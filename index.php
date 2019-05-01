<?php

//DB CONNECTION
$db = mysqli_connect("localhost", "root", "", "proserv-golf");

//GEORGIAN CHARACTERS
mysqli_set_charset($db,"utf8");

//VARS
$email = $bdate = $name = $surname = $country = $pass = $repass = "";

//ERROR VARS
$email_err = $pass_err = $repass_err = "";

//QUERY IF SMTH WRONG NOT TO INSERT INVALID DATA INTO DB
$sql = "SELECT * FROM members";

//IF BUTTON PRESSED
if (isset($_POST['register'])) {

	//IF EMAIL CORRECT
	if (!preg_match("^[a-z0-9](\.?[a-z0-9_-]){0,}@[a-z0-9-]+\.([a-z]{1,}\.)?[a-z]{2,}$^", $_POST['email'])) {
		$email_err = "ელ-ფოსტის ფორმატი არასწორია.";
	}else{
		$email = trim($_POST["email"]);
	}

	//PREPARE VALUES
	$bdate=strtotime($_POST['bdate']);
	$bdate=date("Y-m-d",$bdate); //FOR DATE DATATYPE ('Y-m-d')
	$name = trim($_POST["name"]);
	$surname = trim($_POST["surname"]);
	$country = trim($_POST["country"]);

	if(strlen(trim($_POST["pass"])) < 6){
		$pass_err = "პაროლი უნდა შეიცავდეს მინ. 6 სიმბოლოს.";
	} else{
		$pass = trim($_POST["pass"]);
	}

	$repass = trim($_POST["repass"]);
	
	if($pass != $repass){
		$repass_err = "პაროლები ერთმანეთს არ ემთხვევა.";
	}

	//IF EVERYTHING OK
    if( empty($email_err) && empty($pass_err) && empty($repass_err) ){
    	//INSERT DATA INTO DB
		$sql = "INSERT INTO members (email, bdate, name, surname, country, pass) VALUES ('$email', '$bdate', '$name', '$surname', '$country', '$pass')";
    }

    //EXECUTE SQL QUERY
    mysqli_query($db, $sql);
}

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>გახდი წევრი</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
	<style type="text/css">
		.form-group{
			margin: 10px;
		}
	</style>
</head>
<body>
<section>
	<!--FORM, METHOD = POST-->
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
	<!--FORM RELATED ELEMENT-->
	<div class="form-group">
		<input type="email" name="email" class="form-control" value="<?php echo $email; //SET VALUE OF FIELD = VAR VALUE ?>" placeholder="ელ. ფოსტა" required>
		<!--ECHO ERROR--><span class="help-block"><?php echo $email_err; ?></span>
	</div>

	<div class="form-group">
		<input type="date" name="bdate" class="form-control" value="<?php echo $bdate; ?>" placeholder="დაბადების თარიღი" required>
	</div>

	<div class="form-group">
		<input type="text" name="name" class="form-control" value="<?php echo $name; ?>" placeholder="სახელი" required>
	</div>

	<div class="form-group">
		<input type="text" name="surname" class="form-control" value="<?php echo $surname; ?>" placeholder="გვარი" required>
	</div>

	<div class="form-group">
		<input type="text" name="country" class="form-control" value="<?php echo $country; ?>" placeholder="ქვეყანა" required>
	</div>

	<div class="form-group">
		<input type="file" name="photo" class="form-control" value="<?php echo $photo; ?>" placeholder="ფოტო">
	</div>

	<div class="form-group">
		<input type="password" name="pass" class="form-control" value="<?php echo $pass; ?>" placeholder="პაროლი" required>
		<!--ECHO ERROR--><span class="help-block"><?php echo $pass_err; ?></span>
	</div>

	<div class="form-group">
		<input type="password" name="repass" class="form-control" value="<?php echo $repass; ?>" placeholder="გაიმეორეთ პაროლი" required>
		<!--ECHO ERROR--><span class="help-block"><?php echo $repass_err; ?></span>
	</div>

	<div class="form-group">
		<input type="submit" class="btn btn-primary" value="გაგზავნა" name="register">
	</div>

	<?php
		//SEND MAIL WITH DATA
	  if (isset($_POST["register"])) {
		  $to      = '' . $_POST['email'] . '';
		  $subject = 'მონაცემები';
		  $message = 'სახელი: ' . $_POST['name'] . ', გვარი: ' . $_POST['surname'] . ', ქვეყანა: ' . $_POST['country'] . ', ელ-ფოსტა: ' . $_POST['email'] . ', დაბადების თარიღი: ' . $_POST['bdate'] . '.';
		  mail($to, $subject, $message);
		  //ECHO SUCCESS MESSAGE
		  echo '<p style="font-size:10px; margin-left:10px;">მონაცემები წარმატებით გაიგზავნა.</p>';
	  }
	?>
</form>
</section>
</body>
</html>