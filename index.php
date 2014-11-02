<?php
	session_start();
	include('./includes/title.inc.php');
	// mark position of user
	$_SESSION['userPosi'] = 'Location: http://localhost/CS2102/index.php';
	
	$missing = array();
	$Hotel_Name = "";
	$Destination = "";
	
	// check if the form has been submitted
    if (isset($_POST['sendSearch'])) {
	  	// search button is pressed  
		$required = array('Hotel_Name', 'Destination');
		
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
			$_SESSION['searchInfo'] = $_POST;
			header('Location: hotel.php');
		}
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript" src="calendar/calendar.js"></script>
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
        <h2>Search </h2>
    <form id="search" method="post" action="">
            <p>
                <label for="Hotel Name">Hotel Name:
                <?php if ($missing && in_array('Hotel_Name', $missing)) { ?>
                  <span class="warning">Please enter Hotel Name</span>
                <?php } ?>
                </label>
                <input name="Hotel Name" id="Hotel_Name" type="text" class="formbox"
                <?php if ($missing) { 
                 echo 'value="' . htmlentities($Hotel_Name, ENT_COMPAT, 'UTF-8') . '"';
                } ?>
                >
            </p>
            
      		<p>
                <label for="Destination">Destination:
                <?php if ($missing && in_array('Destination', $missing)) { ?>
                  <span class="warning">Please enter your Destination</span>
                <?php } ?>
                </label>
                <input name="Destination" id="Destination" type="text" class="formbox"
                <?php if ($missing) { 
                 echo 'value="' . htmlentities($Destination, ENT_COMPAT, 'UTF-8') . '"';
                } ?>
                >
      		</p>
            
            <table border="0" cellspacing="0" cellpadding="2">
            <tr>
            <td nowrap>Check In Date :</td>
            <td>
         		<?php
					//get class into the page
					require_once('calendar/classes/tc_calendar.php');
					
					$myCalendar = new tc_calendar("date1", true, false);
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
            
            <table border="0" cellspacing="0" cellpadding="2">
          	<tr>
            <td nowrap>Check Out Date :</td>
            <td>
            	<?php
					//get class into the page
					require_once('calendar/classes/tc_calendar.php');
					
					$myCalendar = new tc_calendar("date2", true, false);
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
            
            <p>
            	<input name="sendSearch" id="sendSearch" type="submit" value="Search" />
    		</p>
      </form>
    </div>
</div>

<div id="footer">
	<p>&copy; Copyright 2014 Wang YanHao && Ding XiangFei</p>
</div>

</body>

</html>
