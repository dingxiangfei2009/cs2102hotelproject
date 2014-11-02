<?php 
	session_start();
	include('./includes/title.inc.php');
	
	$_SESSION['paymentInfo']
	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Hotel Booking</title>
</head>

<body>
	<div id="header">
        <h1>Receipt</h1>
    </div>
    
    <div id="wrapper">
    	<?php 
			// insert the almost same form with same format as the one in payment page but need to include one more attribute: booking ID
			// later just copy paste with the one in payment page
		?>
    </div>
    
</body>
</html>