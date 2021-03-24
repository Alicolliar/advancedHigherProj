<?php session_start();?>
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
          <li><a href="login.php">Admin Login</a></li>
          <?php if ($_SESSION["voter"] == 1) {echo "<li><a href=\"logout.php\">Logout</a></li>";}?>
        </ul>
      </div>
      <div class="dropdown">
        <button class="dropbtn">Home</button>
        <div class="dropdown-cont">
          <a href="index.php">Home</a><br>
          <a href="voteLogin.php">Vote Here</a><br>
          <a href="archive.php">View Past Elections</a><br>
          <a href="login.php">Admin Login</a>
          <?php if ($_SESSION["voter"] == 1) {echo "<a href=\"logout.php\">Logout</a>";}?>
        </div>
      </div>
    </nav>
    <main>
      <title>Voting Site - Home</title>
      <h2>Welcome to the Voting Site Thing</h2>
      <p>This is a thing that lets you vote. It's built with PHP for the backend and MySQL
        for databasing.</p>
      <h3>Current Election:</h3>
      <?php $servername = "localhost";
         $username = "root";
         $password = "";
         $dbname = "testdb";
         $conn = mysqli_connect($servername, $username, $password, $dbname);
         $query = "SELECT `question`, `dateTimeEnding` FROM `elects` WHERE `running` = 1;";
         $result = mysqli_query($conn, $query);
         if (mysqli_num_rows($result) == 1) {
           $row = mysqli_fetch_assoc($result);
           echo "<div id='adminBoxes'>
                  <h4>Question: ".$row["question"]."<br><br>
                  <h5>Time ending: ".$row["dateTimeEnding"]."</h4><br>
                  <a href='voteLogin.php'><button type='button'>Vote!</button></a>
                </div>";
         } else if (mysqli_num_rows($result) > 1) {
           echo "<p>Something has gone horrendously wrong. Please contact an administrator.</p>";
         } else {
           echo "<p>No election running currently</p>";
         }
         mysqli_close($conn);?>
    </main>
    <footer>
      <h4>An Alicolliar Production</h4>
    </footer>
  </body>
</html>
