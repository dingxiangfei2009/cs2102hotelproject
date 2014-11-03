<?php
	session_start();
	include('./includes/title.inc.php');
	// mark position of user
	$_SESSION['userPosi'] = 'Location: room.php';
	
	$isStarted = isset($_GET['hotelname']);
	if ($isStarted) {
		// variable store the specific hotel been selected by the user
		$hotelName = $_GET['hotelname'];
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
				?>
            </div>
            
            <?php 
                // pass the number of rooms to be displayed
                $numberOfRoomTypes = 3;
				// array containing all room types name to be displayed
				$roomTypes = array("dummy_type_A", "dummy_type_B", "dummy_type_C");
                for ($i=0; $i<$numberOfRoomTypes; $i++) {
                    $roomTypeId = "roomTypeId".$i;
					$roomPicID = "picWrapper".$i;
                    ?>
                        <div type="roomInfo" id=<?php echo $roomTypeId;?>>
                            <?php // dummy model for now, need to get room info from SQL 
							?>
							<h2><?php echo $roomTypes[$i];?></h2>
                            <div id=<?php echo $roomPicID ?>>
                            	<img src="calendar/images/disable_date_bg.png" width="100" height="100" align="right" />
                            </div>
							<p>Price: </p>
							<p>Availability: </p>
							<p>Contact Number:</p>
                        </div>
                    <?php	
                }
            ?>
            
            <form id="selectRoom" method="post" action="">
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
							for ($i=0; $i<$numberOfRoomTypes; $i++) {
                    			$roomTypeId = "roomTypeId".$i;
								?>
                        <input type="checkbox" name="roomTypes[]" value=<?php echo $roomTypes[$i];?> 
                                        id=<?php echo $roomTypeId;?> 
                                        <?php
										if (isset($_POST['roomTypes'])){
											if (in_array($roomTypes[$i], $_POST['roomTypes'])) {
											  echo 'checked';
											} 
										}?>
                                        >
                        <label for=<?php echo $roomTypeId;?>><?php echo $roomTypes[$i];?></label>
                      
                           <?php }
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
	<p>&copy; Copyright 2014 Wang YanHao && Ding XiangFei</p>
	</div>
</body>
</html>