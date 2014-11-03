<?php
    require_once('services/query.php');
	session_start();
	include('./includes/title.inc.php');
	// mark position of user
	$_SESSION['userPosi'] = 'Location: room.php';
	
	$isStarted = isset($_GET['zipcode']);
	if ($isStarted) {
		// variable store the specific hotel been selected by the user
		$zipcode = $_GET['zipcode'];
	}
	
	$missing = false;
	$selectRoom = "";
	
	// check if the form has been submitted
    if (isset($_POST['sendPaymentRequest'])) {
		if (empty($_POST['roomTypes'])) {
			$missing = true;
		}
		
		if (!$missing) {
			$_SESSION['roomInfo'] = $_POST['roomTypes'];
			header('Location: payment.php');
		} else {
			$message = "Please select a room type.";
			echo "<script type='text/javascript'>alert('$message');</script>";	
		}
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
        <?php 
			if (!$isStarted) {
				// display warning	
				?>
                <div id="noSearchWarning">
                    <h2><span class="warning">Please search for hotels via the Home Page. </span></h2>
                </div>
                <?php
			} else {
		?>
        
       	    <div id="hotelInfo">
            	<?php 
					// display hotel information
                    $conn = new Connector();
                    $resultSet = queryHotelInformation($conn, $zipcode);
                    $resultSet(
                        $name,
                        $mailingAddress,
                        $rating,
                        $contactNumber,
                        $image);
				?>
                <div>
                <h2><a href="room.php?zipcode=<?php echo $zipcode?>"><?php echo $name ?></a></h2>
                <div id="<?php echo $picID ?>">
                    <img src="calendar/images/disable_date_bg.png" width="100" height="100" align="right" />
                </div>
                <p>Rating:&nbsp;&nbsp;<?php echo $rating ?></p>
                <p>Address:&nbsp;&nbsp;<?php echo $mailingAddress ?></p>
                <p>Contact Number:&nbsp;&nbsp;<?php echo $contactNumber ?></p>
                </div>
                <?php
                    $resultSet($x, $x, $x, $x, $x, false);
                ?>
            </div>
            
            <?php
                $resultSet = queryHotelRooms($conn, $zipcode);
                $n = 0;
                $roomArray = array();
                while ($resultSet($type, $minPrice, $avail)) {
                    $roomArray[$n] = array();
                    $roomArray[$n]['type'] = $type;
                    $roomArray[$n]['minPrice'] = $minPrice;
                    $roomArray[$n]['avail'] = $avail;
                    $roomTypeId = "roomTypeId".$n;
					$roomPicID = "picWrapper".$n;
            ?>
            <div type="roomInfo" id="<?php echo $roomTypeId ?>">
				<h2><?php echo $roomTypes[$n] ?></h2>
                <div id="<?php echo $roomPicID ?>">
                	<img src="calendar/images/disable_date_bg.png" width="100" height="100" align="right" />
                </div>
				<p>Minimum Price: <?php echo $minPrice ?></p>
				<p>Availability: <?php echo $avail ?></p>
            </div>
            <?php
                    $n++;
                }
                $resultSet($x, $x, $x, false);
            ?>
            
            <form id="selectRoom" method="post" action="payment.php">
            	<fieldset id="roomTypes">
                	<h2>
                    <label for="roomTypes">Choose a Type: 
                    <?php if ($missing) { ?>
                      <span class="warning">Please choose a type to make payment.</span>
                    <?php } ?>
                    </label>
                    </h2>
                    <div>
                    	<?php 
							// for each room type, create a checkbox option
							for ($i = 0; $i < $n; $i++) {
                    			$roomTypeId = "roomTypeId".$roomArray[$i]['type'];
						?>
                        <input type="checkbox" name="roomTypes[]" value="<?php echo $roomArray[$i]['type'] ?>" 
                        <?php
        						if (isset($_POST['roomTypes']))
        							if (in_array($roomArray[$i]['type'], $_POST['roomTypes']))
        							  echo 'checked';
						?> id="<?php echo $roomTypeId ?>" />
                        <label for="<?php echo $roomTypeId ?>"><?php echo $roomArray[$i]['type'] ?></label>
                      
                        <?php
                            }
						?>
                    </div>
                </fieldset>
                
                <p>
                	<input name="sendPaymentRequest" id="sendPaymentRequest" type="submit" value="Make Payment">
           		</p>
            </form>
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