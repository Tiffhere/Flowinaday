<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include "db_open.php";

$sql = "SELECT * FROM user";
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
        <section class="admin-table-section">
            <div class="nav-shortcut">
                <a href="usermanagement.php" class="<?php if(basename($_SERVER['PHP_SELF']) == 'usermanagement.php') echo 'active-link'; ?>">Users</a>
                <a href="classmanagement.php" class="<?php if(basename($_SERVER['PHP_SELF']) == 'classmanagement.php') echo 'active-link'; ?>">Classes</a>
                <br>
                <a href="create.php" class="btn-adduser">Add User</a>    
            </div>
            <table class="admintable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Password</th>
                        <th>User Type</th>
                        <th>Email</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php while($row = $result->fetch_assoc()){
                    echo "
                        <tr>
                            <td data-label='ID'>{$row['id']}</td>
                            <td data-label='Username'>{$row['username']}</td>
                            <td data-label='Password' class='passwordlength'>{$row['password']}</td>
                            <td data-label='User Type'>{$row['usertype']}</td>
                            <td data-label='Email'>{$row['email']}</td>
                            <td data-label='Action'>
                                <a href='edit.php?id={$row['id']}' class='btn btn-edit'>Edit</a>
                                <a href='delete.php?id={$row['id']}' class='btn btn-delete btn-delete-user'>Delete</a>
                            </td>
                        </tr>  
                    ";
                } ?>
                     
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