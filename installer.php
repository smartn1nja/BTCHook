<?php
include 'config.php';
include 'functions.php';
// sql to create table
$sql = "CREATE TABLE `rates` ( `id` int(11) NOT NULL, `rate` varchar(255) NOT NULL, `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
if ($conn->query($sql) === TRUE) {
    echo "Rates table created successfully! Please delete <strong>Installer.php</strong>";
} else {
    echo "Error creating table: " . $conn->error;
}
