<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include "db_open.php";

if (isset($_GET["classid"])) {
    $classid = intval($_GET["classid"]); 

    // Avoid injection 
    $stmt = $dbc->prepare("DELETE FROM yoga_classes WHERE classid = ?");
    $stmt->bind_param("i", $classid);
    $stmt->execute();
    $stmt->close();

    // Redirect back to the user management page after deletion
    header("Location: classmanagement.php");
    exit();
}
include "db_close.php";
?>