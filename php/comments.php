<?php
// Function to retrieve comments from the table and group them by category
function getComments() {
    global $conn;
    
    // Initialize an empty associative array to store the comments
    $comments = array(
        'candy' => array(),
        'call me' => array(),
        'referred' => array(),
        'signature' => array(),
        'misc' => array()
    );
    
    // Select all comments from the table
    $sql = 'SELECT * FROM sweetwater_test';
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        // Loop through the comments and classify them into categories
        while ($row = mysqli_fetch_assoc($result)) {
            $comment = $row['comments'];
            if (preg_match('/candy/i', $comment)) {
                $comments['candy'][] = $comment;
            } else if (preg_match('/call me/i', $comment)) {
                $comments['call me'][] = $comment;
            } else if (preg_match('/referred/i', $comment)) {
                $comments['referred'][] = $comment;
            } else if (preg_match('/signature/i', $comment)) {
                $comments['signature'][] = $comment;
            } else {
                $comments['misc'][] = $comment;
            }
        }
    }
    
    return $comments;
}
