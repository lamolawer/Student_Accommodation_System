<?php

require_once 'db_connection.php'; //adding this page for the database connection and the session that has already being started

 //stored the username in a session variable so that will use it to get 
//the user's details from the database and display them through out the enter pages of the system

$userdata = $_SESSION['username'];

//Retriving the user's details from the database
$sql_retrieve = "SELECT  username, category  FROM user_input WHERE username = ?";
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
mysqli_close($link); //closing the database connection
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/campus.css">
    
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
        <li><a href="accomodation.php"><i>Accomodations</i></a></li>
        <li><a href="campus.php"><i>Map</i></a></li>
        <li><a href="feedback.php"><i>Feedback</i></a></li>
        <li><a href="index.php"><i>Log-Out</i></a></li>
    </ul>
  </div>
  <!-- navigation bar -->
  <div class="nav-bar">
  <label for="" class="nav-item"><?php echo $user['category']; ?></label>
  </div>              
</div>

<!-- campus map -->
    <div id="campus" class="map"></div>
    <!-- used google maps free api and used the coordinates that i want to be shown onn the map-->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA8NTAmKHwvhGnA-50CPkWs1lqJP3Yayko&callback=initMap" async defer></script>
<script>
        function initMap(){ //function to initialize the map
            var mapOptions = {
                center: {lat: -23.8699, lng: 29.4504},
                zoom: 15
            }

            var map = new google.maps.Map(document.getElementById('campus'), mapOptions);//creating the map
            var compusCL = {lat: -23.8699, lng: 29.4504};
            var compusCM = new google.maps.Marker({
                position: compusCL, map: map, title: 'Campus Current Building'//creating a marker for the campus building

            })

            var nerbyL = [
                {lat: -23.897339, lng: 29.448040},
                {lat: -23.903676, lng: 29.448767},
                {lat: -23.894714, lng: 29.460017}
            ];//creating markers for nearby buildings

            nerbyL.forEach(function(location){

                new google.maps.Marker({
                    position: location,
                    map: map,
                    title: 'Nearby Building'
                })
            })
        }
    </script>
     </div>
</body>
</html>
