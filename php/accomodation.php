<?php

require_once 'db_connection.php'; //adding this page for the database connection and the session that has already being started

 //stored the username in a session variable so that will use it to get 
//the user's details from the database and display them through out the enter pages of the system

$userdata = $_SESSION['username'];

//Retriving the user's details from the database
$sql_retrieve = "SELECT username, category, current_acc FROM user_input WHERE username = ?";
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

  //Retriving all the user's details from the database
  $sql_return = "SELECT * FROM user_input WHERE username = ?";
  $sql_return= mysqli_prepare($link, $sql_return);
  mysqli_stmt_bind_param($sql_return, "s", $userdata);
  mysqli_stmt_execute($sql_return);
  $result_return = mysqli_stmt_get_result($sql_return);

  $user = mysqli_fetch_assoc($result_return);
  
// Check if the form has been submitted
  if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])){
    
    //if so it then takes the user's input from the form using post method
    $accommodation = $_POST['accommodation'];
    $reason = $_POST['reason'];

    // Check if the user ID already exists in the accommodation table
    $check_query = "SELECT * FROM accommodation WHERE id = ?";
    $check_stmt = mysqli_prepare($link, $check_query);
    mysqli_stmt_bind_param($check_stmt, "i", $user['id']);
    mysqli_stmt_execute($check_stmt);
    $check_result = mysqli_stmt_get_result($check_stmt);

    if (mysqli_num_rows($check_result) > 0) {
        // User ID already exists, alert the user and stop form submission
        $_SESSION['alert'] = "Error: You have already submitted an accommodation request this semester.";
   
     } else {

    //if the ID does not exists insert the user's accommodation request into the accommodation table
    $sql_acc = "INSERT INTO accommodation (id, username, name, surname, gender, email, contact, current_acc, new_acc, reason) 
                   VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $sql_acc = mysqli_prepare($link, $sql_acc);
    mysqli_stmt_bind_param($sql_acc, "isssssssss", $user['id'], $user['username'], $user['name'], $user['surname'],$user['gender'],$user['email'],$user['contact'],$user['current_acc'],$accommodation,$reason);
  
    if(mysqli_stmt_execute($sql_acc)){
      $_SESSION['alert'] = "Form completion was successful, Your request has been sent wait for feedback.";
        } else {
            $_SESSION['alert'] = "ERROR: Could not submit the form.";
        }
  }
}
  if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['save'])){

    //if so it then takes the user's input from the form using post method
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $category = $_POST['category'];
    $complaint = $_POST['complaint'];
  

    // Insert the user's complaints request into the complaints table
    $sql_complaint = "INSERT INTO complaints (username, current_acc, fullname, email, phone, category, complaint) 
                   VALUES (?, ?, ?, ?, ?, ?, ?)";

    $sql_complaint = mysqli_prepare($link, $sql_complaint);
    mysqli_stmt_bind_param($sql_complaint, "sssssss", $user['username'], $user['current_acc'], $fullname, $email, $phone,$category,$complaint);
  
    if(mysqli_stmt_execute($sql_complaint)){
      $_SESSION['alert'] = "Form completion was successful, Your request has been sent wait for feedback.";
        } else {
            $_SESSION['alert'] = "ERROR: Could not submit the form.";
        }
      }
    
      mysqli_stmt_close($stmt_retrieve);
     
  
mysqli_close($link);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/accomodation.css">
  <script src="js/accomodation.js"></script>
  <title>Student Profile Dashboard</title>
  
</head>
<body>
  <!-- sidebar -->
  <div class="sidebar">
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
 <!-- Alert message for user form submission status-->
<?php if (isset($_SESSION['alert'])): ?>
    <div id="alertMessage"  style="padding: 10px; background-color:rgb(204, 219, 255); color: black; text-align: center; border: 1px solid blue; border-radius: 10px 10px 0px 200px; ">
        <?php echo $_SESSION['alert']; unset($_SESSION['alert']); ?>
    </div>
    <script>
        setTimeout(function () {
            document.getElementById("alertMessage").style.display = "none";
        }, 5000); // Hide message after 5 seconds
    </script>
<?php endif; ?>

