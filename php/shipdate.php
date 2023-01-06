<?php
// Function to parse and update the shipdate_expected field in the table
function updateShipDate() {
    global $conn;
    
    // Select all comments from the table
    $sql = 'SELECT * FROM sweetwater_test';
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        // Loop through the comments and extract the ship date if it exists
        while ($row = mysqli_fetch_assoc($result)) {
            $id = $row['orderid'];
            $comment = $row['comments'];
            if (preg_match('/expected ship date: (\d{4}-\d{2}-\d{2})/i', $comment, $matches)) {
                $ship_date = $matches[1];
                // Update the shipdate_expected field in the table
                $sql = "UPDATE sweetwater_test SET shipdate_expected='$ship_date' WHERE orderid='$id'";
                mysqli_query($conn, $sql);
            }
        }
    }
}
