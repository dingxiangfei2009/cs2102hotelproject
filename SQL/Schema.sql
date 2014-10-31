CREATE TABLE Customer (
	emailAddress 	VARCHAR(255) PRIMARY KEY,
	contactNumber 	INT,
	name 			VARCHAR(255) NOT NULL,
	sex				VARCHAR(6) CHECK (sex = 'MALE' or sex = 'FEMALE'),
	mailingAddress 	VARCHAR(255)
);


CREATE TABLE MakeBooking (
	id 				INT  PRIMARY KEY,
	checkInDate 	DATE,
	checkOutDate 	DATE,
	checkInTime 	TIME,
	checkOutTime 	TIME,
	price			INT  NOT NULL,
	paymentMethod 	VARCHAR(255),
	payDate 		DATE
);


CREATE TABLE Hotel (
	zipCode 		INT PRIMARY KEY,
	mailingAddress 	VARCHAR(255),
	rating 			INT,
	contactNumber 	INT,
	name 			VARCHAR(255) NOT NULL
);


CREATE TABLE Room (
	roomNumber 	INT,
	zipCode 	INT,
	type 		VARCHAR(255),
	price 		Numeric NOT NULL,
	FOREIGN KEY (zipCode) REFERENCES hotel(zipCode) ON DELETE CASCADE,
	PRIMARY KEY (roomNumber, zipCode)
);


CREATE TABLE Contains (
	bookingId 	INT,
	roomNumber 	INT,
	zipCode 	INT,
	FOREIGN KEY (bookingId) REFERENCES MakeBooking(id),
	FOREIGN KEY (roomNumber, zipCode) REFERENCES Room(roomNumber, zipCode),
	PRIMARY KEY (bookingId, roomNumber, zipCode)
);