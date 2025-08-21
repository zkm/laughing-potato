<?php

namespace Sweetwater\Models;

use Sweetwater\Config\Database;

class Comment
{
    private $db;
    
    public function __construct()
    {
        $this->db = Database::getInstance();
    }
    
    /**
     * Get all comments from the database
     * 
     * @return array
     */
    public function getAllComments(): array
    {
        $sql = 'SELECT * FROM sweetwater_test';
        $result = $this->db->query($sql);
        
        $comments = [];
        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $comments[] = $row;
            }
        }
        
        return $comments;
    }
    
    /**
     * Update ship date for a specific order
     * 
     * @param int $orderId
     * @param string $shipDate
     * @return bool
     */
    public function updateShipDate(int $orderId, string $shipDate): bool
    {
        // Normalize to full DATETIME format (YYYY-MM-DD 00:00:00)
        $dateTime = $shipDate;
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $shipDate)) {
            $dateTime = $shipDate . ' 00:00:00';
        }
        $escapedDate = $this->db->escapeString($dateTime);
        $sql = "UPDATE sweetwater_test SET shipdate_expected='$escapedDate' WHERE orderid=$orderId";
        
        return $this->db->query($sql) !== false;
    }
    
    /**
     * Get comments with ship date parsing capability
     * 
     * @return array
     */
    public function getCommentsForShipDateProcessing(): array
    {
    // Select comments to scan for ship dates.
    // Avoid comparing against invalid zero DATETIME (which can error in MySQL 8 strict modes).
    // We'll simply scan all comments and only update rows where a valid date is extracted.
    $sql = 'SELECT orderid, comments FROM sweetwater_test';
        $result = $this->db->query($sql);
        
        $comments = [];
        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $comments[] = $row;
            }
        }
        
        return $comments;
    }
}
