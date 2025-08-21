<?php

namespace Sweetwater\Services;

use Sweetwater\Models\Comment;

class CommentService
{
    private $commentModel;
    
    public function __construct(Comment $commentModel = null)
    {
        $this->commentModel = $commentModel ?? new Comment();
    }
    
    /**
     * Categorize comments into different groups
     * 
     * @return array
     */
    public function categorizeComments(): array
    {
        $comments = $this->commentModel->getAllComments();
        
        $categorized = [
            'candy' => [],
            'call_me' => [],
            'referred' => [],
            'signature' => [],
            'misc' => []
        ];
        
        foreach ($comments as $comment) {
            $text = $this->normalizeText($comment['comments']);
            $category = $this->determineCategory($text);
            $categorized[$category][] = $text;
        }
        
        return $categorized;
    }

    /**
     * Normalize common mojibake and Windows-1252 smart quotes to UTF-8 friendly characters
     */
    private function normalizeText(string $text): string
    {
        // First try to ensure string is valid UTF-8
        if (!mb_check_encoding($text, 'UTF-8')) {
            $converted = @iconv('Windows-1252', 'UTF-8//IGNORE', $text);
            if ($converted !== false) {
                $text = $converted;
            }
        }

        // Replace common mojibake sequences
        $replacements = [
            'Â’' => "'",
            'â€™' => "'",
            "\xC2\x92" => "'", // 0x92 shown via UTF-8 bytes
            '’' => "'",           // normalize curly to straight
            '‘' => "'",           // left single quote to straight
            '`' => "'",           // backtick to apostrophe
            '´' => "'",           // acute accent to apostrophe
            'Â“' => '"',
            'â€œ' => '"',
            'Â”' => '"',
            'â€' => '"',
            '“' => '"',
            '”' => '"',
            'â€“' => '-',
            'â€”' => '-',
            '–' => '-',
            '—' => '-',
        ];
        $text = strtr($text, $replacements);

        return $text;
    }
    
    /**
     * Determine which category a comment belongs to
     * 
     * @param string $comment
     * @return string
     */
    private function determineCategory(string $comment): string
    {
        $comment = strtolower($comment);
        
        if (preg_match('/candy/i', $comment)) {
            return 'candy';
        } elseif (preg_match('/call\s+me|don\'?t\s+call/i', $comment)) {
            return 'call_me';
        } elseif (preg_match('/referred/i', $comment)) {
            return 'referred';
        } elseif (preg_match('/signature/i', $comment)) {
            return 'signature';
        } else {
            return 'misc';
        }
    }
    
    /**
     * Get comment statistics
     * 
     * @return array
     */
    public function getCommentStats(): array
    {
        $categorized = $this->categorizeComments();
        
        return [
            'total' => array_sum(array_map('count', $categorized)),
            'by_category' => array_map('count', $categorized)
        ];
    }
}
