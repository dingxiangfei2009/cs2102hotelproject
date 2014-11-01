<?php
	session_start();
	include('./includes/title.inc.php');
	$isStarted = isset($_SESSION['roomInfo']);
	
	$booking = array();
	
	if($isStarted) {
		// assign the booking details based on chosen hotel's info
		$roomInfo = $_SESSION['roomInfo'];
		
		// generate booking id
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
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Hotel Booking</title>
<link href="styles/hotel.css" rel="stylesheet" type="text/css" />
</head>

<body>

<body>
    <div id="header">
        <h1>Payment </h1>
    </div>
    
    <div id="wrapper">
        <?php include('includes/menu.inc.php'); ?>
        
        <div id="mainContent">
			<?php
                if (!$isStarted) {
                    ?>  <div id="noRoomInfoWarning">
                    		<h2><span class="warning">Please search for hotels via the Home Page </span></h2>
                            <h2><span class="warning">or select a room via the Hotel Page! </span></h2>
                        </div> <?php
                } else {
					// display the booking details
					?>
                    	<div id="bookingInfo">
                        	
                        </div>
                    <?php
				}
			?>
    </div>
</body>
</html>