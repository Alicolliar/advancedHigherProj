<?php
  session_start();
  //Initialising SQL Connection
  $servername = "localhost";
  $username = "root";
  $password = "";
  $dbname = "testdb";
  $conn = mysqli_connect($servername, $username, $password, $dbname);
  //Declaring and Running the Query to Find the Current Elections
  $query2 = "SELECT `type` FROM `elects` WHERE `running` = 1;";
  $curElectData = mysqli_query($conn, $query2);
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Voting Site - Voting Login</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css">
  </head>
  <body>
    <header>
      <h1>Voting Site</h1>
    </header>
    <nav>
      <div class="regularBar">
        <ul>
          <li><a href="index.php">Home</a></li>
          <li><a href="voteLogin.php">Vote Here</a></li>
          <li><a href="archive.php">View Past Elections</a></li>
          <li><a href="login.php">Admin Login</a></li>
        </ul>
      </div>
      <div class="dropdown">
        <button class="dropbtn">Vote Here!</button>
        <div class="dropdown-cont">
          <a href="index.php">Home</a>
          <a href="voteLogin.php">Vote Here</a>
          <a href="archive.php">View Past Elections</a>
          <a href="login.php">Admin Login</a>
        </div>
      </div>
    </nav>
    <main>
      <h2>Login Here to Vote</h2>
      <?php
      if (mysqli_num_rows($curElectData) > 0 OR $curElectData != False) {
        $row = mysqli_fetch_assoc($curElectData);
        if ($row["type"] == "yesno"){
          header('Location: yesNovoting.php');
        } elseif ($row["type"] == "multi") {
          header('Location: multiChoiceVoting.php');
        } else {echo "<h4>Please contact an administrator, as these elections can't yet be run, and this displays a flaw in the Matrix</h3>";}
      } else {
        echo "<h3>No election currently running</h3>";
      }?>
      <form method="post" action="voteLogin.php">
        Username:<br>
        <input type="text" name="uname"></input><br>
        Password:<br>
        <input type="password" name="pwd"><br>
        <input type="submit" name="login"><br>
      </form>
      <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
          $uName = $_POST["uname"];
          $pWd = $_POST["pwd"];
          $conn = mysqli_connect($servername, $username, $password, $dbname);
          $query = "SELECT `userID`, `pwd`, `userLevel`, `voted` FROM `users` WHERE `username` = \"$uName\";";
          $query2 = "SELECT `type` FROM `elects` WHERE `running` = 1;";
          $curElectData = mysqli_query($conn, $query2);
          $userData = mysqli_query($conn, $query);
          if (mysqli_num_rows($userData) > 0) {
            $row = mysqli_fetch_assoc($userData);
            if ($row["voted"] == 0) {
              if ($pWd == $row["pwd"]) {
                $_SESSION["voter"] = True;
                $_SESSION["userID"] = $row["userID"];
                if ($row["userLevel"] == 1) {
                  $_SESSION["admin"] = True;
                } else {
                  $_SESSION["admin"] = False;
                } if (mysqli_num_rows($curElectData) > 0) {
                  $row = mysqli_fetch_assoc($curElectData);
                  if ($row["type"] == "yesno") {
                    mysqli_close();
                    header('Location: yesNovoting.php');
                } elseif ($row["type"] == "multi") {
                  mysqli_close();
                  header('Location: multiChoiceVoting.php');
                } {echo "<h4>Please contact an administrator, as these elections can't yet be run</h3>";mysqli_close();}
              } else {echo "<h3><b>No Election Running Currently</b></h3>";mysqli_close();}
            } else {echo "<h3>Incorrect Password</h3>";mysqli_close();}
          } else {echo "<h3>You have already voted</h3>";mysqli_close();}
        } else {echo "<h3>Incorrect Username</h3>";mysqli_close();}
      }
      ?>
    </main>
    <footer>
      <h4>An Alicolliar Production</h4>
    </footer>
  </body>
</html>
