<?php
require_once('connect.php');
/**
 * queryAvailableHotel
 * @param conn, name, location, checkIn, checkOut, offset, order
 * order = 0 for alphabetical, order = 1 for rating
 * @return a function such that takes in reference to name, location and zipcode and put
 * in data, then return true for success, false for error or null for end of record
 */
function queryAvailableHotel($conn, $name, $location, $checkIn, $checkOut, $order = 0, $offset = 0) {
	$query = 'select h.name, h.mailingAddress, h.zipCode,'	// name, address, zipcode
		.'h.rating, h.contactNumber, h.image,'	// rating, contactNumber
		.'count(distinct r.roomNumber) as avail,'
		.'min(r.price) as minPrice, max(r.price) as maxPrice '	// availability, minPrice, maxPrice
		.'from Hotel h, Room r '
		.'where (r.zipCode = h.zipCode) and '
		.'((h.name like ?) or (h.mailingAddress like ?)) '
		.'and not exists ('
			.'select * from MakeBooking b, Contains c '
			.'where (b.id = c.bookingId) '
			.'and (c.zipCode = h.zipCode) '
			.'and (c.roomNumber = r.roomNumber) '
			.'and ((b.checkOutDate > ?) or (b.checkInDate < ?))) '
		.'group by h.zipCode order by ';
	switch ($order) {
		case 0:
			$query .= 'h.name asc';
			break;
		
		case 1:
			$query .= 'h.rating desc';
			break;
	}
	$stmt = $conn->createPreparedStatement($query);
	$query = array(
		'name' => strlen(trim($name)) ? '%'.$name.'%' : '',
		'location' => strlen(trim($location)) ? '%'.$location.'%' : '',
		'checkIn' => $checkIn,
		'checkOut' => $checkOut
	);
	$stmt->bind_param('ssss',
		$query['name'],
		$query['location'],
		$query['checkIn'],
		$query['checkOut']) or report($stmt->error);
	$stmt->execute() or report($stmt->error);
	$eof = false;
	return function (
		&$name,
		&$mailingAddress,
		&$zipCode,
		&$rating,
		&$contactNumber,
		&$image,
		&$avail,
		&$minPrice,
		&$maxPrice,
		$continue = true
		) use (&$stmt, &$eof) {
		if ($eof)
			return null;
		$stmt->bind_result(
			$name,
			$mailingAddress,
			$zipCode,
			$rating,
			$contactNumber,
			$image,
			$avail,
			$minPrice,
			$maxPrice
		) or report($stmt->error);
		$retVal = false;
		if ($continue)
			$retVal = $stmt->fetch();
		if ($retVal)
			return $retVal;
		else {
			$eof = true;
			$stmt->close();
			return $retVal;
		}
	};
}

/**
 * queryHotelInformation
 * @param conn, zipCode
 * conn: connector, zipCode: zip code
 * @return a function of
 * 		@param name, mailingAddress, zipCode, rating
 */
