<?php
	session_start();
	include('./includes/title.inc.php');
	//get class into the page
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
        <h1>Search Results </h1>
    </div>
    
    <div id="wrapper">
        <?php include('includes/menu.inc.php'); ?>
        
        <div id="mainContent">
			<?php
                if (!$isStarted) {
                    ?>  <div id="noSearchWarning">
                    		<h2><span class="warning">Please Search for Hotels via the Home Page! </span></h2>
                        </div> <?php
                } else {
                	// while loop to display searching results
					
                }
            ?>
        </div>
    </div>
</body>
</html>