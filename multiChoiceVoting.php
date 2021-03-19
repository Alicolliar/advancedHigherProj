<?php
  session_start();
  if ((isset($_SESSION["voter"]) == False) ){
    header('Location: voteLogin.php');
  }
  $servername = "localhost";
  $username = "root";
  $password = "";
  $dbname = "testdb";
  $conn = mysqli_connect($servername, $username, $password, $dbname);
  $query = "SELECT `electID`, `question`, `opt1`, `opt2`, `opt3`, `opt4`, `opt1Votes`, `opt2Votes`, `opt3Votes`, `opt4Votes` FROM `elects` WHERE `running` = '1';";
  $curRaw = mysqli_query($conn, $query);
  if (mysqli_num_rows($curRaw) > 0) {
    $row = mysqli_fetch_assoc($curRaw);
    $quest = $row["question"];
    $electID = $row["electID"];
    $opt1Q = $row["opt1"];
    $opt2Q = $row["opt2"];
    if ($row["opt3"] != False) {
      $opt3Q = $row["opt3"];
    } if ($row["opt4"] != False) {$opt4Q = $row["opt4"];}
  } else {
    header('Location: voteLogin.php');
  }
?>
<!DOCTYPE html>
<html>
  <head>
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
      <form method="POST" action="multiChoiceVoting.php">
        <h3><?php echo $quest;?></h3>
        <input type="radio" name="vote" value="opt1" checked><?php echo $opt1Q;?></input><br>
        <input type="radio" name="vote" value="opt2"><?php echo $opt2Q;?></input><br>
        <?php if ($row["opt3"] != False) {
          echo "<input type=\"radio\" name=\"vote\" value=\"opt3\">$opt3Q</input><br>";}
        if ($row["opt4"] != False) {
          echo "<input type=\"radio\" name=\"vote\" value=\"opt4\">$opt4Q</input><br>";
        }
        ?>
        <input type="submit">
      </form>
    </main>
      <?php
      if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $userID = $_SESSION["userID"];
        $optionPicked = $_POST["vote"];
        $queryUse = $optionPicked."Votes";
        $curSum = $row["$queryUse"] + 1;
        $electQuery = "UPDATE `elects` SET `$queryUse` = $curSum WHERE `electID` = $electID;";
        echo $electQuery;
        $userQuery = "UPDATE `users` SET `running` = 1 WHERE `userID` = $userID;";
        $electRun = mysqli_query($conn, $electQuery);
        $userRun = mysqli_query($conn, $userQuery);
      }
      ?>
    <footer>
      <h4>An Alicolliar Production</h4>
    </footer>
  </body>
</html>
