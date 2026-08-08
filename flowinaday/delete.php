<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include "db_open.php";

if (isset($_GET["id"])) {
    $id = intval($_GET["id"]); 

    // Avoid injection 
    $stmt = $dbc->prepare("DELETE FROM user WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    // Redirect back to the user management page after deletion
    header("Location: usermanagement.php");
    exit();
}
include "db_close.php";
?>