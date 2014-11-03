CREATE TABLE Customer (
	emailAddress 	VARCHAR(255) PRIMARY KEY,
	contactNumber 	INT,
	name 			VARCHAR(255) NOT NULL,
	sex				VARCHAR(6) CHECK (sex = 'MALE' or sex = 'FEMALE'),
	mailingAddress 	VARCHAR(255)
);


CREATE TABLE MakeBooking (
	id 				INT  PRIMARY KEY,
	emailAddress	VARCHAR(255),
	checkInDate 	DATE,
	checkOutDate 	DATE CHECK (checkInDate < checkOutDate),
	checkInTime 	TIME,
	checkOutTime 	TIME,
	price			NUMERIC  NOT NULL,
	paymentMethod 	VARCHAR(255),
	payDate 		DATE,
	FOREIGN KEY (emailAddress) REFERENCES Customer(emailAddress) ON DELETE CASCADE
);


CREATE TABLE Hotel (
	zipCode 		INT PRIMARY KEY,
	mailingAddress 	VARCHAR(255),
	rating 			NUMERIC,
	contactNumber 	INT,
	name 			VARCHAR(255) NOT NULL,
	image			VARCHAR(255)
);


CREATE TABLE Room (
	roomNumber 	INT,
	zipCode 	INT,
	type 		VARCHAR(255),
	price 		Numeric NOT NULL,
	image		VARCHAR(255)
	FOREIGN KEY (zipCode) REFERENCES Hotel(zipCode) ON DELETE CASCADE,
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
