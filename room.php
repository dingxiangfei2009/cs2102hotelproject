<?php
    require_once('services/query.php');
	session_start();
	include('./includes/title.inc.php');
	// mark position of user
	$_SESSION['userPosi'] = 'Location: room.php';
	
	$isStarted = isset($_GET['zipcode']);
	$isVisited = isset($_SESSION['zipcode']);
	if ($isStarted) {
		// variable store the specific hotel been selected by the user
		$zipCode = $_SESSION['zipcode'] = intval($_GET['zipcode']);
	} else if ($isVisited) {
		$zipCode = $_SESSION['zipcode'];
	}
	
	$missing = false;
	$selectRoom = "";
	
	// check if the form has been submitted
    if (isset($_POST['sendPaymentRequest'])) {
		if (empty($_POST['roomType'])) {
			$missing = true;
		}
		
		if (!$missing) {
			if (!$_SESSION['login']) {
				// not logged in
				header('Location: login.php');
			} else {
				// check if check in and check out date are set
				if ($_SESSION['searchInfo']["date1"] == "0000-00-00" || $_SESSION['searchInfo']["date2"] == "0000-00-00") {
					$message = "Please select check in and check out date at home page.";
					echo "<script type='text/javascript'>alert('$message');</script>";
				} else {
					$_SESSION['roomInfo'] = array(
						'roomType' => $_POST['roomType'],
						'zipCode' => $zipCode);
					header('Location: payment.php');
				}
			} 
		} else {
			$message = "Please select a room type.";
			echo "<script type='text/javascript'>alert('$message');</script>";	
		}
	}
	
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
			if (!$isStarted && !$isVisited) {
				// display warning	
				?>
                <div id="noSearchWarning">
                    <h2><span class="warning">Please search for hotels via the Home Page.?></span></h2>
                </div>
                <?php
			} else {
		?>
        
       	    <div id="hotelInfo">
            	<?php 
					// display hotel information
                    $conn = new Connector();
                    $resultSet = queryHotelInformation($conn, $zipCode);
                    $hotelInfo = array();
                    $hotelInfo['zipCode'] = $zipCode;
                    $resultSet(
                        $hotelInfo['name'],
                        $hotelInfo['mailingAddress'],
                        $hotelInfo['rating'],
                        $hotelInfo['contactNumber'],
                        $hotelInfo['image']);
				?>
                <div>
                <h2><a href="room.php?zipcode=<?php echo $zipCode?>"><?php echo $hotelInfo['name'] ?></a></h2>
                <div id="<?php echo $picID ?>">
                    <img src="<?php echo $hotelInfo['image'] ?>" width="150" height="150" align="right" />
                </div>
                <p>Rating:&nbsp;&nbsp;<?php echo $hotelInfo['rating'] ?></p>
                <p>Address:&nbsp;&nbsp;<?php echo $hotelInfo['mailingAddress'] ?></p>
                <p>Contact Number:&nbsp;&nbsp;<?php echo $hotelInfo['contactNumber'] ?></p>
                <p> </p>
                </div>
                <?php
                    $_SESSION['hotelInfo'] = $hotelInfo;
                    $resultSet($x, $x, $x, $x, $x, false);
                ?>
            </div>
            
            <?php
                $checkInDate = $_SESSION['searchInfo']['date1'];
                $checkOutDate = $_SESSION['searchInfo']['date2'];
                $resultSet = queryHotelRooms($conn, $zipCode, $checkInDate, $checkOutDate);
                $n = 0;
                $roomArray = array();
                while ($resultSet($type, $minPrice, $avail, $image)) {
                    $roomArray[$n] = array();
                    $roomArray[$n]['type'] = $type;
                    $roomArray[$n]['minPrice'] = $minPrice;
                    $roomArray[$n]['avail'] = $avail;
                    $roomTypeId = "roomTypeId".$n;
					$roomPicID = "picWrapper".$n;
            ?>
            <div type="roomInfo" id="hotelInfo">
            	<p> </p>
				<h2><?php echo $roomArray[$n]['type'] ?></h2>
                <div id="<?php echo $roomPicID ?>">
                	<img src="<?php echo $image?>" width="100" height="100" align="right" />
                </div>
				<p>Minimum Price: <?php echo $minPrice ?></p>
				<p>Availability: <?php echo $avail ?></p>
                <p> </p>
            </div>
            <?php
                    $n++;
                }
                $resultSet($x, $x, $x, $x, false);
            ?>
            
            <form id="selectRoom" method="post" action="room.php">
            	<fieldset id="roomType">
                	<h2 id="roomTypesTitle">
                    <label for="roomType">Choose a Type: 
                    <?php if ($missing) { ?>
                      <span class="warning">Please choose a type to make payment.</span>
                    <?php } ?>
                    </label>
                    </h2>
                    	<?php 
							// for each room type, create a checkbox option
							for ($i = 0; $i < $n; $i++) {
                    			$roomTypeId = "roomTypeId".$roomArray[$i]['type'];
						?>
                    <div id="selectType">
                        <input type="radio" name="roomType" value="<?php echo $roomArray[$i]['type'] ?>" 
                        <?php
        						if (isset($_POST['roomType']))
        							if ($roomArray[$i]['type'] === $_POST['roomType'])
        							  echo 'checked';
						?> id="<?php echo $roomTypeId ?>" />
                        <label for="<?php echo $roomTypeId ?>"><?php echo $roomArray[$i]['type'] ?></label>
                    </div>
                        <?php
                            }
						?>
                </fieldset>
                
                <p>
                	<input name="sendPaymentRequest" id="sendPaymentRequest" type="submit" value="Make Payment">
           		</p>
                <input type="hidden" name="zipcode" value="<?php echo $zipCode ?>"/>
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