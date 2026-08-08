<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include "db_open.php";

$sql = "SELECT * FROM yoga_classes";
$result = $dbc ->query($sql);

if(!$result){
    die("Invalid query: ".$dbc->error);
}

?>

<!DOCTYPE html>
    <head>
        <?php include 'html_head.php' ?>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="script.js"></script>
    </head>
    <body>
        <?php include 'header.php' ?>
       
    <main>
        <section>
            <div class="nav-shortcut">
                <a href="usermanagement.php" class="<?php if(basename($_SERVER['PHP_SELF']) == 'usermanagement.php') echo 'active-link'; ?>">Users</a>
                <a href="classmanagement.php" class="<?php if(basename($_SERVER['PHP_SELF']) == 'classmanagement.php') echo 'active-link'; ?>">Classes</a>
                <br>
                <a href="createclass.php" class="btn-adduser">Add Class</a>    
            </div>
            <table class="admintable">
                <thead>
                    <tr>
                        <th>ClassID</th>
                        <th>Name</th>
                        <th>Instructor</th>
                        <th>Schedule</th>
                        <th>Price</th>
                        <th>Capacity</th>
                        <th>Description</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $result->fetch_assoc()){
                        echo"
                            <tr>
                                <td data-label='Classid'>$row[classid]</td>
                                <td data-label='Name'>$row[name]</td>
                                <td data-label='Instructor'>$row[instructor]</td>
                                <td data-label='Schedule'>$row[schedule]</td>
                                <td data-label='Price'>$row[price]</td>
                                <td data-label='Capacity'>$row[capacity]</td>
                                <td data-label='Description'>$row[description]</td>
                                <td>
                                    <a href='editclass.php?classid={$row['classid']}' class='btn btn-edit'>Edit</a>
                                    <a href='deleteclass.php?classid={$row['classid']}' class='btn btn-delete btn-delete-class'>Delete</a>

                                </td>
                            </tr>  
                        ";
                    }
                    ?>
                     
                </tbody>    
            </table>
            
            
        </section>
    </main>
        <?php include 'footer.php' ?>
    </body>
</html>
<?php
include "db_close.php";
?>