<?php include('server.php') ?>
<!DOCTYPE html>
<html>
<head>
  <title>Sticky Notes — Login</title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<meta name="google-signin-client_id" content="302607163529-0gqpkub166g3oofkqa1vicdsdivnpf9k.apps.googleusercontent.com">
	<script src="https://apis.google.com/js/platform.js" async defer></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</head>
<body>
  <div class="header">
		<h2>Sticky Notes — Login</h2>
	</div>
  <form align="center" method="post" action="login.php">
  	<?php include('errors.php'); ?>
  	<div class="input-group">
  		<input type="text" name="username" placeholder="Username or Email">
  	</div>
  	<div class="input-group">
  		<input type="password" name="password" placeholder="Password">
  	</div>
  	<div align="center" class="input-group">
			<button type="submit" class="btn" name="login_user">Login</button>
			<br />
			or
			<br />
			<div align ="center" class="g-signin2" data-onsuccess="onSignIn"></div>

  	</div>
  	<p>
  		Not yet a member? <a href="register.php">Sign up</a>
		</p>
	</form>
	
	<script type="text/javascript">
	function onSignIn(googleUser) {
	  var profile = googleUser.getBasicProfile();


      if(profile){
          $.ajax({
                type: 'POST',
                url: 'login_pro.php',
                data: {id:profile.getId(), name:profile.getName(), email:profile.getEmail()}
            }).done(function(data){
                console.log(data);
                window.location.href = 'index.php';
            }).fail(function() { 
                alert( "Posting failed." );
            });
      }


    }
</script>
	
</body>
</html>