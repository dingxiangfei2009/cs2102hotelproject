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
		$error = false;
		if (isset($_POST['cancel'])) {
			// head back to room page
			header('Location: room.php');
		} else if (isset($_POST['confirm'])) {
			$conn = new Connector();
			if (insertBooking($conn, array())) {
				// direct the page to receipt
				header('Location: receipt.php');
			} else {
				$error = true;
			}
		}

		$conn = new Connector();
		$resultSet = queryHotelInformation($conn, $roomInfo['zipcode']);
		$resultSet($hotelName, $x, $x, $x, $x);
		$resultSet($x, $x, $x, $x, $x, false);
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
            <div id="noSearchWarning">
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
            				</td>
            			</tr>
            			<tr>
            				<td>
            					<div>Check In Date</div>
            				</td>
            			</tr>
            			<tr>
            				<td>

<?php
	//get class into the page
	require_once('calendar/classes/tc_calendar.php');
	
	$myCalendar = new tc_calendar("checkInDate", true, false);
	$myCalendar->setIcon("calendar/images/iconCalendar.gif");
	$myCalendar->setPath("calendar/");
	$myCalendar->setDate(date('d'), date('m'), date('Y'));
	$myCalendar->setYearInterval(2014, 2015);
	$myCalendar->dateAllow('2014-10-31', '2015-03-01');
	$myCalendar->setDateFormat('j F Y');
	$myCalendar->setAlignment('left', 'bottom');
	$myCalendar->writeScript();  
?>
            				</td>
            			</tr>
            			<tr>
            				<td>
            					<div>Check Out Date</div>
            				</td>
            			</tr>
            			<tr>
            				<td>
<?php
		$myCalendar = new tc_calendar("checkOutDate", true, false);
		$myCalendar->setIcon("calendar/images/iconCalendar.gif");
		$myCalendar->setPath("calendar/");
		$myCalendar->setDate(date('d'), date('m'), date('Y'));
		$myCalendar->setYearInterval(2014, 2015);
		$myCalendar->dateAllow('2014-10-31', '2015-03-01');
		$myCalendar->setDateFormat('j F Y');
		$myCalendar->setAlignment('left', 'bottom');
		$myCalendar->writeScript();  
?>
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
	<p>&copy; Copyright 2014 Wang YanHao &amp;&amp; Ding XiangFei</p>
	</div>
</body>
</html>