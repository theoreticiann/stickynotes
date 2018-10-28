<?php 
    // initialize errors variable
	$errors = "";

	// connect to database
	$db = mysqli_connect("107.170.203.30", "qafmzsuxhq", "SamaJZe6Nf", "note");

	// insert a quote if submit button is clicked
	if (isset($_POST['submit'])) {
		if (empty($_POST['note'])) {
			$errors = "Please add any note";
		}else{
			$note = $_POST['note'];
			$sql = "INSERT INTO sticky_notes (content) VALUES ('$note')";
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
	<div class="heading">
		<h2 style="font-style: 'Hervetica';">Sticky Notes</h2>
	</div>
	<form method="post" action="index.php" class="input_form">
		<input type="text" name="note" class="task_input">
		<button type="submit" name="submit" id="add_btn" class="add_btn">Add Note</button>
		<?php if (isset($errors)) { ?>
		<p><?php echo $errors; ?></p>
		<?php } ?>
	</form>
	
	<table>
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
		$notes = mysqli_query($db, "SELECT * FROM sticky_notes");

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
