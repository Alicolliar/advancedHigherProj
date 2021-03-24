<?php
  session_start();
  if (isset($_SESSION["admin"]) and $_SESSION["admin"] == True) {
    header('Location: admin.php');
  }?>
<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" type=text/css href="css/styles.css ">
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
        <button class="dropbtn">Admin Login</button>
        <div class="dropdown-cont">
          <a href="index.php">Home</a><br>
          <a href="voteLogin.php">Vote Here</a><br>
          <a href="archive.php">View Past Elections</a><br>
          <a href="login.php">Admin Login</a><br>
          <?php if ($_SESSION["voter"] == 1) {echo "<a href=\"logout.php\">Logout</a>";}?>
        </div>
      </div>
    </nav>
    <main>
      <title> Voting Site - Login Page</title>
      <h2>Admin's Login Page</h2>
      <h3>Login For Admins</h3>
      <form method="POST" action="login.php">
        Username:<br>
        <input type="text" maxlength=25 name="uname"></input><br>
        Password:<br>
        <input type="password" maxlength=25 name="password"></input><br>
        <input type="hidden" value="admin">
        <input type="submit"></input><br>
      </form>
      <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
          $uName = $_POST["uname"];
          $pWd = $_POST["password"];
          $servername = "localhost";
          $username = "root";
          $password = "";
          $dbname = "testdb";
          $conn = mysqli_connect($servername, $username, $password, $dbname);
          $query = "SELECT `userID`, `pwd` FROM `users` WHERE `username` = \"".$uName."\" AND `userLevel` = 1;";
          $userData = mysqli_query($conn, $query);
          $row = mysqli_fetch_assoc($userData);
          if (mysqli_num_rows($userData) == 1) {
            if ($pWd == $row["pwd"]) {
              $_SESSION["voter"] = True;
              $_SESSION["admin"] = True;
              header('Location: admin.php');
            } else {echo "<h3>Incorrect Password</h3>";}
          } else {echo "<h3>Incorrect Username</h3>";}
        }
      ?>
    </main>
    <footer>
      <h4>An Alicolliar Production</h4><br><br>
      <p style="color: grey"><i>Version 1.0.0</i></p>
    </footer>
  </body>
</html>
