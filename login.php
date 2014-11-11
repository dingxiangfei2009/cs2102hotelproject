<?php
  require_once('services/query.php');
	session_start();
	include('./includes/title.inc.php');
	
	if (isset($_POST['login'])) {
		// check for validity of the email and password
		$isValid = true;
		$userEmail = $_POST['login']['loginUserEmail'];
		$userPwd = $_POST['login']['loginPwd'];
		$conn = new Connector();
		// check
    $isValid = !!validUser($conn, $userEmail, $userPwd);
		
		if ($isValid) {
			$_SESSION['email'] = $userEmail;
			$_SESSION['login'] = true;
			header($_SESSION['userPosi']);
		} else {
			$message = "User name or password is invalid!  Please try again.";
			echo "<script type='text/javascript'>alert('$message');</script>";	
		}
	}
	
		
	$missing = array();
	$pwdError = false;
	$username = "";
	$email = "";
	$contact_no = "";
	$mailAdd = "";
	
	// check if the form has been submitted
    if (isset($_POST['register'])) {
	  	// search button is pressed  
		$required = array('username', 'email', 'pwd', 'conf_pwd');
		
		// process $_POST variables
		foreach ($_POST as $key => $value) {
			$temp = is_array($value) ? $value : trim($value);
			// check if required array is missing
			if (empty($temp) && in_array($key, $required)) {
				array_push($missing, $key);
			} else {
				${$key} = $temp;
			}
		}
		
		if (empty($missing)) {
			// check validity for pwd and confirmPwd
			if ($_POST['pwd'] != $_POST['conf_pwd']) {
				$pwdError = true;
			} else {
				
				$regUserInfo = $_POST;
				// SQL database check if primary key has been violated
				
				// add into SQL database
				
				$isRegSuc = true;
				
				if ($isRegSuc) {
					// successfully logged in,
					$_SESSION['login'] = true;
					header($_SESSION['userPosi']);
				} else {
					// reg unseccessful, display error msg
					$message = "Registration failed.";
					echo "<script type='text/javascript'>alert('$message');</script>";	
				}
			}
		}
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Hotel Booking</title>
<link href="styles/hotel.css" rel="stylesheet" type="text/css" />
</head>

<body>
    <div id="header">
    	<h1>Hotel Booking</h1>
    </div>
    
    <div id="wrapper">
        <?php include('includes/menu.inc.php'); ?>
        
        <div id="mainContent">
        	<div id="login">
        	<h2>Login </h2>
        	  <form action="" method="post" id="loginForm">
                  <p>
                    <label for="loginUserEmail">E-mail:</label>
                    <input type="text" name="loginUserEmail" id="loginUserEmail">
                  </p>
                  <p>
                    <label for="loginPwd">Password:</label>
                    <input type="password" name="loginPwd" id="loginPwd">
                  </p>
                  <p>
                    <input type="submit" name="login" id="login" value="Login">
                  </p>
            </form>
        	</div>
            
        	<div id="reg">
            <h2>Register User</h2>
            <form action="" method="post" id="regForm">
                  <p>
                    <label for="username">Username:
                    <?php if ($missing && in_array('username', $missing)) { ?>
                      <span class="warning">Please enter your name.</span>
                    <?php } ?>
                    </label>
                    <input type="text" name="username" id="username"
                    <?php if ($missing || $pwdError) { 
					 echo 'value="' . htmlentities($username, ENT_COMPAT, 'UTF-8') . '"';
					} ?>>
                  </p>
                  
                  <p>
                    <label for="sex" id="sex">Sex:</label>
                    <p>
                        <input name="sex" type="radio" value="Male" id="sex-male" 
                        <?php
                        if ($_POST && $_POST['sex'] == 'male') { 
                          echo 'checked';
                        } ?>>
                        <label for="sex-male" id="sex">Male</label>
                        <input name="sex" type="radio" value="Female" id="sex-female" 
                        <?php
                        if ($_POST && $_POST['sex'] == 'female') {
                          echo 'checked';
                        } ?>>
                        <label for="sex-female" id="sex">Female</label>
                    </p>
                  </p>
                  
                  <p>
                    <label for="email">Email:
                    <?php if (isset($_POST['register']) && $missing && in_array('email', $missing)) { ?>
                      <span class="warning">Please enter your Email Address.</span>
                    <?php } ?>
                    </label>
                    <input type="email" name="email" id="email"
                    <?php if ($missing || $pwdError) { 
					 echo 'value="' . htmlentities($email, ENT_COMPAT, 'UTF-8') . '"';
					} ?>>
                  </p>
                  
                  <p>
                    <label for="pwd">Password:
                    <?php if (isset($_POST['register']) && $missing && in_array('email', $missing)) { ?>
                      <span class="warning">Please enter a valid password.</span>
                    <?php } else if ($pwdError){ ?>
						<span class="warning">Two passward must be consistent.</span>
					<?php } ?>
                    </label>
                    <input type="password" name="pwd" id="pwd">
                  </p>
                  <p>
                    <label for="conf_pwd">Confirm Password:
                    <?php if (isset($_POST['register']) && $missing && in_array('email', $missing)) { ?>
                      <span class="warning">Please enter a valid password.</span>
                    <?php } ?>
                    </label>
                    <input type="password" name="conf_pwd" id="conf_pwd">
                  </p>
                  
                  <p>
                    <label for="contact_no">Contact Number:</label>
                    <input type="tel" name="contact_no" id="contact_no" class="formbox"
                    <?php if ($missing || $pwdError) { 
					 echo 'value="' . htmlentities($contact_no, ENT_COMPAT, 'UTF-8') . '"';
					} ?>>
                  </p>
                  <p>
                    <label for="mailAdd">Mailing Address:</label>
                    <input type="text" name="mailAdd" id="mailAdd" class="formbox"
                    <?php if ($missing || $pwdError) { 
					 echo 'value="' . htmlentities($mailAdd, ENT_COMPAT, 'UTF-8') . '"';
					} ?>>
                  </p>
                  
                  <p>
                    <input type="submit" name="register" id="register" value="Register">
                  </p>
            </form>
            </div>
        </div>
   </div>
   
   <div id="footer">
	<p>&copy; Copyright 2014 Wang YanHao && Ding XiangFei</p>
	</div>
</body>
</html>