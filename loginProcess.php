<?php
  $uName = $_GET["uname"];
  $pWd = $_GET["password"];
  $servername = "localhost";
  $username = "root";
  $password = "Sadie";
  $dbname = "testdb";
  $conn = mysqli_connect($servername, $username, $password, $dbname);
  $query = "SELECT `userID`, `pwd` FROM `users` WHERE `username` = \"".$uName."\";";
  $userData = mysqli_query($conn, $query);
  $row = mysqli_fetch_assoc($userData);
  if (mysqli_num_rows($userData) == 1) {
    if ($pWd = $row["pwd"]) {
      echo $row["pwd"];
      header('Location: admin.php');
    }
  } else {
    echo $query;
    echo "<br>Ok, <i>more</i> interesting";
    echo $row["pwd"];
  }
 ?>
