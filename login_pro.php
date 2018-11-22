<?php


	session_start();


	$_SESSION["id"] = $_POST["id"];
	$_SESSION["name"] = $_POST["name"];
	$_SESSION["email"] = $_POST["email"];


    $db = mysqli_connect("107.170.203.30", "qafmzsuxhq", "qafmzsuxhq", "qafmzsuxhq");


	$sql = "SELECT * FROM users WHERE email='".$_POST["email"]."'";
	$result = $db->query($sql);


	if(!empty($result->fetch_assoc())){
		$sql2 = "UPDATE users SET google_id='".$_POST["id"]."' WHERE email='".$_POST["email"]."'";
	}else{ls
		$sql2 = "INSERT INTO users (name, email, google_id) VALUES ('".$_POST["name"]."', '".$_POST["email"]."', '".$_POST["id"]."')";
	}


	$db->query($sql2);


	echo "Updated Successful";
?>
