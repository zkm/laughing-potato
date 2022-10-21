<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
    .wrapper {
        width: 600px;
        margin: 0 auto;
    }

    table tr td:last-child {
        width: 120px;
    }
    </style>
    <script>
    $(document).ready(function() {
        $('[data-toggle="tooltip"]').tooltip();
    });
    </script>
</head>

<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <?php
                    require_once "config.php";

                    // Attempt select query execution
                    $sql = "SELECT * FROM sweetwater_test";
                    $candySql = "SELECT * FROM `sweetwater_test` WHERE `comments` LIKE '%candy%'";
                    $callSql = "SELECT * FROM `sweetwater_test` WHERE `comments` LIKE '%call me%'";
                    $referredSql = "SELECT * FROM `sweetwater_test` WHERE `comments` LIKE '%referred%'";
                    $signatureSql = "SELECT * FROM `sweetwater_test` WHERE `comments` LIKE '%signature%'";
                    $miscSql = "SELECT * FROM `sweetwater_test` WHERE `comments` NOT LIKE '%candy%' AND `comments` NOT LIKE '%call me%' AND `comments` NOT LIKE '%referred%' AND `comments` NOT LIKE '%signature%'";

                    // Candy
                    if ($result = $mysqli->query($candySql)) {
                        if ($result->num_rows > 0) {
                            echo '<h3>Candy Comments</h3>';
                            echo '<table class="table table-bordered table-striped">';
                            echo "<thead>";
                            echo "<tr>";
                            echo "<th>#</th>";
                            echo "<th>Comment</th>";
                            echo "<th>Ship Date</th>";
                            echo "</tr>";
                            echo "</thead>";
                            echo "<tbody>";
                            while ($row = $result->fetch_array()) {
                                echo "<tr>";
                                echo "<td>" . $row['orderid'] . "</td>";
                                echo "<td>" . $row['comments'] . "</td>";
                                echo "<td>" . $row['shipdate_expected'] . "</td>";
                                echo "</tr>";
                            }
                            echo "</tbody>";
                            echo "</table>";
                            // Free result set
                            $result->free();
                        } else {
                            echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
                        }
                    } else {
                        echo "Oops! Something went wrong. Please try again later.";
                    }

                    // Call me
                    if ($result = $mysqli->query($callSql)) {
                        if ($result->num_rows > 0) {
                            echo '<h3>Call Me/Don`t Call Me Comments</h3>';
                            echo '<table class="table table-bordered table-striped">';
                            echo "<thead>";
                            echo "<tr>";
                            echo "<th>#</th>";
                            echo "<th>Comment</th>";
                            echo "<th>Ship Date</th>";
                            echo "</tr>";
                            echo "</thead>";
                            echo "<tbody>";
                            while ($row = $result->fetch_array()) {
                                echo "<tr>";
                                echo "<td>" . $row['orderid'] . "</td>";
                                echo "<td>" . $row['comments'] . "</td>";
                                echo "<td>" . $row['shipdate_expected'] . "</td>";
                                echo "</tr>";
                            }
                            echo "</tbody>";
                            echo "</table>";
                            // Free result set
                            $result->free();
                        } else {
                            echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
                        }
                    } else {
                        echo "Oops! Something went wrong. Please try again later.";
                    }

                    // Referred
                    if ($result = $mysqli->query($referredSql)) {
                        if ($result->num_rows > 0) {
                            echo '<h3>Referred Comments</h3>';
                            echo '<table class="table table-bordered table-striped">';
                            echo "<thead>";
                            echo "<tr>";
                            echo "<th>#</th>";
                            echo "<th>Comment</th>";
                            echo "<th>Ship Date</th>";
                            echo "</tr>";
                            echo "</thead>";
                            echo "<tbody>";
                            while ($row = $result->fetch_array()) {
                                echo "<tr>";
                                echo "<td>" . $row['orderid'] . "</td>";
                                echo "<td>" . $row['comments'] . "</td>";
                                echo "<td>" . $row['shipdate_expected'] . "</td>";
                                echo "</tr>";
                            }
                            echo "</tbody>";
                            echo "</table>";
                            // Free result set
                            $result->free();
                        } else {
                            echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
                        }
                    } else {
                        echo "Oops! Something went wrong. Please try again later.";
                    }

                    // Signature
                    if ($result = $mysqli->query($signatureSql)) {
                        if ($result->num_rows > 0) {
                            echo '<h3>Signature Comments</h3>';
                            echo '<table class="table table-bordered table-striped">';
                            echo "<thead>";
                            echo "<tr>";
                            echo "<th>#</th>";
                            echo "<th>Comment</th>";
                            echo "<th>Ship Date</th>";
                            echo "</tr>";
                            echo "</thead>";
                            echo "<tbody>";
                            while ($row = $result->fetch_array()) {
                                echo "<tr>";
                                echo "<td>" . $row['orderid'] . "</td>";
                                echo "<td>" . $row['comments'] . "</td>";
                                echo "<td>" . $row['shipdate_expected'] . "</td>";
                                echo "</tr>";
                            }
                            echo "</tbody>";
                            echo "</table>";
                            // Free result set
                            $result->free();
                        } else {
                            echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
                        }
                    } else {
                        echo "Oops! Something went wrong. Please try again later.";
                    }

                    // Misc
                    if ($result = $mysqli->query($miscSql)) {
                        if ($result->num_rows > 0) {
                            echo '<h3>Misc Comments</h3>';
                            echo '<table class="table table-bordered table-striped">';
                            echo "<thead>";
                            echo "<tr>";
                            echo "<th>#</th>";
                            echo "<th>Comment</th>";
                            echo "<th>Ship Date</th>";
                            echo "</tr>";
                            echo "</thead>";
                            echo "<tbody>";
                            while ($row = $result->fetch_array()) {
                                echo "<tr>";
                                echo "<td>" . $row['orderid'] . "</td>";
                                echo "<td>" . $row['comments'] . "</td>";
                                echo "<td>" . $row['shipdate_expected'] . "</td>";
                                echo "</tr>";
                            }
                            echo "</tbody>";
                            echo "</table>";
                            // Free result set
                            $result->free();
                        } else {
                            echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
                        }
                    } else {
                        echo "Oops! Something went wrong. Please try again later.";
                    }
                    // Close connection
                    $mysqli->close();
                    ?>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
