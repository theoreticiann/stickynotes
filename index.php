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
	$db = mysqli_connect("localhost", "root", "", "note");
	// insert a quote if submit button is clicked
	if (isset($_POST['submit'])) {
		if (empty($_POST['note'])) {
			$errors = "Please add any note";
		}else{
			$note = $_POST['note'];
			$username = $_SESSION['username'];
			$sql = "INSERT INTO sticky_notes (content,username,completed) VALUES ('$note','$username','false')";
			mysqli_query($db, $sql);
			header('location: index.php');
		}
	}
	
	if (isset($_GET['done'])) {
			// if (isset($_GET['done_task'])) {
			// $id = $_GET['done_task'];
			// }
			if (isset($_GET['complete'])) {
				
			$complete = $_GET['complete'];
			}
			
			$sql = "UPDATE sticky_notes SET completed = 'true' WHERE id=".$complete;
			mysqli_query($db, $sql);
			header('location: index.php');
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
	$cityId = "5393068";
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
	</div>

		<div class="report-container">
        <h2><?php echo $data->name; ?> Weather Status</h2>
        <div class="time">
            <div><?php echo date("l g:i a", $currentTime); ?></div>
            <div><?php echo date("jS F, Y",$currentTime); ?></div>
            <div><?php echo ucwords($data->weather[0]->description); ?></div>
        </div>
        <div class="weather-forecast">
            <img
                src="http://openweathermap.org/img/w/<?php echo $data->weather[0]->icon; ?>.png"
                class="weather-icon" /> <?php echo $data->main->temp_max; ?>°C<span
                class="min-temperature"><?php echo $data->main->temp_min; ?>°C</span>
        </div>
	</div>
	
	<div>
  	<!-- notification message -->
  	<?php if (isset($_SESSION['success'])) : ?>
      <center>
      	<p>
          <?php 
          	echo $_SESSION['success']; 
          	unset($_SESSION['success']);
          ?>
      	</p>
      </center>
  	<?php endif ?>

  	<br />
  	<br />
	<?php  if (isset($_SESSION['username'])) : ?>
    	<p align="center">Hey <strong><?php echo $_SESSION['username']; ?>,
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
	?></strong></p>
	<p align="center"> <a href="index.php?logout='1'" style="color: red;">logout</a> </p>

    <?php endif ?>
	<br />
	
	<form method="post" action="index.php" class="input_form">
		<input type="text" name="note" class="task_input">
		<button type="submit" name="submit" id="add_btn" class="add_btn">Add Note</button>
		<?php if (isset($errors)) { ?>
		<p><?php echo $errors; ?></p>
		<?php } ?>
	</form>
	
	<table>
	<form method="get" action="index.php" class="input_form">
	<button type="submit" name="done" id="done" class="done" style="display:none">Done</button>
	<thead>
		<tr>
			<th>Completed</th>
			<th>Note</th>
			<th style="width: 60px;">Delete</th>
		</tr>
	</thead>

	<tbody>
		<?php 
		// select all tasks if page is visited or refreshed
		$username = $_SESSION['username'];
		$notes = mysqli_query($db, "SELECT * FROM sticky_notes where username = '$username' and completed='false'");
		 while ($row = mysqli_fetch_array($notes)) { ?>
			<tr>
				<td class="task"> <input type="checkbox" name="complete" id="<?php echo $row['id'] ?>" value="<?php echo $row['id'] ?>" onclick="show_button()"></td> 
				<td class="task"> <?php echo $row['content']; ?> </td>
				<td class="delete"> 
					<a href="index.php?del_task=<?php echo $row['id'] ?>">x</a> 
				</td>
				
			</tr>
		<?php  } ?>	
	</tbody>
</table>
</form>
<h4>Completed</h4>
	<table>
	<thead>
		<tr>
			<th>Note</th>
			<th style="width: 60px;">Delete</th>
		</tr>
	</thead>

	<tbody>
		<?php 
		// select all tasks if page is visited or refreshed
		$username = $_SESSION['username'];
	
		$notes = mysqli_query($db, "SELECT * FROM sticky_notes where username = '$username' and completed='true'");
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