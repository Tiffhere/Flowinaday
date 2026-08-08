<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include "db_open.php";

// Initialize variables
$classid = $name = $instructor = $schedule = $price = $capacity = $description = "";
$nameErr = $instructorErr = $scheduleErr = $priceErr = $capacityErr = $descriptionErr = "";
$SuccessMessage = $errorMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Show the form with current class data
    if (!isset($_GET["classid"])) {
        header("Location: classmanagement.php");
        exit();
    }

    $classid = intval($_GET["classid"]);
    $sql = "SELECT * FROM yoga_classes WHERE classid = ?";
    $stmt = $dbc->prepare($sql);
    $stmt->bind_param("i", $classid);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        header("Location: classmanagement.php");
        exit();
    }

    $row = $result->fetch_assoc();
    $name = $row["name"];
    $instructor = $row["instructor"];
    $schedule = $row["schedule"];
    $price = $row["price"];
    $capacity = $row["capacity"];
    $description = $row["description"];
} else {
    // Handle form submission (update)
    $classid = $_POST["classid"];
    $name = trim($_POST["name"]);
    $instructor = trim($_POST["instructor"]);
    $schedule = trim($_POST["schedule"]);
    $price = trim($_POST["price"]);
    $capacity = trim($_POST["capacity"]);
    $description = trim($_POST["description"]);

    // Validation
    if (empty($name)) {
        $nameErr = "Class name is required";
    }
    if (empty($instructor)) {
        $instructorErr = "Instructor is required";
    }
    if (empty($schedule)) {
        $scheduleErr = "Schedule is required";
    }
    if (empty($price) || !is_numeric($price)) {
        $priceErr = "Valid price is required";
    }
    if (empty($capacity) || !is_numeric($capacity)) {
        $capacityErr = "Valid capacity is required";
    }
    if (empty($description)) {
        $descriptionErr = "Description is required";
    }

    // If no validation errors, proceed
    if (empty($nameErr) && empty($instructorErr) && empty($scheduleErr) && 
        empty($priceErr) && empty($capacityErr) && empty($descriptionErr)) {
        
        $sql = "UPDATE yoga_classes SET 
                name = ?, 
                instructor = ?, 
                schedule = ?, 
                price = ?, 
                capacity = ?, 
                description = ? 
                WHERE classid = ?";
        
        $stmt = $dbc->prepare($sql);
        $stmt->bind_param("ssssisi", 
            $name, 
            $instructor, 
            $schedule, 
            $price, 
            $capacity, 
            $description, 
            $classid
        );

        if ($stmt->execute()) {
            $SuccessMessage = "Class updated successfully";
            $stmt->close();
            header("Location: classmanagement.php");
            exit();
        } else {
            $errorMessage = "Error updating class: " . $dbc->error;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <?php include 'html_head.php'; ?>
</head>
<body>
    <?php include 'header.php'; ?>
    <main>
        <section class="editclass">
            <div>
                <h2>Edit Yoga Class</h2>
                <?php if (!empty($errorMessage)): ?>
                    <div class="error"><?php echo $errorMessage; ?></div>
                <?php endif; ?>
                <form action="" method="post">
                    <input type="hidden" name="classid" value="<?php echo htmlspecialchars($classid); ?>">
                    
                    <label>Class Name</label><br>
                    <input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>">
                    <span class="error"><?php echo $nameErr; ?></span><br>

                    <label>Instructor</label><br>
                    <input type="text" name="instructor" value="<?php echo htmlspecialchars($instructor); ?>">
                    <span class="error"><?php echo $instructorErr; ?></span><br>

                    <label>Schedule</label><br>
                    <input type="datetime-local" name="schedule" value="<?php echo htmlspecialchars($schedule); ?>">
                    <span class="error"><?php echo $scheduleErr; ?></span><br>

                    <label>Price</label><br>
                    <input type="number" step="0.01" name="price" value="<?php echo htmlspecialchars($price); ?>">
                    <span class="error"><?php echo $priceErr; ?></span><br>

                    <label>Capacity</label><br>
                    <input type="number" name="capacity" value="<?php echo htmlspecialchars($capacity); ?>">
                    <span class="error"><?php echo $capacityErr; ?></span><br>

                    <label>Description</label><br>
                    <textarea name="description"><?php echo htmlspecialchars($description); ?></textarea>
                    <span class="error"><?php echo $descriptionErr; ?></span><br>

                    <input type="submit" value="Update">
                    
                    <?php if (!empty($SuccessMessage)): ?>
                        <div class="success">
                            <?php echo $SuccessMessage; ?>
                            <a href="classmanagement.php">Back to Class Management</a>
                        </div>
                    <?php endif; ?>
                </form>
            </div>
        </section>
    </main>
    <?php include 'footer.php'; ?>
</body>
</html>
<?php
include "db_close.php";
?>
