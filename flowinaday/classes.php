<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
    <?php include 'html_head.php'; ?>
    <?php
    /*session_start();  */
    
  
    echo "<!-- Debug: ";
    echo "logged_in = " . (isset($_SESSION['logged_in']) ? $_SESSION['logged_in'] : 'not set') . ", ";
    echo "userid = " . (isset($_SESSION['userid']) ? $_SESSION['userid'] : 'not set');
    echo " -->";

    $isLoggedIn = (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) ? 'true' : 'false';
    ?>
    <script>
        window.isLoggedIn = <?php echo $isLoggedIn; ?>;
        console.log("User logged in:", window.isLoggedIn);
    </script>
    <script src="script.js"></script>
</head>
<body class="indexbody">
    <?php include 'header.php'; ?>
    <main class="indexmain">
  <!-- The Modal -->
  <div id="myModal" class="modal hidden">
    <div class="modal-content">
      <span class="close" onclick="closeModal()">&times;</span>
      <img id="classImage" alt="Class image">
      <h2 id="classTitle"></h2>
      <p id="classDescription"></p>
      <p id="classInstructor"></p>
      <p id="classSchedule"></p>
      <p id="classPrice"></p>
      <p id="classCapacity"></p>
      <button class="btn-bookclass" onclick="reserveClass()">Book now</button>
    </div>
  </div>

  <!-- reservation confirm modal -->
  <div id="confirmModal" class="modal hidden">
    <div class="modal-content">
      <p>Do you want to book the class?</p>
      <button id="confirmYesBtn">Yes</button>
      <button id="confirmNoBtn">No</button>
    </div>
  </div>

  <!-- message modal -->
  <div id="messageModal" class="modal hidden">
    <div class="modal-content">
      <p id="messageText"></p>
      <button id="messageCloseBtn">Close</button>
      <button id="loginBtn">Log in</button>
    </div>
  </div>
</main>

       <div class="h1class">
                <h1>One Day Yoga Classes</h1>
                <p>Choose the class that's just right for you!</p>
        </div>
        <section class="container">
            <?php
            include "db_open.php";

            $sql = "SELECT classid, name, instructor, schedule, price, capacity, description, image_path FROM yoga_classes";
            $result = $dbc->query($sql);
            function displayCard($row) {
                echo '<div class="class-card" 
                    onclick="showDetails(this)" 
                    data-classid="' . htmlspecialchars($row['classid']) . '"
                    data-name="' . htmlspecialchars($row['name']) . '"
                    data-description="' . htmlspecialchars($row['description']) . '"
                    data-instructor="' . htmlspecialchars($row['instructor']) . '"
                    data-schedule="' . htmlspecialchars($row['schedule']) . '"
                    data-price="' . htmlspecialchars($row['price']) . '"
                    data-capacity="' . htmlspecialchars($row['capacity']) . '"
                    data-image="' . htmlspecialchars($row['image_path']) . '">';
            
                echo '<h3>' . htmlspecialchars($row['name']) . '</h3>';
                echo '<p>Instructor: ' . htmlspecialchars($row['instructor']) . '</p>';
                echo '<img src="' . htmlspecialchars($row['image_path']) . '" alt="Class Image">';
                echo '</div>';
            }
            

            while ($row = mysqli_fetch_assoc($result)) {
                displayCard($row);
            }

            include "db_close.php";
            ?>
        </section>
    </main>

    <?php include 'footer.php'; ?>
</body>
</html>
