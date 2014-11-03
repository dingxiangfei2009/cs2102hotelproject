<?php
	session_start();
	include('./includes/title.inc.php');
	// mark position of user
	$_SESSION['userPosi'] = 'payment.php';
	$isStarted = isset($_SESSION['roomInfo']);
	
	$booking = array();
	
	if($isStarted) {		
		// assign the booking details based on chosen hotel's info
		$roomInfo = $_SESSION['roomInfo'];
		
		// calculate the price needed to pay
		$totalPrice = 100;
		
		// dummy booking array
		$booking = array('bookId' => $bookingId,
						'price' => $totalPrice,
						'hotelName' => 'Hotel Name',
						'roomType' => $roomInfo['roomTypes'],
						'checkInDate' => '05/12/2014',
						'checkOutDate' => '18/01/2015');
						
						
		// check if the form has been submitted
		if (isset($_POST['cancel'])) {
			// head back to room page
			header('Location: room.php');
		} else if (isset($_POST['confirm'])) {
			// push all the info to database and insert into booking table, 
			$_SESSION['paymentInfo'] = $booking;
			
			// direct the page to receipt
			header('Location: receipt.php');
		}
	}
	
	// TODO: xhtml dropped, use html5 instead
?>

<html>
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
    if (!$isStarted) {
?>
            <div id="noRoomInfoWarning">
        		<h2><span class="warning">Please search for hotels via the Home Page </span></h2>
                <h2><span class="warning">or select a room via the Hotel Page! </span></h2>
            </div>
<?php
    } else {
		// display the booking details
?>
        	<div id="bookingInfo">
            	<form action="payment.php">
            		<table class="form">
            			<tr>
            				<td>
            					<div>Hotel</div>
            				</td>
            			</tr>
            			<tr>
            				<td>
            					<input disabled="disabled" type="text" value="<?php echo $hotelName ?>"/>
            				</td>
            			</tr>
            			<tr>
            				<td>
            					<div>Room Type</div>
            				</td>
            			</tr>
            			<tr>
            				<td>
            					<input disabled="disabled" type="text" value="<?php echo $roomType?>">
<?php // Do you have a dropdown here to select room type ?>
            				</td>
            			</tr>
            			<tr>
            				<td>
            					<div>Check In Date</div>
            				</td>
            			</tr>
            			<tr>
            				<td>
            				</td>
            			</tr>
            			<tr>
            				<td>
            					<div>Check Out Date</div>
            				</td>
            			</tr>
            			<tr>
            				<td>
            				</td>
        				</tr>
            		</table>
            		<div>
            			<input type="submit" name="confirm" id="confirm" value="Confirm"/>&nbsp;&nbsp;
            			<input type="submit" name="cancel" id="cancel" value="Cancel"/>
            		</div>
            	</form>
            </div>
<?php
	// end of form
	}
?>
        </div>
    </div>
    
    <div id="footer">
	<p>&copy; Copyright 2014 Wang YanHao && Ding XiangFei</p>
	</div>
</body>
</html>