<?php
	session_start();
	include('./includes/title.inc.php');
	
	
	$isLoggedIn = false;
	if (isset($_SESSION['login'])) {
		// user is logged in
		if ($_SESSION['login'] == true) {
			$isLoggedIn = true;
		}
	}
	
	$isEditing = false;
	if (isset($_POST['edit'])) {
		$isEditing = true;
	}
	
	$missing = array();
	if (isset($_POST['editing'])) {
		// user submit edited form
		$required = array('email', 'pwd');
		
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
			$userUpdatedInfo = $_POST;
			// update successfully, save data to DataBase via SQL
			
			
			header('Location: memberInfo.php');
			exit();
		}
	}
	
	$username = "";
	$sex = "";
	$email = "";
	$mailingAdd = "";
	$contact_no = "";
	$bookings = array();
	
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
        <?php
        	if (!$isLoggedIn) {
				?>
				<div id="noSearchWarning">
        			<h2><span class="warning">Please log in first. </span></h2>
            	</div>
                <?php
			} else {
				// check if user is editing his information
				if ($isEditing) { ?>
					<div id="editing">
                    	<p>Username:&nbsp;&nbsp;<?php echo $username ?></p>
						<p>Sex:&nbsp;&nbsp;<?php echo $sex ?></p>
						<form action="" method="post" id="editing">
							  <p>
                                <label for="email">Email:
                                <?php if (isset($_POST['editing']) && $missing && in_array('email', $missing)) { ?>
                                  <span class="warning">Please enter your Email Address.</span>
                                <?php } ?>
                                </label>
                                <input type="email" name="email" id="email" value="<?php echo $email ?>"
                                <?php if ($missing) { 
                                 echo 'value="' . htmlentities($email, ENT_COMPAT, 'UTF-8') . '"';
                                } ?>>
                              </p>
                              
                              <p>
                                <label for="pwd">Password:
                                <?php if (isset($_POST['register']) && $missing && in_array('email', $missing)) { ?>
                                  <span class="warning">Please enter a valid password.</span>
                                <?php } ?>
                                </label>
                                <input type="password" name="pwd" id="pwd">
                              </p>
                              <p>
                                <label for="contact_no">Contact Number:</label>
                                <input type="tel" name="contact_no" id="contact_no" class="formbox" value="<?php echo $contact_no ?>"
                                <?php if ($missing) { 
                                 echo 'value="' . htmlentities($contact_no, ENT_COMPAT, 'UTF-8') . '"';
                                } ?>>
                              </p>
                              <p>
                                <label for="mailAdd">Mailing Address:</label>
                                <input type="text" name="mailAdd" id="mailAdd" class="formbox" value="<?php echo $mailingAdd ?>"
                                <?php if ($missing) { 
                                 echo 'value="' . htmlentities($mailAdd, ENT_COMPAT, 'UTF-8') . '"';
                                } ?>>
                              </p>
							  <p>
								<input type="submit" name="editing" id="editing" value="Confirm">
							  </p>
						</form>
					</div>
                    
				<?php } else {
					// display user info
					?>
					<div id="userInfo">
						<p>Username:&nbsp;&nbsp;<?php echo $username ?></p>
						<p>Sex:&nbsp;&nbsp;<?php echo $sex ?></p>
						<p>E-mail Address:&nbsp;&nbsp;<?php echo $email ?></p>
						<p>Mailing Address:&nbsp;&nbsp;<?php echo $mailingAdd ?></p>
						<p>Contact Number:&nbsp;&nbsp;<?php echo $contact_no ?></p>
						<p></p>
					</div>
						<form action="" method="post" id="editUserInfo"> 
						<p>
							<input type="submit" name="edit" id="edit" value="Edit">
						</p>
						</form>
                        
                        <div id="userBookings">
							<?php 
                                if (!empty($bookings)) {
                                    // print booking details for each booking
                                    // same as the one in payment page
                                        
                                }
                            ?>
                        </div>
					<?php
				}
			}
		?>
        </div>
   </div>
   
   <div id="footer">
	<p>&copy; Copyright 2014 Wang YanHao && Ding XiangFei</p>
	</div>
</body>
</html>