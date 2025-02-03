<?php

require_once 'db_connection.php'; //adding this page for the database connection and the session that has already being started

 //stored the username in a session variable so that will use it to get 
//the user's details from the database and display them through out the enter pages of the system

$userdata = $_SESSION['username'];

//Retriving the user's details from the database
$sql_retrieve = "SELECT id, username,image, name, surname, gender, email, contact, birth, address , category FROM user_input WHERE username = ?";
$stmt_retrieve = mysqli_prepare($link, $sql_retrieve);
mysqli_stmt_bind_param($stmt_retrieve, "s", $userdata);
mysqli_stmt_execute($stmt_retrieve);
$result_retrieve = mysqli_stmt_get_result($stmt_retrieve);

$users = [];

//Storing the user's details in an array to displayy them on the html sector
if (mysqli_num_rows($result_retrieve) > 0) {
    while ($row = mysqli_fetch_assoc($result_retrieve)) {
        $users[] = $row;
    }
}

mysqli_stmt_close($stmt_retrieve);

if(isset($_POST['submit'])){

  // getting the user's input from the form using post method
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $email = $_POST['email'];
    $contact = $_POST['contact'];
    $address = $_POST['address'];
    $filename = $_FILES['image']['name'];
    $tmpname = $_FILES['image']['tmp_name'];
    $folder = $_SERVER['DOCUMENT_ROOT'] . '/Lebogang/Map_System/php/css/resource/images/' . $filename;

   //uploading the image to the server 
    if(move_uploaded_file($tmpname, $folder)){
      echo "Image uploaded successfully";}else{
        echo "Failed to upload image";
      }

      $file_path = "php/css/resource/images/" . $row['image'];
if (!file_exists($file_path)) {
    echo "Image not found: " . $file_path;
} else {
    echo '<img src="' . $file_path . '" alt="Profile Picture">';
}

    //updating the user's details in the database
    $sql_update = "UPDATE user_input SET image=?, name = ?, surname = ?, email = ?, contact = ?, address = ? WHERE username = ?";
    $stmt_update = mysqli_prepare($link, $sql_update);
    mysqli_stmt_bind_param($stmt_update, "sssssss",$filename, $name, $surname, $email, $contact, $address, $userdata);
    mysqli_stmt_execute($stmt_update);


    if (mysqli_stmt_execute($stmt_update)) {
      header("Location: profile.php"); //when the execusion is successful the page will be redirected to home page with new updated user's details
      exit;
  } else {
      echo "ERROR: Could not update the profile. " . mysqli_error($link);
  }
  
    mysqli_stmt_close($stmt_update); //closing the sql statement
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/profile.css"> 
  <script src="js/profile.js"></script>
  <title>Student Profile Dashboard</title>
  
</head>
<body>
  <!-- sidebar -->
  <div class="sidebar">
    <!--using arrays formed in the php section to get user's details-->
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
<!--Profile Card = holds the body content of everything-->
  <div class="profile-card">
    <button id="editbutton" class="edit-button" onclick="openPopup()">✏️ </button> <!--this button is used to open up the popup form using a function
    called onclick described within the profile.js-->

    <div class="profile-details">
      <?php
      //Retriving the user's image from the database
      $res = mysqli_query($link, "SELECT image FROM user_input WHERE username = '$userdata'");
      while ($row = mysqli_fetch_array($res)) {
       ?>

     <img src="/Lebogang/Map_System/php/css/resource/images/<?php echo $row['image']; ?>" alt="Profile Picture">
      <?php } ?>
      <div class="container">
        <div class="column">
        <?php foreach ($users as $user): ?>
          <div class="input">
            <label for="input" class="text">ID:</label>
            <input type="text" value="<?php echo $user['id']; ?>" name="input" class="input" disabled>
          </div>
          <div class="input">
            <label for="input" class="text">Name:</label>
            <input type="text" value="<?php echo $user['name']; ?>" name="input" class="input" disabled>
          </div>
          <div class="input">
            <label for="input" class="text">Surname:</label>
            <input type="text" value="<?php echo $user['surname']; ?>" name="input" class="input" disabled>
          </div>
          <div class="input">
            <label for="input" class="text">Gender:</label>
            <input type="text" value="<?php echo $user['gender']; ?>" name="input" class="input" disabled>
          </div>
        </div>
        <div class="column">
          <div class="input">
            <label for="input" class="text">Email:</label>
            <input type="text" value="<?php echo $user['email']; ?>" name="input" class="input" disabled>
          </div>
          <div class="input">
            <label for="input" class="text">Phone NO:</label>
            <input type="text" value="<?php echo $user['contact']; ?>" name="input" class="input" disabled>
          </div>
          <div class="input">
            <label for="input" class="text">Birth:</label>
            <input type="text" value="<?php echo $user['birth']; ?>" name="input" class="input" disabled>
          </div>
          <div class="input">
            <label for="input" class="text">Address:</label>
            <input type="text" value="<?php echo $user['address']; ?>" name="input" class="input" disabled>
          </div>
        <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>

  <!-- Popup  edit Form -->
  <div class="overlay" id="overlay"></div><!--this div is used to create a dark background when the popup form is opened-->
  <div class="popup" id="popupForm">
    <h2>Edit Profile</h2>
    <form action="profile.php" method="POST" enctype="multipart/form-data">
      <?php foreach ($users as $user): ?>
        <div class="input-field">
        <input class="input2" type="text" name="name"  view=" <?php echo $user['name']; ?>" placeholder=" <?php echo $user['name']; ?>" required>
        <label class="label2">Name:</label>
        </div>
        <div class="input-field">
        <input class="input2" type="text" name="surname" view="<?php echo $user['surname']; ?>" placeholder="<?php echo $user['surname']; ?>"  required>
        <label class="label2">Surname:</label>  
      </div>
        <div class="input-field">
        <input class="input2" type="text" name="email"  view="<?php echo $user['email']; ?>" placeholder="<?php echo $user['email']; ?>"  required>
        <label class="label2">Email:</label>  
      </div>
        <div class="input-field">
        <input class="input2" type="text" name="contact" view="<?php echo $user['contact']; ?>" placeholder="<?php echo $user['contact']; ?>"  required>
        <label class="label2">Phone No</label>  
      </div>
        <div class="input-field">
        <input class="input2" type="text" name="address" view="<?php echo $user['address']; ?>" placeholder="<?php echo $user['address']; ?>"  required>
        <label class="label2">Address:</label>
      </div>
      <div class="input-field">
        <input type="file" name="image">>
      </div>
        <?php endforeach; ?>
      <div class="btn-container">
       
        <button  class="btn" type="submit" name="submit">Save Changes</button>
      </div>
      <button class="btn2" ><a href="profile.php">X</a></button>
    
    </form>
  </div>
</body>
</html>
