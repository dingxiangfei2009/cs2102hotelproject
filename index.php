<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Hotel Booking</title>
<link href="styles/hotel.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div id="header">
    <h1>Hotel Booking </h1>
</div>

<div id="wrapper">
	<?php include('includes/menu.inc.php'); ?>
</div>


<div id="mainContent">
	<h2>Search </h2>
	<form id="search" method="post" action="">
    	<p>
        	<label for="Hotel Name">Hotel Name:</label>
            <input name="Hotel Name" id="Hotel Name" type="text" class="formbox">
        </p>
        <p>
            <label for="Destination">Destination:</label>
            <input name="Destination" id="Destination" type="text" class="formbox">
        </p>
        
        /* leave space for date and time */
        
        <p>
            <input name="sendSearch" id="sendSearch" type="submit" value="Search">
        </p>
        </form>
</div>


<div id="footer">
	<p>&copy; Copyright 2014 Wang YanHao && Ding XiangFei</p>
</div>

</body>

</html>
