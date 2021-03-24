<?php
  session_start();
  if (isset($_SESSION["admin"]) == False) {
    header('Location: login.php');
  } else {
    if ($_SESSION["admin"] == False) {
      header('Location: login.php');
    }
  }
?>
<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" type="text/css" href="css/styles.css">
    <script>
      var intervalId = window.setInterval(function ShowIfTrue() {
        var curElectType = document.getElementById("electType").value;
        if (curElectType == "Multiple Choice") {
          document.getElementById("optBoxes").style.display = "block";
        } else {
          document.getElementById("optBoxes").style.display = "none";
        }}, 100);
    </script>
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
        <button class="dropbtn">Admin</button>
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
      <title>Voting Site - Admin Page</title>
      <h2>Admin Page</h2>
      <?php
      print_r($_SESSION);
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
          date_default_timezone_set("Europe/London");
          $servername = "localhost";
          $username = "root";
          $password = "";
          $dbname = "testdb";
          $conn = mysqli_connect($servername, $username, $password, $dbname);
          if ($_POST["formUse"] == "electMake") {
            $electQuest = $_POST["question"];
            $electType = $_POST["electType"];
            $dateStarting = date("Y-m-d H:i");
            $dateStarting[10] = "T";
            echo $dateStarting."<br>";
            $dateEnding = $_POST["endDate"];
            $checkQuery = "SELECT `question` FROM `elects` WHERE `running` = 1;";
            $queryCheck = mysqli_query($conn, $checkQuery);
            if (mysqli_num_rows($queryCheck) == 0) {
              if ($electType == "Yes/No") {
                $electType = "yesno";
                $query = "INSERT INTO `elects` (`question`, `type`, `dateTimeBeginning`, `dateTimeEnding`, `opt1`, `opt2`, `running`) VALUES (\"$electQuest\", 'yesno', \"$dateStarting\", \"$dateEnding\", 'yes', 'no', 1);";
                $results = mysqli_query($conn, $query);
                echo "<h4>Election with question \"$electQuest\" started.</h4>";
              } elseif ($electType == "Multiple Choice") {
                $electType = "multi";
                $options = array();
                if ($_POST["opt1"] != "") {
                  $options[] = $_POST["opt1"];
                } if ($_POST["opt2"] != "") {
                  $options[] = $_POST["opt2"];
                } if ($_POST["opt3"] != "") {
                 $options[] = $_POST["opt3"];
                } if ($_POST["opt4"] != "") {
                $options[] = $_POST["opt4"];
                }
                switch (sizeof($options)) {
                  case 1:
                    echo "<h3 style='color:red'>Must have 2 or more options.</h3>";
                    break;
                  case 2:
                    $multiAdd = "INSERT INTO `elects` (`question`, `type`, `dateTimeBeginning`, `dateTimeEnding`, `opt1`, `opt2`, `running`) VALUES (\"$electQuest\", 'multi', \"$dateStarting\", \"$dateEnding\", \"$options[0]\", \"$options[1]\", 1);";
                    break;
                  case 3:
                    $multiAdd = "INSERT INTO `elects` (`question`, `type`, `dateTimeBeginning`, `dateTimeEnding`, `opt1`, `opt2`, `opt3`, `running`) VALUES (\"$electQuest\", 'multi', \"$dateStarting\", \"$dateEnding\", \"$options[0]\", \"$options[1]\", \"$options[2]\", 1);";
                    break;
                  case 4:
                    $multiAdd = "INSERT INTO `elects` (`question`, `type`, `dateTimeBeginning`, `dateTimeEnding`, `opt1`, `opt2`, `opt3`, `opt4`, `running`) VALUES (\"$electQuest\", 'multi', \"$dateStarting\", \"$dateEnding\", \"$options[0]\", \"$options[1]\", \"$options[2]\", \"$options[3]\", 1);";
                    break;}
                $alpha = mysqli_query($conn, $multiAdd);
                echo $multiAdd;
                echo "a";
                //echo "<h2>Election Started!</h2>";
              } else {
                echo "<h3>Invalid Election Type</h3>";}
            } else {
              $row = mysqli_fetch_assoc($queryCheck); $runningElectQuest = $row["question"];
              echo "<h3>There is already an election running, with the question \"$runningElectQuest\".</h3>";}
          } elseif ($_POST["formUse"] == "electCan") {
            $queryCheck = "SELECT `electID` FROM `elects` WHERE `running` = 1;";
            $checkQuery = mysqli_query($conn, $queryCheck);
            $row = mysqli_fetch_assoc($checkQuery);
            if (mysqli_num_rows($checkQuery) > 0) {
              $electID = $row["electID"];
              $queryAct = "UPDATE `elects` SET `running` = 0 WHERE `electID` = $electID;";
              $run = mysqli_query($conn, $queryAct);
              echo "Election cancelled";
            } else {
              echo "No election to cancel";}
          } elseif ($_POST["formUse"] == "userAdd") {
            $username = $_POST["user"];
            $password = $_POST["pwd"];
            $uLev = $_POST["level"];
            if ($uLev == "Admin") {
              $queryLev = 1;
            } else {
              $queryLev = 0;
            }
            $query = "SELECT `userID` FROM `users` WHERE `username` = \"$username\"";
            $addUserQuery = "INSERT INTO `users` (`userName`, `pwd`, `userLevel`, `voted`) VALUES (\"$username\", \"$password\", $queryLev, 0);";
            $checkQuer = mysqli_query($conn, $query);
            if (mysqli_num_rows($checkQuer) > 0) {echo "<h2 style='color:red;'>Username already taken</h2>";}
            else {
              $beta = mysqli_query($conn, $addUserQuery);
              echo $username." added.";
            }} elseif ($_POST["formUse"] == "userCan") {
              $userToCan = $_POST["userToGo"];
              $checkQuery = "SELECT `userID` FROM `users` WHERE `username` = \"$userToCan\"";
              $checking = mysqli_query($conn, $checkQuery);
              if (mysqli_num_rows($checking) == 1) {
                $checking1 = mysqli_fetch_assoc($checking);
                $remQuer = "DELETE FROM `users` WHERE `userID` == ".$checking1['userID'];
                $redRum = mysqli_query($conn, $remQuer);
                echo "User Removed";
              }
            } else {
            echo "<h3>Invalid Form</h3>";
          }
        }
      ?>
      <div id=adminBoxes>
        <h3>Election Controls</h3>
        <h4>Start New Election</h4>
        <form method="POST" action="admin.php">
          Question of Election:<br>
          <input type="text" name="question" maxlength="50"></input><br>
          Election Type:<br>
          <select name="electType" id="electType"><br>
            <option value="Yes/No" selected>Yes/No</option>
            <option value="Multiple Choice">Multiple Choice</option>
          </select><br>
          <input type="hidden" name="formUse" value="electMake">
          <div id=optBoxes>
            Option 1:<br>
            <input type="text" name="opt1"><br>
            Option 2:<br>
            <input type="text" name="opt2"><br>
            Option 3:<br>
            <input type="text" name="opt3"><br>
            Option 4:<br>
            <input type="text" name="opt4"><br>
          </div>
          End Date:<br>
          <input type="datetime-local" name="endDate"></input><br>
          <input type="submit"></input>
        </form>
        <form method="POST" id="cancel">
          <h4>Cancel Ongoing Election</h4>
          <button type="submit" form="cancel" value="electCancel" name="electCancel">Cancel Current Election</button>
          <input type="hidden" name="formUse" value="electCan">
        </form>
      </div>
      <div id=adminBoxes>
        <h3>User Controls</h3><br>
        <h2>New User</h2>
        <form method="POST">
          New User's Username:<br>
          <input type="text" name="user"></input><br>
          New User's Password:<br>
          <input type="password" name="pwd"></input><br>
          New User's Level:<br>
          <input type="list" list="num" name="level">
          <datalist id="num">
            <option value="Admin"></option>
            <option value="Voter"></option>
          </datalist><br>
          <input type="hidden" name="formUse" value="userAdd">
          <input type="submit" name="userAdd"></input>
        </form>
        <br>
        <h2>Delete User</h2>
        <form method="POST" id="cancel" action="admin.php">
          Username of user to remove:<br>
          <input type="text" name="userToGo"><br>
          <input type="hidden" name="formUse" value="userCan">
          <input type="submit">
      </div>
    </main>
    <footer>
      <h4>An Alicolliar Production</h4>
    </footer>
  </body>
</html>
