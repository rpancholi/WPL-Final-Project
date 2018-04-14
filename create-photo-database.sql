/*
 *    File name: create-photo_database.sql
 *       Author: Solon Pitts
 *    Last Edit: 2018-03-06
 *  Description: This file creates the photo DB for use in the web programming final project
 */

-- Run this script directly in the MySQL server query window.
-- It will automatically create the database and all the database objects.

-- If the database "Event_photo" already exists, then delete it.
DROP DATABASE IF EXISTS Event_photo;
-- Create the Database "event _photo"
CREATE DATABASE Event_photo;

USE event_photo;

DROP TABLE IF EXISTS customer;
CREATE TABLE customer (
  username      VARCHAR(30) NOT NULL,
  password      VARCHAR(255) NOT NULL,
  email      	VARCHAR(255) NOT NULL,
  CONSTRAINT pk_customer PRIMARY KEY (username)
);

DROP TABLE IF EXISTS Photo;
CREATE TABLE photo (
  username      VARCHAR(30) NOT NULL, 
  id			int not null,
  event_name	VARCHAR(30) NOT NULL,
  event_date	DATE NOT NULL,
  selected		BOOLEAN NOT NULL,
  CONSTRAINT pk_photo PRIMARY KEY (id),
  Constraint fk_photo_customer FOREIGN KEY (username) references Customer(username)
);

DROP TABLE IF EXISTS Purchase_summary;
CREATE TABLE Purchase_summary (
  username      VARCHAR(30) NOT NULL, 
  purchase_id	int not null AUTO_INCREMENT,
  description	VARCHAR(30) NOT NULL,
  purchase_date	DATE NOT NULL,
  CONSTRAINT pk_purchase PRIMARY KEY (purchase_id),
  Constraint fk_purchase_customer FOREIGN KEY (username) references Customer(username)
);

DROP TABLE IF EXISTS service;
CREATE TABLE service (
  pic_size     VARCHAR(30) NOT NULL, 
  id		int not null AUTO_INCREMENT,
  pic_backing	VARCHAR(30) NOT NULL,
  pic_frame	VARCHAR(30) NOT NULL,
  CONSTRAINT pk_service PRIMARY KEY (id)
);


-- Insert statements test
INSERT INTO customer VALUES ('Tom','p1','tom@gmail.com');
INSERT INTO customer VALUES ('Bill','p2','bill@gmail.com');
INSERT INTO customer VALUES ('Jerry','p3','jerry@gmail.com');
INSERT INTO customer VALUES ('Fred','p4','fred@gmail.com');

INSERT INTO photo VALUES ('Tom',1,"Birthday","2001/04/10",false);
INSERT INTO photo VALUES ('Tom',5,"Company Party","2002/06/22",false);
INSERT INTO photo VALUES ('Tom',6,"Dance","2002/06/22",false);
INSERT INTO photo VALUES ('Tom',7,"Food Competition","2002/06/22",false);
INSERT INTO photo VALUES ('Tom',8,"Birthday","2001/04/10",false);
INSERT INTO photo VALUES ('Tom',9,"Company Party","2002/06/22",false);
INSERT INTO photo VALUES ('Tom',10,"Birthday","2001/04/10",false);
INSERT INTO photo VALUES ('Tom',11,"Company Party","2002/06/22",false);
INSERT INTO photo VALUES ('Bill',2,"Birthday","2002/06/22",false);
INSERT INTO photo VALUES ('Jerry',3,"Birthday","2002/06/22",false);
INSERT INTO photo VALUES ('Fred',4,"Birthday","2002/06/22",false);

INSERT INTO purchase_summary(username, description, purchase_date) VALUES ('Tom', 'A4, Gold Frame, No Backing', '2002/06/22');

INSERT INTO service(pic_size,pic_backing,pic_frame) VALUES ('A4','Yes','Gold');