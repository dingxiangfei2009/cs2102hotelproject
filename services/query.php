<?php
require('connect.php');
require('error.php');
/**
 * queryAvailableHotel
 * @param conn, name, location, checkIn, checkOut, offset
 * @return a function such that takes in reference to name, location and zipcode and put
 * in data, then return true for success, false for error or null for end of record
 */
function queryAvailableHotel($conn, $name, $location, $checkIn, $checkOut, $offset = 0) {
	$stmt = $conn->createPreparedStatement(
		'select h.name, h.mailingAddress, h.zipCode, '	// name, address, zipcode
		.'h.rating, h.contactNumber, '	// rating, contactNumber
		.'count(unique r.roomNumber), min(r.price), max(r.price)'	// availability, minPrice, maxPrice
		.'from Hotel h, Room r '
		.'where (r.hotel = h.zipCode) and '
		.'((h.name like ?) or (h.mailingAddress like ?)) '
		.'and not exists ('
			.'select * from MakeBooking b, Contains c '
			.'where (b.id = c.bookingId) '
			.'and (c.zipCode = h.zipCode) '
			.'and (c.roomNumber = r.roomNumber) '
			.'and ((b.checkOutDate > ?) or (b.checkInDate < ?))) '
		.'group by h.zipCode;'
		);
	$stmt->bind('%'.$name.'%', 1) or report($stmt->error);
	$stmt->bind('%'.$location.'%', 2) or report($stmt->error);
	$stmt->bind($checkIn, 3) or report($stmt->error);
	$stmt->bind($checkOut, 4) or report($stmt->error);
	$stmt->execute() or report($stmt->error);
	return function (
		&$name,
		&$mailingAddress,
		&$zipCode,
		&$rating,
		&$contactNumber,
		&$avail,
		&$minPrice,
		&$maxPrice) {

		$stmt->bind_result(
			$name,
			$mailingAddress,
			$zipCode,
			$rating,
			$contactNumber,
			$avail,
			$minPrice,
			$maxPrice
		) or report($stmt->error);
		return $stmt->fetch();
	};
}

function queryHotelRooms($conn, $zipCode) {
	$stmt = $conn->createPreparedStatement('');
}

/**
 * queryHotelLocations
 * @param conn, location, offset and length
 * @return 
 */
function queryHotelLocations($conn, $location, $offset = 0) {
	$stmt = $conn->createPreparedStatement('select * from Hotel h where (h.mailingAddress like ?);');
	$stmt->bind('%'.$location.'%', 1) or report($stmt->error);
	$rs = false;
	$stmt->bind_result($rs);
	$stmt->fetch() or report($stmt->error);
	return $rs;
}

function queryHotelBookings() {
	$stmt = $conn->createPreparedStatement();
}

/**
 * insertBooking
 *
 */
function insertBooking($conn, $room, $hotel, $checkIn, $checkOut) {
	$stmt = $conn->createPreparedStatement('insert into Booking values (?,?,?,?,?,?,?)');
	$stmt->bind(1) or report($stmt->error);
	$rs = false;
	$stmt->bind_result($rs);
	$stmt->fetch();
}
?>