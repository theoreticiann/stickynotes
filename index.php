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
		}else{
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
    	<H2 align="center">Welcome <strong><?php echo $_SESSION['username']; ?></strong></H2>
    <?php endif ?>
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
				<td class="task"> <?php echo $row['id'] ;?></td> 
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
