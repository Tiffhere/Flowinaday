<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include "db_open.php";

$SuccessMessage = "";
$nameErr = $instructorErr = $scheduleErr = $priceErr = $capacityErr = $descriptionErr = "";
$name = $instructor = $schedule = $price = $capacity = $description = "";

// Helper function
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = test_input($_POST["name"]);
    $instructor = test_input($_POST["instructor"]);
    $schedule = test_input($_POST["schedule"]);
    $price = test_input($_POST["price"]);
    $capacity = test_input($_POST["capacity"]);
    $description = test_input($_POST["description"]);

    // Set a default image path since image upload is not required
    $imagePath = "images/default.png";

    do {
        // Validate Class Name
        if (empty($name)) {
            $nameErr = "Class name is required";
            break;
        }

        // Validate Instructor
        if (empty($instructor)) {
            $instructorErr = "Instructor name is required";
            break;
        }

        // Validate Schedule
        if (empty($schedule)) {
            $scheduleErr = "Schedule is required";
            break;
        }

        // Validate Price
        if (empty($price)) {
            $priceErr = "Price is required";
            break;
        } elseif (!is_numeric($price)) {
            $priceErr = "Price must be a number";
            break;
        }

        // Validate Capacity
        if (empty($capacity)) {
            $capacityErr = "Capacity is required";
            break;
        } elseif (!filter_var($capacity, FILTER_VALIDATE_INT)) {
            $capacityErr = "Capacity must be a whole number";
            break;
        }

        // Validate Description
        if (empty($description)) {
            $descriptionErr = "Description is required";
            break;
        }

        // Add to database
        $stmt = $dbc->prepare("INSERT INTO yoga_classes (name, instructor, schedule, price, capacity, description, image_path) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssdiss", $name, $instructor, $schedule, $price, $capacity, $description, $imagePath);

        if ($stmt->execute()) {
            $SuccessMessage = "Class added successfully";
            // Reset fields
            $name = $instructor = $schedule = $price = $capacity = $description = "";
        } else {
            $SuccessMessage = "Error adding class: " . $stmt->error;
        }

        $stmt->close();

    } while(false);
}
?>

<!DOCTYPE html>
    <head>
        <?php include 'html_head.php' ?>
    </head>
    <body>
        <?php include 'header.php' ?>
       
    <main>
        <section class="createclass">
                <h2>New Yoga Class</h2>
                <form action="" method="post">
                    <label>Class Name</label><br>
                    <input type="text" name="name" value="<?php echo $name ?>">
                    <span class="error"><?php echo $nameErr ?></span><br>

                    <label>Instructor</label><br>
                    <input type="text" name="instructor" value="<?php echo $instructor ?>">
                    <span class="error"><?php echo $instructorErr ?></span><br>

                    <label>Schedule</label><br>
                    <input type="datetime-local" name="schedule" value="<?php echo $schedule ?>">
                    <span class="error"><?php echo $scheduleErr ?></span><br>

                    <label>Price</label><br>
                    <input type="number" step="0.01" name="price" value="<?php echo $price ?>">
                    <span class="error"><?php echo $priceErr ?></span><br>

                    <label>Capacity</label><br>
                    <input type="number" name="capacity" value="<?php echo $capacity ?>">
                    <span class="error"><?php echo $capacityErr ?></span><br>

                    <label>Description</label><br>
                    <textarea name="description"><?php echo $description ?></textarea>
                    <span class="error"><?php echo $descriptionErr ?></span><br>

                    <input type="submit" value="Submit">
                    
                    <?php if(!empty($SuccessMessage)): ?>
                        <div class="success">
                            <?php echo $SuccessMessage ?><br>
                            <a href="classmanagement.php">Back to Classes</a>
                        </div>
                    <?php endif; ?>
                </form>
        </section>
    </main>
    <?php include 'footer.php' ?>
    </body>
</html>
<?php
include "db_close.php";
?>
