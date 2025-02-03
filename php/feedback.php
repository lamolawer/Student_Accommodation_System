<?php

require_once 'db_connection.php'; //adding this page for the database connection and the session that has already being started

 //stored the username in a session variable so that will use it to get 
//the user's details from the database and display them through out the enter pages of the system

$userdata = $_SESSION['username'];


//Retriving the user's details from the database
$sql_retrieve = "SELECT  username, category FROM user_input WHERE username = ?";
$stmt_retrieve = mysqli_prepare($link, $sql_retrieve);
mysqli_stmt_bind_param($stmt_retrieve, "s", $userdata);
mysqli_stmt_execute($stmt_retrieve);
$result_retrieve = mysqli_stmt_get_result($stmt_retrieve);


//Storing the user's details in an array to displayy them on the html sector
$users = [];

if (mysqli_num_rows($result_retrieve) > 0) {
    while ($row = mysqli_fetch_assoc($result_retrieve)) {
        $users[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/feedback.css">
  <title>Student Profile Dashboard</title>
  
</head>
<body>
    <!-- sidebar -->
  <div class="sidebar">
    <!--array to get user information from the database-->
  <?php foreach ($users as $user): ?>
    <h2><?php echo $user['username']; ?></h2>
    <?php endforeach; ?>
    <ul>
        <li><a href="profile.php"><i>Profile</i></a></li>
        <li><a href="accomodation.php"><i>Accommodations</i></a></li>
        <li><a href="campus.php"><i>Map</i></a></li>
        <li><a href="feedback.php"><i>Feedback</i></a></li>
        <li><a href="index.php"><i>Log-Out</i></a></li>
    </ul>
  </div>
  <!-- navigation bar -->
  <div class="nav-bar">
  <label for="" class="nav-item"><?php echo $user['category']; ?></label>
  </div>
  
  <!-- feedback body content-->
  <div class="content">
  <div class="profile-card">
    <h2>Feedback</h2>
    <div class="feedback-container">
      <p>No feedback yet, please check your student email for updates</br> / </br>send an email to lamolalebogang93@gmail.com for a follow up.</p>
    </div>
  </div>
</div>
</body>
</html>
