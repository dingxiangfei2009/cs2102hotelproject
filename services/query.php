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
		count(r.roomNumber) as avail
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
		$continue = true
		) use (&$stmt, &$eof) {
		if ($eof)
			return null;
		$stmt->bind_result(
			$type,
			$minPrice,
			$avail) or report($stmt->error);
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
		from Room.r
		where (r.zipCode = ?) and (r.roomType = ?) and not exists (
			select b.id
			from MakeBooking b, Contains c
			where (b.id = c.bookingId) and (c.zipCode = r.zipCode) and (c.roomNumber = r.roomNumber)
			and ((b.checkOutDate > ?) or (b.checkInDate < ?))
			)
		order by r.price asc
		');
	if (!$stmt)
		report($conn->getError());
	$stmt->bind_param() or report($stmt->error);
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
	while (true) {
		$stmt = $conn->createPreparedStatement('select * from MakeBooking where id = ?');
		$stmt->bind_param('i', $id = rand(0, 2147483647));
		$stmt->execute();
		if ($stmt->query())
			$stmt->close();
		else {
			$stmt->close();
			break;
		}
	}
	// var_dump($id);
	$stmt = $conn->createPreparedStatement('
		insert into MakeBooking values (?,?,?,?,?,?,?,?,null)
		');
	$stmt->bind_param('isssssdss',
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
	$stmt = $conn->createPreparedStatement(
		''
		);
	if (!$stmt)
		report($conn->getError());
	return $retVal;
}

/**
 * updateBooking
 * @param conn, bookingId, roomNumber, hotel, checkIn, checkOut
 */
function updateBooking($conn, $bookingId, $roomNumber, $hotel, $checkIn, $checkOut) {
	return;
}
?>