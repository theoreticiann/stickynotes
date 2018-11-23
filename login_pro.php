<?php


	session_start();


	$_SESSION["id"] = $_POST["id"];
	$_SESSION["name"] = $_POST["name"];
	$_SESSION["email"] = $_POST["email"];


    $db = mysqli_connect("107.170.203.30", "qafmzsuxhq", "qafmzsuxhq", "qafmzsuxhq");


	$db = "SELECT * FROM users WHERE email='".$_POST["email"]."'";
	$result = $db->query($db);


	if(!empty($result->fetch_assoc())){
		$db = "UPDATE users SET google_id='".$_POST["id"]."' WHERE email='".$_POST["email"]."'";
	}else{ls
		$db = "INSERT INTO users (name, email, google_id) VALUES ('".$_POST["name"]."', '".$_POST["email"]."', '".$_POST["id"]."')";
	}


	$db->query($db);


	echo "Updated Successful";
?>
