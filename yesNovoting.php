<?php
  session_start();
  if ((isset($_SESSION["voter"]) == False) ){
    header('Location: voteLogin.php');
  }
  //Initialising SQL Connection
  $servername = "localhost";
  $username = "root";
  $password = "";
  $dbname = "testdb";
  $conn = mysqli_connect($servername, $username, $password, $dbname);
  //Declaring and running the Query to Find Current Elections
  $query = "SELECT `electID`, `question`, `opt1`, `opt2` FROM `elects` WHERE `running` = '1';";
  $curRaw = mysqli_query($conn, $query);
  if (mysqli_num_rows($curRaw) > 0) {
    $row = mysqli_fetch_assoc($curRaw);
    $quest = $row["question"];
    $electID = $row["electID"];
    $opt1s = $row["opt1"];
    $noes = $row["opt2"];
  }
 ?>
<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" type=text/css href="css/styles.css">
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
          <li><a href="admin.php">Admin Login</a></li>
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
      <title>Voting Site - Voting</title>
      <h2>Vote Here!</h2>
      <form method="post" action="yesNovoting.php">
        <p><?php echo $quest;?></p>
        <input type="radio" name="vote" value="yes">Yes</input><br>
        <input type="radio" name="vote" value="no">No</input><br>
        <input type="submit" name="voting">
      </form>
      <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
          $vote = $_POST["vote"];
          $uID = $_SESSION["userID"];
          $queryMod = "UPDATE `users` SET `voted`= 1 WHERE `userID` = $uID;";
          $votedQuery = mysqli_query($conn, $queryMod);
          if ($vote = "yes") {
            $newYeses = intval($yeses) + 1;
            $queryAdd = "UPDATE `elects` SET `opt1Votes`='$newYeses' WHERE `electID`='$electID'";
            $addOp = mysqli_query($conn, $queryAdd);
          } else if ($vote = "no") {
            $newNoes = $noes + 1;
            $queryAdd = "UPDATE `elects` SET `opt2Votes`='$newNoes' WHERE `electID`='$electID'";
            $addOp = mysqli_query($conn, $queryAdd);
          } else {
            echo "<p>Not a Valid Vote</p>";
          }
          session_destroy();
          mysqli_close();
          header('Location: index.php');
        }
       ?>
    </main>
    <footer>
      <h4>An Alicolliar Production</h4>
    </footer>
  </body>
</html>
