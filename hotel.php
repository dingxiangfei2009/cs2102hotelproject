<?php
	session_start();
	include('./includes/title.inc.php');
	// mark position of user
	$_SESSION['userPosi'] = 'Location: hotel.php';
	
	//get class into the page
	require('services/query.php');
	require_once('calendar/classes/tc_calendar.php');
	
	$isStarted = isset($_SESSION['searchInfo']);
	if($isStarted) {
		$searchInfo = $_SESSION['searchInfo'];
		
		$hotelName = $searchInfo["Hotel_Name"];
		$hotelAddress = $searchInfo["Destination"];
		
		$checkInDate = $searchInfo["date1"];
		$checkOutDate = $searchInfo["date2"];
		
		$isCheckInValid = false;
		$isCheckOutValid = false;
		
		if ($checkInDate != "0000-00-00") {
			$isCheckInValid = true;
			
		}
		if ($checkOutDate != "0000-00-00") {
			$isCheckOutValid = true;
			
		}
		
		$conn = new Connector();

		//	$search_sql = "";
		//	$search_query = mysql_query($search_sql);	
		//	$search_rs = mysql_fetch_assoc($search_query);
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
            <div id="contentHeader">
                <h2>Search Results </h2>
            </div>
            <div id="sort">
                
            </div>
			<?php
                if (!$isStarted) {
            ?>
            <div id="noSearchWarning">
                <h2><span class="warning">Please search for hotels via the Home Page. </span></h2>
            </div>
            <?php
                } else {
                	// for loop to display searching results
					$resultNumber = 10;
					$resultSet = queryAvailableHotel($conn, '', '', '', '');
					// sorry, yanhao
					// i will just use callback here

					for ($i=0; $i<$resultNumber
							&& $resultSet($name, $mailingAddress, $zipCode,
								$rating, $contactNumber, $avail,
								$minPrice, $maxPrice); $i++) {
						$divID = "result".$i;
						$picID = "picWrapper".$i;
						// ------ start result iteration------
			?>
			<div id="<?php echo $divID ?>"> 
            <?php // dummy model for now, need to pass the name of hotel as $hotelName into room.php
			?>
			<h2><a href="room.php?hotelname=<?php echo $hotelName?>">Hotel Name</a></h2>
            <div id="<?php echo $picID ?>">
            	<img src="calendar/images/disable_date_bg.png" width="100" height="100" align="right" />
            </div>
			<p>Rating:&nbsp;&nbsp;<?php echo $name ?></p>
			<p>Address:&nbsp;&nbsp;<?php echo $mailingAddress ?></p>
			<p>Price Range:&nbsp;&nbsp;<?php echo $minPrice ?>&nbsp;-&nbsp;<?php echo $maxPrice ?></p>
			<p>Availability:&nbsp;&nbsp;<?php echo $avail ?></p>
			<p>Contact Number:&nbsp;&nbsp;<?php echo $contactNumber ?></p>
			</div>
            <?php
            		// ------ end result iteration ------
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