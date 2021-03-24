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
        <button class="dropbtn">Archive</button>
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
      <title>Voting Site - Archives</title>
      <h2>Archives</h2>
      <h3>View all past elections</h3>
      <?php
        $query1 = "SELECT * FROM `elects` WHERE running = 0;";
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "testdb";
        $conn = mysqli_connect($servername, $username, $password, $dbname);
        $results = mysqli_query($conn, $query1);
        if (mysqli_num_rows($results) > 0){
          foreach ($results as $row) {
            if ($row["type"] == "yesno"){
              echo '<div id="archiveBoxes">
                      <h3>Election Question: '.$row["question"].'</h3><br><br>
                      <h4>Yes votes: '.$row["opt1Votes"].'</h4><br>
                      <h4>No votes: '.$row["opt2Votes"].'</h4><br>
                    </div>';
            }elseif ($row["type"] == "multi") {
              echo '<div id="archiveBoxes">
                      <h3>Election Question: '.$row["question"].'</h3><br><br>
                      <h4>'.$row["opt1"].': '.$row["opt1Votes"].'<br><br>
                      '.$row["opt2"].': '.$row["opt2Votes"].'<br><br>';
                      if ($row["opt3"] != "") {
                        echo $row["opt3"].': '.$row["opt3Votes"].'<br><br>';
                        if ($row["opt4"] != "") {
                          echo $row["opt4"].': '.$row["opt4Votes"].'<br><br>';
                        }
                      }
                      echo '</h4>
                    </div>';
            }}
        } else {
          echo "<h4>No past elections</h4>";
        }
      ?>
    </main>
    <footer>
      <h4>An Alicolliar Production</h4><br><br>
      <p style="color: grey"><i>Version 1.0.0</i></p>
    </footer>
  </body>
</html>
