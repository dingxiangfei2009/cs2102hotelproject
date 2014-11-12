<?php
	session_start();
	require_once('services/query.php');
	include('./includes/title.inc.php');
	
	
	$conn = new Connector();
	$isLoggedIn = false;
	if (isset($_SESSION['login'])) {
		// user is logged in
		if ($_SESSION['login'] === true) {
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
		$required = array('pwd');
		
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
			// update successfully, save data to DataBase via SQL
			if (updateUser($conn, $_SESSION['email'], $_POST['pwd'], $_POST['mailAdd'], $_POST['contact_no'])) {
				header('Location: memberInfo.php');
				exit();
			}
		}
	} else if (isset($_POST['delete'])) {
		deleteBooking($conn, $_POST['bookingId']);
	}
	
	$email = $_SESSION['email'];
	$userInfo = queryUser($conn, $email);
	if (!$userInfo) {
		header('Location: login.php');
		exit();
	}
	$sex = $userInfo['sex'] == 'MALE' ? 'Male' : 'Female';
	$username = $userInfo['name'];
	$mailingAdd = $userInfo['mailingAddress'];
	$contact_no = $userInfo['contactNumber'];
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
					<div id="userInfo">
                    	<p>Username:&nbsp;&nbsp;<?php echo $username ?></p>
						<p>Sex:&nbsp;&nbsp;<?php echo $sex ?></p>
						<p>Email:&nbsp;&nbsp;<?php echo $email ?></p>
                        </div>
                    <div id="editing">
						<form action="" method="post" id="editing">
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
                                // print booking details for each booking
                                // same as the one in payment page
                                $n = 0;
                                $bookings = array();
                                $resultSet = queryUserBookings($conn, $email);
                                while ($resultSet($bookingId, $hotelName, $hotelImage, $roomNumber, $roomType, $checkInDate, $checkOutDate, $price))
                                	$bookings[$n++] = array(
                                		'bookingId' => $bookingId,
                                		'hotelName' => $hotelName,
                                		'hotelImage' => $hotelImage,
                                		'roomNumber' => $roomNumber,
                                		'roomType' => $roomType,
                                		'checkInDate' => $checkInDate,
                                		'checkOutDate' => $checkOutDate,
                                		'price' => $price
                                		);
                                foreach ($bookings as $entry) {
                            ?>

          <div id="bookingInfoPic">
								        	<img src="<?php echo $entry['hotelImage'] ?>" width="180" height="180" align="right" />
								        </div>
			                <table class="form">
			                    <tr>
			                        <td>
			                            <div id="bookInfoPara">Booking ID</div>
			                        </td>
			                    </tr>
			                    <tr>
			                        <td>
			                            <?php echo $entry['bookingId'] ?>
			                        </td>
			                    </tr>
			                    <tr>
			                        <td>
			                            <div id="bookInfoPara">Hotel</div>
			                        </td>
			                    </tr>
			                    <tr>
			                        <td>
			                            <?php echo $entry['hotelName'] ?>
			                        </td>
			                    </tr>
			                    <tr>
			                        <td>
			                            <div id="bookInfoPara">Room Type</div>
			                        </td>
			                    </tr>
			                    <tr>
			                        <td>
			                        	<?php echo $entry['roomType'] ?>
			                        </td>
			                    </tr>
			                    <tr>
			                        <td>
			                            <div id="bookInfoPara">Room Number</div>
			                        </td>
			                    </tr>
			                    <tr>
			                        <td>
			                            <?php echo $entry['roomNumber'] ?>
			                        </td>
			                    </tr>
			                    <tr>
			                        <td>
			                            <div id="bookInfoPara">Check In Date</div>
			                        </td>
			                    </tr>
			                    <tr>
			                        <td>
			                            <?php echo $entry['checkInDate'] ?>
			                        </td>
			                    </tr>
			                    <tr>
			                        <td>
			                            <div id="bookInfoPara">Check Out Date</div>
			                        </td>
			                    </tr>
			                    <tr>
			                        <td>
			                            <?php echo $entry['checkOutDate'] ?>
			                        </td>
			                    </tr>
			                    <tr>
			                        <td>
			                            <div id="bookInfoPara">Price</div>
			                        </td>
			                    </tr>
			                    <tr>
			                        <td>
			                            <?php echo $entry['price'] ?>
			                        </td>
			                    </tr>
			                    <tr>
			                    	<td>
			                    		<form action="" method="post">
			                    			<input type="hidden" name="bookingId" value="<?php echo $entry['bookingId'] ?>"/>
			                    			<input type="submit" name="delete" value="Delete"/>
			                    		</form>
			                    	</td>
			                    </tr>
			                </table>
                            <?php
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
	<p>&copy; Copyright 2014 Wang YanHao &amp; Ding XiangFei</p>
	</div>
</body>
</html>