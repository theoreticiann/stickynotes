<?php 
    // initialize errors variable
	$errors = "";
	session_start(); 
  if (!isset($_SESSION['username'])) {
  	$_SESSION['msg'] = "You must log in first";
  	header('location: login.php');
  }
  if (isset($_GET['logout'])) {
  	session_destroy();
  	unset($_SESSION['username']);
  	header("location: login.php");
  }
	// connect to database
	$db = mysqli_connect("107.170.203.30", "qafmzsuxhq", "SamaJZe6Nf", "qafmzsuxhq");
	// insert a quote if submit button is clicked
	if (isset($_POST['submit'])) {
		if (empty($_POST['note'])) {
			$errors = "Please add any note";
		} else{
			$note = $_POST['note'];
			$username = $_SESSION['username'];
			$sql = "INSERT INTO sticky_notes (content,username) VALUES ('$note','$username')";
			mysqli_query($db, $sql);
			header('location: index.php');
		}
	}
	
		if (isset($_GET['del_task'])) {
		$id = $_GET['del_task'];

		mysqli_query($db, "DELETE FROM sticky_notes WHERE id=".$id);
		header('location: index.php');
	}

	// select all tasks if page is visited or refreshed
	$tasks = mysqli_query($db, "SELECT * FROM sticky_notes");

?>


<?php
	$apiKey = "ade039452c8d195dee785fc32ad2cd54";
	$cityId = "1270260";
	$googleApiUrl = "http://api.openweathermap.org/data/2.5/weather?id=" . $cityId . "&lang=en&units=metric&APPID=" . $apiKey;

	$ch = curl_init();

	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_URL, $googleApiUrl);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_VERBOSE, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	$response = curl_exec($ch);

	curl_close($ch);
	$data = json_decode($response);
	$currentTime = time();
?>



<!DOCTYPE html>
<html>
<head>
	<title>Sticky Notes</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	
	<div class="header">
	<h2 style="font-style: 'Hervetica';">Sticky Notes</h2>
	<?php if (isset($_SESSION['success'])) : ?>
      <div class="error success" >
      	<h3>
          <?php 
          	echo $_SESSION['success']; 
          	unset($_SESSION['success']);
          ?>
      	</h3>
      </div>
  	<?php endif ?>
		<p align="right"> <a href="index.php?logout='1'" style="color: white;">logout</a> </p>
		<div aligh="left"><?php echo date("jS F, Y",$currentTime); ?></div>  
	</div>
	
	<br />
	<?php  if (isset($_SESSION['username'])) : ?>
    	<H2 align="center">Hey <strong><?php echo $_SESSION['username']; ?>,
    	<?php
  	$hour = date('H', time());

	if( $hour > 6 && $hour <= 11) {
  	echo "Good Morning!";
	}
	else if($hour > 11 && $hour <= 16) {
  	echo "Good Afternoon!";
	}
	else if($hour > 16 && $hour <= 23) {
  	echo "Good Evening!";
	}
	else {
  	echo "Not slept yet!  Are you programming?";
	}
	?></strong></H2>

    <?php endif ?>
	<br />

	<div align="center">
		<div class="time">
			<div><?php echo date("l g:i a", $currentTime); ?></div>
          </div>
        <div class="weather-forecast">
            <center><img
                src="http://openweathermap.org/img/w/<?php echo $data->weather[0]->icon; ?>.png"
                class="weather-icon" /><center>
		    <br />
		    <div><?php echo ucwords($data->weather[0]->description); ?></div>
		    <?php echo $data->main->temp_max; ?>°C <span
                class="min-temperature"><?php echo $data->main->temp_min; ?>°C</span>
        </div>
	</div>
  	
	<br />
	<form method="post" action="index.php" align="center" class="form">
		<input type="text" name="note" class="task_input">
		<button type="submit" name="submit" id="add_btn" class="add_btn">Add Note</button>
		<?php if (isset($errors)) { ?>
		<p><?php echo $errors; ?></p>
		<?php } ?>
	</form>
	
	<table align="center">
	<thead>
		<tr>
			<th>Id</th>
			<th>Note</th>
			<th style="width: 60px;">Delete</th>
		</tr>
	</thead>

	<tbody>
		<?php 
		// select all tasks if page is visited or refreshed
		$username = $_SESSION['username'];
		$notes = mysqli_query($db, "SELECT * FROM sticky_notes where username = '$username'");

		 while ($row = mysqli_fetch_array($notes)) { ?>
			<tr>
				<td class="task"> <?php echo $row['content']; ?> </td>
				<td class="delete"> 
					<a href="index.php?del_task=<?php echo $row['id'] ?>">x</a> 
				</td>
			</tr>
		<?php  } ?>	
	</tbody>
</table>
	
</body>
</html>