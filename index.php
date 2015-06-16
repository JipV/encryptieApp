<?php			
include_once("phpcrypt/phpCrypt.php");
use PHP_Crypt\PHP_Crypt as PHP_Crypt;
?>
<html>

	<head>
		<meta charset="UTF-8">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<title>Le beautiful encrypion web app for vewy,vewy secure storage</title>
	</head>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
	<body>
		<div class="container">
			<div class="row">
				<div class="col-md-4 well">
					<form method="post">
						<h1>Encrypt-R-us</h1>
						<h4>Much secure, very wow</h4>
						<p><label for="naam">Naam: <input class="form-control" type='text' id="naam" name="naam"/></label></p>
						<p><label for="geheim">Geheime tekst: <textarea  class="form-control" id="geheim" name="geheim"></textarea></label></p>
						<p><label for="wachtwoord">Wachtwoord: <input  class="form-control" type='password'  id="wachtwoord" name="wachtwoord"/></label></p>
						<p><input type="submit" value="Sla mijn geheim op!" class="btn btn-primary"/></p>
					</form>
				</div>
				<div class="col-md-4 col-md-push-2 well">
					<h1>Decrypt-R-us</h1>
						<h4>Don't worry, it's suuuuuuper safe</h4>
					<form method="post">
						<p><label for="username">Naam: <input class="form-control" type='text' id="username" name="username"/></label></p>
						<p><label for="password">Wachtwoord: <input  class="form-control" type='password'  id="password" name="password"/></label></p>
						<p><input type="submit" value="reveal my secret" class="btn btn-primary"/></p>
					</form>
				</div>
			</div>
		</div>
		<?php

			if(isset($_POST['naam']) && isset($_POST['wachtwoord']) && isset($_POST['geheim'])){
				$data = $_POST['geheim'];
			$key  = "SuchSecretKeyVeryWowMuchFantastic";
			$crypt = new PHP_Crypt($key, PHP_Crypt::CIPHER_AES_128, PHP_Crypt::MODE_ECB);
			$encryptedSecret = $crypt->encrypt($data);
			$encryptedPass = $crypt->encrypt($_POST['wachtwoord']);

			$connection = mysqli_connect('127.0.0.1','root','','sec_2');
			$sql = "INSERT INTO secrets (naam,wachtwoord,secret) VALUES ('".$_POST['naam']."','".addslashes($encryptedPass)."','".addslashes($encryptedSecret)."');";
			$query = mysqli_query($connection,$sql);


				
			}

			if(isset($_POST['username']) && isset($_POST['password'])){
				$key  = "SuchSecretKeyVeryWowMuchFantastic";
				$crypt = new PHP_Crypt($key, PHP_Crypt::CIPHER_AES_128, PHP_Crypt::MODE_ECB);
				$connection = mysqli_connect('127.0.0.1','root','','sec_2');
				$sql = "SELECT * FROM secrets WHERE naam = '".$_POST['username']."'";
				$query = mysqli_query($connection,$sql);
				$array = mysqli_fetch_assoc($query);
				$decryptedPassword = trim($crypt->decrypt($array['wachtwoord']));
				$formPassword = trim($_POST['password']);

				if(strcmp($decryptedPassword,$formPassword) == 0){
					?>
					<script>alert("Jouw geheim is:  <?php echo trim($crypt->decrypt($array['secret']));?>");</script>
					<?php
				}
			}
		?>
	</body>
</html>