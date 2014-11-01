<?php
	session_start();
	include('./includes/title.inc.php');
	// mark position of user
	$_SESSION['userPosi'] = 'Location: http://localhost/CS2102/payment.php';
	$isStarted = isset($_SESSION['roomInfo']);
	
	$booking = array();
	
	if($isStarted) {		
		// assign the booking details based on chosen hotel's info
		$roomInfo = $_SESSION['roomInfo'];
		
		// generate booking id
		// TODO: booking id is not available until you insert into table, how?
		$bookingId = "32974392";
		// calculate the price needed to pay
		$totalPrice = 100;
		
		// dummy booking array
		$booking = array('bookId' => $bookingId,
						'price' => $totalPrice,
						'hotelName' => 'Hotel Name',
						'roomType' => $roomInfo['roomTypes'],
						'checkInDate' => '05/12/2014',
						'checkOutDate' => '18/01/2015');
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
    	<h2>Payment </h2>
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
            			<input type="submit" value="Book the room"/>&nbsp;&nbsp;
            			<input type="submit" value="Cancel and return"/>
            			<input type="hidden" name="action" value="confirm"/>
            		</div>
            	</form>
            </div>
<?php
	// end of form
	}
?>
        </div>
    </div>
</body>
</html>