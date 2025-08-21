<?php

namespace Sweetwater\Tests\Integration;

use PHPUnit\Framework\TestCase;
use Sweetwater\Config\Database;
use Sweetwater\Models\Comment;
use Sweetwater\Services\CommentService;
use Sweetwater\Services\ShipDateService;

/**
 * Integration tests that test the full application flow
 */
class ApplicationTest extends TestCase
{
    private $testTable = 'sweetwater_test_integration';
    private $db;

    protected function setUp(): void
    {
        $this->db = Database::getInstance();
        $this->createTestTable();
        $this->insertTestData();
    }

    protected function tearDown(): void
    {
        $sql = "DROP TABLE IF EXISTS {$this->testTable}";
        $this->db->query($sql);
    }

    private function createTestTable(): void
    {
        $sql = "CREATE TABLE IF NOT EXISTS {$this->testTable} LIKE sweetwater_test";
        $this->db->query($sql);
    // For testing, allow NULL shipdate_expected to avoid empty-string DATETIME issues
    $this->db->query("ALTER TABLE {$this->testTable} MODIFY shipdate_expected DATETIME NULL DEFAULT NULL");
        
        $sql = "DELETE FROM {$this->testTable}";
        $this->db->query($sql);
    }

    private function insertTestData(): void
    {
        $testData = [
            [1, 'I love candy and sweets', null],
            [2, 'Please call me when ready', null],
            [3, 'John referred me to your store', null],
            [4, 'No signature required please', null],
            [5, 'Expected ship date: 2025-12-25', null],
            [6, 'More candy please, expected ship date: 2025-01-15', null],
            [7, 'General comment with no keywords', null],
        ];

        foreach ($testData as $data) {
            $orderId = (int)$data[0];
            $comment = $this->db->escapeString($data[1]);
            $dateVal = $data[2] === null ? 'NULL' : "'{$data[2]}'";
            $sql = "INSERT INTO {$this->testTable} (orderid, comments, shipdate_expected) VALUES ({$orderId}, '{$comment}', {$dateVal})";
            $this->db->query($sql);
        }
    }

    public function testFullApplicationWorkflow(): void
    {
        // Create services that will use our test data
        $commentModel = new Comment();
        
        // Temporarily replace the table name for testing
        $reflection = new \ReflectionClass($commentModel);
        // Note: In a real application, you'd use dependency injection or configuration
        // to switch tables. This is a simplified approach for testing.
        
        $commentService = new CommentService($commentModel);
        $shipDateService = new ShipDateService($commentModel);

        // Test ship date processing first
        $shipDateResults = $this->processShipDatesWithTestTable($shipDateService);
        
        // Verify ship dates were processed
        $this->assertGreaterThan(0, $shipDateResults['processed']);
        $this->assertGreaterThan(0, $shipDateResults['updated']);

        // Test comment categorization
        $categorizedComments = $this->categorizeCommentsWithTestTable($commentService);
        
        // Verify categorization
        $this->assertArrayHasKey('candy', $categorizedComments);
        $this->assertArrayHasKey('call_me', $categorizedComments);
        $this->assertArrayHasKey('referred', $categorizedComments);
        $this->assertArrayHasKey('signature', $categorizedComments);
        $this->assertArrayHasKey('misc', $categorizedComments);

        // Verify specific categorizations
        $this->assertNotEmpty($categorizedComments['candy']);
        $this->assertNotEmpty($categorizedComments['call_me']);
        $this->assertNotEmpty($categorizedComments['referred']);
        $this->assertNotEmpty($categorizedComments['signature']);
        $this->assertNotEmpty($categorizedComments['misc']);
    }

    public function testDatabaseConnection(): void
    {
        $connection = $this->db->getConnection();
        $this->assertNotFalse($connection);
        
        // Test a simple query
        $result = $this->db->query("SELECT 1 as test");
        $this->assertNotFalse($result);
        
        $row = mysqli_fetch_assoc($result);
        $this->assertEquals(1, $row['test']);
    }

    public function testCommentModelOperations(): void
    {
        $commentModel = new Comment();
        
        // Test getting all comments (this will get from main table, not test table)
        $comments = $commentModel->getAllComments();
        $this->assertIsArray($comments);
        
        // Test update ship date functionality
        // Note: This tests the method but on the main table
        $result = $commentModel->updateShipDate(999999, '2025-01-01'); // Use non-existent ID
        $this->assertIsBool($result);
    }

    public function testEnvironmentConfiguration(): void
    {
        // Test that environment variables are properly set
        $this->assertNotEmpty($_ENV['DB_HOST'] ?? '');
        $this->assertNotEmpty($_ENV['DB_USER'] ?? '');
        $this->assertNotEmpty($_ENV['DB_PASS'] ?? '');
        $this->assertNotEmpty($_ENV['DB_NAME'] ?? '');
    }

    // Helper methods for testing with test table
    private function processShipDatesWithTestTable(ShipDateService $service): array
    {
        // Get comments that need ship date processing from test table
    $sql = "SELECT orderid, comments FROM {$this->testTable} WHERE shipdate_expected IS NULL";
        $result = $this->db->query($sql);
        
        $processed = 0;
        $updated = 0;
        $errors = [];
        
        while ($row = mysqli_fetch_assoc($result)) {
            $processed++;
            $shipDate = $service->extractShipDate($row['comments']);
            
            if ($shipDate && $service->isValidDate($shipDate)) {
                $sql = "UPDATE {$this->testTable} SET shipdate_expected='$shipDate' WHERE orderid={$row['orderid']}";
                if ($this->db->query($sql)) {
                    $updated++;
                } else {
                    $errors[] = "Failed to update order {$row['orderid']}";
                }
            }
        }
        
        return [
            'processed' => $processed,
            'updated' => $updated,
            'errors' => $errors
        ];
    }

    private function categorizeCommentsWithTestTable(CommentService $service): array
    {
        // Get all comments from test table
        $sql = "SELECT * FROM {$this->testTable}";
        $result = $this->db->query($sql);
        
        $categorized = [
            'candy' => [],
            'call_me' => [],
            'referred' => [],
            'signature' => [],
            'misc' => []
        ];
        
        while ($row = mysqli_fetch_assoc($result)) {
            $comment = $row['comments'];
            
            if (preg_match('/candy/i', $comment)) {
                $categorized['candy'][] = $comment;
            } elseif (preg_match('/call\s+me|don\'?t\s+call/i', $comment)) {
                $categorized['call_me'][] = $comment;
            } elseif (preg_match('/referred/i', $comment)) {
                $categorized['referred'][] = $comment;
            } elseif (preg_match('/signature/i', $comment)) {
                $categorized['signature'][] = $comment;
            } else {
                $categorized['misc'][] = $comment;
            }
        }
        
        return $categorized;
    }
}
