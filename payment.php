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
						
		// check if the form has been submitted
		$error = false;
        $checkInDate = $_SESSION['searchInfo']['date1'];
        $checkOutDate = $_SESSION['searchInfo']['date2'];
		$conn = new Connector();
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
			if ($_SESSION['searchInfo']['date1'] == '0000-00-00' || $_SESSION['searchInfo']['date2'] == '0000-00-00') {
				// no specific date
				header('Location: index.php');
				exit();
			} else if ($_POST['price'] != $price || $_POST['roomNumber'] != $roomNumber) {
				header('Location: room.php');
				exit();
			}
			
			$info = array(
				'zipCode' => $roomInfo['zipCode'],
				'roomNumber' => $roomNumber,
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

		$resultSet = queryHotelInformation($conn, $roomInfo['zipCode']);
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
            	<form action="payment.php" method="post">
            		<table class="form">
            			<tr>
            				<td>
            					<div id="bookInfoPara">Hotel</div>
            				</td>
            			</tr>
            			<tr>
            				<td>
            					<input disabled="disabled" type="text" value="<?php echo $_SESSION['hotelInfo']['name'] ?>"/>
                            </td>
            			</tr>
            			<tr>
            				<td>
            					<div id="bookInfoPara">Room Type</div>
            				</td>
            			</tr>
            			<tr>
            				<td>
            					<input disabled="disabled" type="text" value="<?php echo $roomInfo['roomType']?>"/>
            				</td>
            			</tr>
                        <tr>
            				<td>
            					<div id="bookInfoPara">Room Number</div>
            				</td>
            			</tr>
            			<tr>
                        	<td>
            					<input disabled="disabled" type="text" name="roomNumber" value="<?php echo $roomNumber ?>"/>
            				</td>
            			</tr>
            			<tr>
            				<td>
            					<div id="bookInfoPara">Check In Date</div>
            				</td>
            			</tr>
            			<tr>
            				<td>
            					<input disabled="disabled" type="text" value="<?php echo $_SESSION['searchInfo']['date1']?>"/>
            				</td>
            			</tr>
            			<tr>
            				<td>
            					<div id="bookInfoPara">Check Out Date</div>
            				</td>
            			</tr>
            			<tr>
            				<td>
            					<input disabled="disabled" type="text" value="<?php echo $_SESSION['searchInfo']['date2']?>"/>
            				</td>
        				</tr>
        				<tr>
        					<td>
        						<div id="bookInfoPara">Price</div>
        					</td>
        				</tr>
        				<tr>
        					<td>
        						<input disabled="disabled" type="text" value="<?php echo $price?>"/>
        						<input type="hidden" name="price" value="<?php echo $price?>"/>
        					</td>
        				</tr>
        				<tr>
        					<td>
        						<div id="bookInfoPara">Payment Method</div>
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
            		<div id="bookInfoPara">
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