function queryHotelInformation($conn, $zipCode) {
	$stmt = $conn->createPreparedStatement(
		'select h.name, h.mailingAddress,
		h.rating, h.contactNumber, h.image
		from Hotel h
		where h.zipCode = ?');
	if (!$stmt)
		report($conn->getError());
	$stmt->bind_param('i', $zipCode) or report($stmt->error);
	$stmt->execute() or report($stmt->error);
	$eof = false;
	return function (
		&$name,
		&$mailingAddress,
		&$rating,
		&$contactNumber,
		&$image,
		$continue = true
		) use (&$stmt, &$eof) {
		if ($eof)
			return null;
		$stmt->bind_result(
			$name,
			$mailingAddress,
			$rating,
			$contactNumber,
			$image) or report($stmt->error);
		$retVal = false;
		if ($continue)
			$retVal = $stmt->fetch();
		if ($retVal)
			return $retVal;
		else {
			$eof = true;
			$stmt->close();
			return $retVal;
		}
	};
}

/**
 * queryHotelRooms
 */
function queryHotelRooms($conn, $zipCode, $checkInDate, $checkOutDate) {
	$stmt = $conn->createPreparedStatement('
		select r.type, min(r.price) as minPrice,
		count(r.roomNumber) as avail, min(r.image) as image
		from Room r
		where (r.zipCode = ?)
			and not exists (
				select b.id
				from MakeBooking b, Contains c
				where (b.id = c.bookingId)
					and (c.roomNumber = r.roomNumber)
					and (c.zipCode = r.zipCode)
					and ((b.checkOutDate > ?) or (b.checkOutDate < ?))
				)
		group by r.type
		order by minPrice desc
		');
	if (!$stmt)
		report($conn->getError());
	$stmt->bind_param('iss', $zipCode, $checkInDate, $checkOutDate);
	$stmt->execute() or report($stmt->error);
	$eof = false;
	return function (
		&$type,
		&$minPrice,
		&$avail,
		&$image,
		$continue = true
		) use (&$stmt, &$eof) {
		if ($eof)
			return null;
		$stmt->bind_result(
			$type,
			$minPrice,
			$avail,
			$image) or report($stmt->error);
		$retVal = false;
		if ($continue)
			$retVal = $stmt->fetch();
		if ($retVal)
			return $retVal;
		else {
			$eof = true;
			$stmt->close();
			return $retVal;
		}
	};
}

function queryHotelChooseAvailableRoomWithType($conn, $zipCode, $roomType, $checkInDate, $checkOutDate, &$roomNumber, &$price) {
	$stmt = $conn->createPreparedStatement('
		select r.roomNumber, r.price
		from Room r
		where (r.zipCode = ?) and (r.type = ?) and not exists (
			select b.id
			from MakeBooking b, Contains c
			where (b.id = c.bookingId) and (c.zipCode = r.zipCode) and (c.roomNumber = r.roomNumber)
			and ((b.checkOutDate > ?) or (b.checkInDate < ?))
			)
		order by r.price asc limit 1
		');
	if (!$stmt)
		report($conn->getError());
	$stmt->bind_param('isss', $zipCode, $roomType, $checkInDate, $checkOutDate) or report($stmt->error);
	$stmt->execute() or report($stmt->error);
	$roomNumber = null;
	$stmt->bind_result($roomNumber, $price) or report($stmt->error);
	$stmt->fetch();	// just first one
	$stmt->close();
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
function insertBooking($conn, $entry) {
	$id = 0;
	while (true) {
		$id = rand(0, 2147483647);
		$stmt = $conn->createPreparedStatement('select * from MakeBooking where id = ?');
		$stmt->bind_param('i', $id) or report($stmt->error);
		$stmt->execute() or report($stmt->error);
		if ($stmt->fetch())
			$stmt->close();
		else {
			$stmt->close();
			break;
		}
	}
	$stmt = $conn->createPreparedStatement('
		insert into MakeBooking (
			id,emailAddress,checkInDate,checkOutDate,checkInTime,
			checkOutTime,price,paymentMethod,payDate) values (?,?,?,?,?,?,?,?,null)
		');
	$stmt->bind_param('isssssds',
		$id,
		$entry['emailAddress'],
		$entry['checkInDate'],
		$entry['checkOutDate'],
		$entry['checkInTime'],
		$entry['checkOutTime'],
		$entry['price'],
		$entry['paymentMethod']) or report($stmt->error);
	$stmt->execute() or report($stmt->error);
	$stmt->close();
	$stmt = $conn->createPreparedStatement('insert into Contains values (?,?,?)');
	if (!$stmt)
		report($conn->getError());
	$stmt->bind_param('iii',
		$id,
		$entry['roomNumber'],
		$entry['zipCode']
		) or report($stmt->error);
	$stmt->execute() or report($stmt->error);
	$retVal = $conn->affected_rows;
	$stmt->close();
	return $retVal;
}

/**
 * updateBooking
 * @param conn, bookingId, roomNumber, hotel, checkIn, checkOut
 */
function updateBooking($conn, $bookingId, $roomNumber, $hotel, $checkIn, $checkOut) {
	return;
}

function validUser($conn, $email, $pass) {
	$stmt = $conn->createPreparedStatement(
		'select u.name, u.birthday, u.sex, u.contactNumber
		from Customer u
		where (u.email = ?) and (u.pass = ?) limit 1
		');
	if (!$stmt)
		report($conn->getError());
	$stmt->bind_param('ss', $email, $pass) or report($stmt);
	$stmt->execute() or report($stmt->error);
	$retVal = array();
	$stmt->bind_result($retVal['name'], $retVal['birthday'], $retVal['sex'], $retVal['contactNumber'])
		or report($stmt->error);
	if ($stmt->fetch())
		return $retVal;
	else
		return null;
}

function updateUser($conn, $email, $info) {
	$stmt = $conn->createPreparedStatement('
		update Customer
		values contactNumber = ?, name = ?, mailingAddress = ?
		where email = ?
		');
	if (!$stmt)
		report($conn->getError());
	$stmt->bind_param('ssss',
		$info['contactNumber'],
		$info['name'],
		$info['mailingAddress'],
		$email) or report($stmt->error);
	$stmt->execute() or report($stmt->error);
	$retVal = $conn->affected_rows;
	$stmt->close();
	return $retVal;
}
?>