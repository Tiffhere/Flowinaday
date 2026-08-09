<?php
$servername = "localhost";
$username = "USERNAME";
$password = "PASSWORD";
$dbname = "DBNAME";


//Create connection
$dbc = new mysqli($servername, $username, $password, $dbname);
//Check connection
if ($dbc->connect_error) {
  die("Connection failed: " . $dbc->connect_error);
}
//echo "Connected successfully!";

//Display card about the book, including image, title, author and view details button
/*function displayCard($row) {
    echo '<div class="card">';
    echo '<img src="images/'.$row['ImagePath'].'" alt="'.$row['Title'].'">';
    echo '<h4>'.$row['Title'].'</h4>';
    echo '<a href="#" onclick="showDetail(document.querySelector(\'.target\'),\''

     . '<img src=&quot;images/' . $row['ImagePath'] . '&quot;> <br>'
     . '<strong>ISBN:</strong> ' . $row['ISBN'] . '<br>'
     . '<strong>Author:</strong> ' . $row['Author'] . '<br>'
     . '<strong>Publisher:</strong> ' . $row['Publisher'] . '<br>'
     . '<strong>Publish Date:</strong> ' . $row['PublishDate'] . '<br>'
     . '<strong>Title:</strong> ' .$row['Title'] . '<br>'
     . '<strong>Description:</strong> '.$row['Description']
     . '\'); return false;">View Details</a>';
    echo '</div>';
}
*/

?>





