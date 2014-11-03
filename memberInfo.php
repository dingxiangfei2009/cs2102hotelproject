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
				// display user info
				?>
                <div id="userInfo">
                	<p>Username:&nbsp;&nbsp;<?php echo $username ?></p>
                    <p>Sex:&nbsp;&nbsp;<?php echo $sex ?></p>
                    <p>E-mail Address:&nbsp;&nbsp;<?php echo $email ?></p>
                    <p>Mailing Address:&nbsp;&nbsp;<?php echo $mailingAdd ?></p>
                    <p>Contact Number:&nbsp;&nbsp;<?php echo $contact_no ?></p>
                    
                    <?php 
						if (!empty($bookings)) {
							// print booking details for each booking
							// same as the one in payment page
								
						}
					?>
                </div>
                <?php	
			}
		?>
        </div>
   </div>
   
   <div id="footer">
	<p>&copy; Copyright 2014 Wang YanHao && Ding XiangFei</p>
	</div>
</body>
</html>