<label for="" class="nav-item"><?php echo $user['category']; ?></label>
  </div>
  <div class="container1">
      <div class="card side-card card1">
        <h2>Switch Accomodation:</h2>
        <p>Fill this application</p>
        <button class="btn type1" onclick="openPopup1()">Apply</button>
      </div>

      <div class="card card2">
        <div class="p1">
        <h2>Your Accomodation</h2>
        
          Name: Lebo's villa <br />
          A beautiful building surrounded with fun activies<br />

          feature:<br />
          > Shared space <br />> Free wifi<br />
          >read more view this link:
        
        </div>
      </div>

      <div class="card side-card card3">
        <h2>Complains & Errors</h2>
        <p>
          Launch your:<br />
          complains , things that needs to fixed about the building here:
        </p>
        <button class="btn type1" onclick="openPopup2()">Complain</button>
      </div>

      <div class="card card4">
        <h2>View Accomodation</h2>
        <p>
                link 1: <u><a href="http://www.studentaccommodation.co.za/">Tlou Villa</a></u>
          <br />link 2: <u><a href="http://www.studentaccommodation.co.za/">Milky Way</a></u>
          <br />link 3: <u><a href="http://www.studentaccommodation.co.za/">Lamola Villa</a></u>
          <br />link 4: <u><a href="http://www.studentaccommodation.co.za/">Modern House</a></u><br />
        </p>
      </div>
    </div>
   
    <!-- Popup switching accomodation form -->
<div class="overlay" id="overlay"></div>
<div class="popup" id="switchAccommodationForm">
  <h3>Switch Accommodation</h3>
  <h5>NB: You can only switch accommodations once per semester.</h5>
  <form action="accomodation.php" method="POST">

    <div class="input-field">
    
    <?php foreach ($users as $user): ?>
      <input class="input2" name="current_acc" placeholder=" Current accomodation: <?php echo  $user['current_acc']; ?>" disabled>
      <?php endforeach; ?>
    </div>
    
    <div class="input-field"> 
      <select class="input2" name="accommodation"  required>
        <option value="" disabled selected></option>
        <option value="Tlou Villa" >Tlou Villa </option>
        <option value="Milky way" >Milky way </option>
        <option value="Lamola Villa"> Lamola Villa </option>
        <option value="Mordern house"> Mordern house </option>
      </select>
      <label class="label2" for="accommodation">New Accommodation</label>
    </div>
    
    <div class="input-field">
    
      <textarea class="input2" name="reason" rows="5" placeholder="Provide a reason for switching accommodations" required></textarea>
      <label class="label2" for="reason">Reason for Switching:</label>
    </div>
    
    <div class="btn-container">
       
        <button  class="btn1" type="submit" name="submit">Save Changes</button>
      </div>
      <button class="btn2" ><a href="accomodation.php">X</a></button>
  
  </form>
</div>

<!-- Popup complaint form -->
<div class="popup" id="complainForm">
  <h3>Submit a Complaint</h3>
  <form action="accomodation.php" method="POST">
    <div class="input-field">
    <input class="input2" type="text" name="fullname" placeholder="Enter your full name:" required>
     
      <label class="label2">Full name:</label>
    </div>

    <div class="input-field">
    <input class="input2" type="text" name="email" placeholder="Enter your Email Address:" required>
     
      <label  class="label2">Email:</label>
    </div>
    <div class="input-field">
    <input class="input2 " type="text" name="phone" placeholder="Enter your Phone Number:" required>
     
      <label class="label2">Contact:</label>
    </div>

    <div class="input-field">
     
     <select class="input2" name="category"  required>
     <option value="" disabled selected></option>
        <option value="billing">Billing Issue</option>
        <option value="service">Service Problem</option>
        <option value="product">Product Issue</option>
        <option value="other">Other</option>
     </select>
     <label class="label2" >Complaint Category</label>
   </div>
   
    <div class="input-field">
    
    <textarea class="input2" name="complaint" rows="5"  required></textarea>
    <label class="label2" for="reason">Complaint details:</label>
  </div>
  
    <div class="btn-container">
       
        <button  class="btn1" type="submit" name="save">Save Changes</button>
      </div>
      <button class="btn2" ><a href="accomodation.php">X</a></button>
  
  </form>
</div>
</body>
</html>