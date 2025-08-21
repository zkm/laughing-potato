<?php

namespace Sweetwater\Services;

use Sweetwater\Models\Comment;

class ShipDateService
{
    private $commentModel;
    
    public function __construct(Comment $commentModel = null)
    {
        $this->commentModel = $commentModel ?? new Comment();
    }
    
    /**
     * Process all comments and update ship dates where found
     * 
     * @return array Processing results
     */
    public function processShipDates(): array
    {
        $comments = $this->commentModel->getCommentsForShipDateProcessing();
        $results = [
            'processed' => 0,
            'updated' => 0,
            'errors' => []
        ];
        
        foreach ($comments as $comment) {
            $results['processed']++;
            
            $shipDate = $this->extractShipDate($comment['comments']);
            if ($shipDate) {
                if ($this->isValidDate($shipDate)) {
                    $success = $this->commentModel->updateShipDate(
                        $comment['orderid'], 
                        $shipDate
                    );
                    
                    if ($success) {
                        $results['updated']++;
                    } else {
                        $results['errors'][] = "Failed to update order {$comment['orderid']}";
                    }
                } else {
                    $results['errors'][] = "Invalid date format in order {$comment['orderid']}: $shipDate";
                }
            }
        }
        
        return $results;
    }
    
    /**
     * Extract ship date from comment text
     * 
     * @param string $comment
     * @return string|null
     */
    public function extractShipDate(string $comment): ?string
    {
        // Match "Expected ship date: YYYY-MM-DD" (case insensitive)
        if (preg_match('/expected\s+ship\s+date:\s*(\d{4}-\d{2}-\d{2})/i', $comment, $matches)) {
            return $matches[1];
        }
        
        return null;
    }
    
    /**
     * Validate date format and logical validity
     * 
     * @param string $date
     * @return bool
     */
    public function isValidDate(string $date): bool
    {
        // Check format YYYY-MM-DD
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            return false;
        }
        
        // Check if date is actually valid
        $parts = explode('-', $date);
        $year = (int)$parts[0];
        $month = (int)$parts[1];
        $day = (int)$parts[2];
        
        return checkdate($month, $day, $year);
    }
    
    /**
     * Get ship date statistics
     * 
     * @return array
     */
    public function getShipDateStats(): array
    {
        $comments = $this->commentModel->getAllComments();
        $stats = [
            'total_comments' => count($comments),
            'with_ship_date' => 0,
            'without_ship_date' => 0,
            'valid_dates' => 0,
            'invalid_dates' => 0
        ];
        
        foreach ($comments as $comment) {
            $ship = $comment['shipdate_expected'] ?? null;
            if ($ship) {
                $stats['with_ship_date']++;
                if ($this->isValidDate(substr($ship, 0, 10))) {
                    $stats['valid_dates']++;
                } else {
                    $stats['invalid_dates']++;
                }
            } else {
                $stats['without_ship_date']++;
            }
        }
        
        return $stats;
    }
}
