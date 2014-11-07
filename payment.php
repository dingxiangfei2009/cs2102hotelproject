<?php
	require_once('services/query.php');
	session_start();
	include('./includes/title.inc.php');
	// mark position of user
	$_SESSION['userPosi'] = 'payment.php';
	$isStarted = isset($_SESSION['roomInfo']);
	
	$booking = array();
	
	if($isStarted) {		
		// assign the booking details based on chosen hotel's info
		$roomInfo = $_SESSION['roomInfo'];
		$searchInfo = $_SESSION['searchInfo'];
		
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
        $checkInDate = $_SESSION['searchInfo']['date1'];
        $checkOutDate = $_SESSION['searchInfo']['date2'];
		queryHotelChooseAvailableRoomWithType(
			$conn,
			$roomInfo['zipCode'],
			$roomInfo['roomType'],
			$checkInDate,
			$checkOutDate,
			$roomNumber,
			$price);
		if ($roomNumber == null) {
			header('Location: room.php');
			exit();
		}
		if (isset($_POST['cancel'])) {
			// head back to room page
			header('Location: room.php');
		} else if (isset($_POST['confirm'])) {
			// check if user has input check in and check out 
			if ($_SESSION['searchInfo']['date1'] === '0000-00-00' || $_SESSION['searchInfo']['date2'] === '0000-00-00') {
				// no specific date
				header('Location: index.php');
				exit();
			} else if ($_POST['price'] != $price) {
				header('Location: room.php');
				exit();
			}
			
			$conn = new Connector();
			$info = array(
				'zipCode' => 
				'emailAddress' => $_SESSION['email'],
				'checkInDate' => $searchInfo['date1'],
				'checkOutDate' => $searchInfo['date2'],
				'checkInTime' => '12:0:0',
				'checkOutTime' => '12:0:0',	// TODO
				'price' => $price,
				'paymentMethod' => $_POST['paymentMethod']
			);
			if (insertBooking($conn, $info)) {
				// direct the page to receipt
				$_SESSION['bookingInfo'] = $info;
				header('Location: receipt.php');
				exit();
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
            					<input disabled="disabled" type="text" value="<?php echo $_SESSION['searchInfo']['date1']?>">
            				</td>
            			</tr>
            			<tr>
            				<td>
            					<div>Check Out Date</div>
            				</td>
            			</tr>
            			<tr>
            				<td>
            					<input disabled="disabled" type="text" value="<?php echo $_SESSION['searchInfo']['date2']?>">
            				</td>
        				</tr>
        				<tr>
        					<td>
        						<div>Price</div>
        					</td>
        				</tr>
        				<tr>
        					<td>
        						<input disabled="disabled" type="text" value="<?php echo $roomInfo?>">
        					</td>
        				</tr>
        				<tr>
        					<td>
        						<div>Payment Method</div>
        					</td>
        				</tr>
        				<tr>
        					<td>
        						<select name="paymentMethod">
        							<option value="mastercard">MasterCard</option>
        							<option value="visa">Visa</option>
        							<option value="wired">Wired</option>
        						</select>
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