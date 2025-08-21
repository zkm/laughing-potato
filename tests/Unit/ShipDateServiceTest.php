<?php

namespace Sweetwater\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Sweetwater\Services\ShipDateService;
use Sweetwater\Models\Comment;

/**
 * Unit tests for ShipDateService
 */
class ShipDateServiceTest extends TestCase
{
    private $shipDateService;
    private $mockCommentModel;

    protected function setUp(): void
    {
        $this->mockCommentModel = $this->createMock(Comment::class);
        $this->shipDateService = new ShipDateService($this->mockCommentModel);
    }

    public function testExtractShipDateWithValidFormat(): void
    {
        // Test various valid formats
        $testCases = [
            'Expected ship date: 2025-12-25' => '2025-12-25',
            'EXPECTED SHIP DATE: 2025-01-15' => '2025-01-15',
            'expected ship date: 2025-06-30' => '2025-06-30',
            'Please note expected ship date: 2025-03-10 for this order' => '2025-03-10',
        ];

        foreach ($testCases as $comment => $expectedDate) {
            $result = $this->shipDateService->extractShipDate($comment);
            $this->assertEquals($expectedDate, $result, "Failed for comment: $comment");
        }
    }

    public function testExtractShipDateWithInvalidFormat(): void
    {
        $testCases = [
            'Ship date expected: 2025-12-25', // Different wording
            'Expected delivery date: 2025-12-25', // Different keyword
            'No date mentioned',
            'Expected ship date: invalid-date',
            'Expected ship date 2025-12-25', // Missing colon
        ];

        foreach ($testCases as $comment) {
            $result = $this->shipDateService->extractShipDate($comment);
            $this->assertNull($result, "Should return null for comment: $comment");
        }
    }

    public function testIsValidDateWithValidDates(): void
    {
        $validDates = [
            '2025-01-01',
            '2025-12-31',
            '2025-02-28',
            '2024-02-29', // Leap year
            '2025-06-15',
        ];

        foreach ($validDates as $date) {
            $result = $this->shipDateService->isValidDate($date);
            $this->assertTrue($result, "Date $date should be valid");
        }
    }

    public function testIsValidDateWithInvalidDates(): void
    {
        $invalidDates = [
            '2025-13-01', // Invalid month
            '2025-02-30', // Invalid day for February
            '2025-00-15', // Invalid month
            '2025-12-32', // Invalid day
            '2025-02-29', // Not a leap year
            'abcd-ef-gh', // Non-numeric
            '25-12-01',   // Wrong year format
            '2025-2-5',   // Missing leading zeros
        ];

        foreach ($invalidDates as $date) {
            $result = $this->shipDateService->isValidDate($date);
            $this->assertFalse($result, "Date $date should be invalid");
        }
    }

    public function testProcessShipDatesSuccess(): void
    {
        // Arrange
        $mockComments = [
            ['orderid' => 1, 'comments' => 'Expected ship date: 2025-12-25'],
            ['orderid' => 2, 'comments' => 'Expected ship date: 2025-01-15'],
            ['orderid' => 3, 'comments' => 'No date mentioned'],
        ];

        $this->mockCommentModel
            ->expects($this->once())
            ->method('getCommentsForShipDateProcessing')
            ->willReturn($mockComments);

        // Expect updateShipDate to be called twice (for orders 1 and 2)
        $this->mockCommentModel
            ->expects($this->exactly(2))
            ->method('updateShipDate')
            ->willReturn(true);

        // Act
        $result = $this->shipDateService->processShipDates();

        // Assert
        $this->assertEquals(3, $result['processed']);
        $this->assertEquals(2, $result['updated']);
        $this->assertEmpty($result['errors']);
    }

    public function testProcessShipDatesWithErrors(): void
    {
        // Arrange
        $mockComments = [
            ['orderid' => 1, 'comments' => 'Expected ship date: 2025-13-45'], // Invalid date
            ['orderid' => 2, 'comments' => 'Expected ship date: 2025-01-15'],
        ];

        $this->mockCommentModel
            ->expects($this->once())
            ->method('getCommentsForShipDateProcessing')
            ->willReturn($mockComments);

        // Only one valid update should be attempted
        $this->mockCommentModel
            ->expects($this->once())
            ->method('updateShipDate')
            ->with(2, '2025-01-15')
            ->willReturn(false); // Simulate database error

        // Act
        $result = $this->shipDateService->processShipDates();

        // Assert
        $this->assertEquals(2, $result['processed']);
        $this->assertEquals(0, $result['updated']);
        $this->assertCount(2, $result['errors']);
        $this->assertStringContainsString('Invalid date format in order 1', $result['errors'][0]);
        $this->assertStringContainsString('Failed to update order 2', $result['errors'][1]);
    }

    public function testGetShipDateStats(): void
    {
        // Arrange
        $mockComments = [
            ['orderid' => 1, 'comments' => 'Test', 'shipdate_expected' => '2025-12-25'],
            ['orderid' => 2, 'comments' => 'Test', 'shipdate_expected' => null],
            ['orderid' => 3, 'comments' => 'Test', 'shipdate_expected' => 'invalid-date'],
            ['orderid' => 4, 'comments' => 'Test', 'shipdate_expected' => null],
        ];

        $this->mockCommentModel
            ->expects($this->once())
            ->method('getAllComments')
            ->willReturn($mockComments);

        // Act
        $result = $this->shipDateService->getShipDateStats();

        // Assert
        $this->assertEquals(4, $result['total_comments']);
        $this->assertEquals(2, $result['with_ship_date']);
        $this->assertEquals(2, $result['without_ship_date']);
        $this->assertEquals(1, $result['valid_dates']);
        $this->assertEquals(1, $result['invalid_dates']);
    }

    public function testProcessShipDatesWithEmptyComments(): void
    {
        // Arrange
        $this->mockCommentModel
            ->expects($this->once())
            ->method('getCommentsForShipDateProcessing')
            ->willReturn([]);

        // Act
        $result = $this->shipDateService->processShipDates();

        // Assert
        $this->assertEquals(0, $result['processed']);
        $this->assertEquals(0, $result['updated']);
        $this->assertEmpty($result['errors']);
    }
}
