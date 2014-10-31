<?php
$conn = new Connector();
$stream = queryAvailableHotel($conn, $name, $location, $checkIn, $checkOut);
while ($stream($name, $mailingAddress, $zipCode)) {
?>
<div><?$name?></div>
<div><?$
<?php
}
?>