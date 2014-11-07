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
	}
	
	$orderBy = 0;
	// sorting order
	if (!isset($_POST['sortby']) || $_POST['sortby'] == '' || $_POST['sortby'] == 'alphebarical') {
		// hotel name alphebarical order
		$orderBy = 0;
	} else if ($_POST['sortby'] == 'rating') {
		// 	hotel rating order
		$orderBy = 1;
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
            <div id="sort">
                <form id="sortHotelForm" method="post" action="">
                  <label for="sortHotelForm">Search results sorted by:
                    <select name="sortby" id="sortby">
                      <option value="alphebarical"
                                            <?php
                                            if (!$_POST || $_POST['sortby'] == '' || $_POST['sortby'] == 'alphebarical') {
                                              echo 'selected';
                                            } ?>>Alphebarical</option>
                      <option value="rating"
                                            <?php
                                            if ($_POST && $_POST['sortby'] == 'rating') {
                                              echo 'selected';
                                            } ?>>Rating</option>
                    </select>
                  <input type="submit" value="Sort" id="sort" name="sort"/>
                  </label>
              </form>
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
					$resultSet = queryAvailableHotel(
						$conn,
						$hotelName,
						$hotelAddress,
						$isCheckInValid ? $checkInDate : '',
						$isCheckOutValid ? $checkOutDate : '',
						$orderBy);

					$i = 0; $noSearchResult = true;
					while ($resultSet($name, $mailingAddress, $zipCode,
								$rating, $contactNumber, $image, $avail,
								$minPrice, $maxPrice)) {
						$noSearchResult = false;
						$divID = "result".$i;
						$picID = "picWrapper".$i;
						// ------ start result iteration------
			?>
			<div id="<?php echo $divID ?>">
			<h2><a href="room.php?zipcode=<?php echo $zipCode?>"><?php echo $name?></a></h2>
            <div id="<?php echo $picID ?>">
            	<img src="<?php echo $image ?>" width="100" height="100" align="right" />
            </div>
			<p>Rating:&nbsp;&nbsp;<?php echo $rating ?></p>
			<p>Address:&nbsp;&nbsp;<?php echo $mailingAddress ?></p>
			<p>Price Range:&nbsp;&nbsp;<?php echo $minPrice ?>&nbsp;-&nbsp;<?php echo $maxPrice ?></p>
			<p>Availability:&nbsp;&nbsp;<?php echo $avail ?></p>
			<p>Contact Number:&nbsp;&nbsp;<?php echo $contactNumber ?></p>
			</div>
            <?php
            		// ------ end result iteration ------
            		$i++;
					}
                }
                if ($noSearchResult) {
            ?>
            <div>Sorry, no hotel matches your search.</div>
            <?php
            	}
            ?>
        </div>
    </div>
    
    <div id="footer">
	<p>&copy; Copyright 2014 Wang YanHao &amp;&amp; Ding XiangFei</p>
	</div>
</body>
</html>