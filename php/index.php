<?php

require_once 'db_connection.php'; // Ensure this starts the session


if(isset($_POST['submit'])){

$username = $_POST['username'];
$password = $_POST['pass'];


//Retriving the user's details from the database
$sql_login = "SELECT * FROM user_input WHERE username =? AND pass=? ";
$stmt_login = mysqli_prepare($link,$sql_login);
mysqli_stmt_bind_param($stmt_login,"ss",$username,$password);
mysqli_stmt_execute($stmt_login);
$Finale_login = mysqli_stmt_get_result($stmt_login);

//checking if the user's details are correct
if(mysqli_num_rows($Finale_login)>0){
    

 //if the details are correct the user will be redirected to the profile page and 
 //the session will be started and the user's details will be stored in the session variable

$_SESSION['username'] = $username;
$_SESSION['loggedIn'] = true;
echo json_encode(['success'=>true]);
  header("Location: profile.php");


}else{
    $error_message = "Incorrect login details. Please try again."; // Set the error message

}

mysqli_stmt_close($stmt_login);      
}
mysqli_close($link);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link rel="stylesheet" href="css\login.css">
</head>
<body>
   <!-- login form -->
    <div class="login-container">
        <h2>Student Accomadation</h2>
        <!-- validation text for the user if the details are wrong-->
        <?php if (!empty($error_message)): ?>
            <div class="error-message" >
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>
        <form action="#" method="POST">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
           
                <label for="password">Password:</label>
                <input type="password" id="pass" name="pass" required>
               

                <button type="submit" name="submit" id="submit" class="button type1">
                <span class="btn-txt">Login</span>
                </button>
            
        </form>
</div>
</body>
</html>


