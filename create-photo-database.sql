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
  CONSTRAINT pk_photo PRIMARY KEY (id),
  Constraint fk_photo_customer FOREIGN KEY (username) references Customer(username)
);

-- Insert statements test
INSERT INTO customer VALUES ('Tom','p1','tom@gmail.com');
INSERT INTO customer VALUES ('Bill','p2','bill@gmail.com');
INSERT INTO customer VALUES ('Jerry','p3','jerry@gmail.com');
INSERT INTO customer VALUES ('Fred','p4','fred@gmail.com');

INSERT INTO photo VALUES ('Tom',1);
INSERT INTO photo VALUES ('Tom',5);
INSERT INTO photo VALUES ('Tom',6);
INSERT INTO photo VALUES ('Tom',7);
INSERT INTO photo VALUES ('Bill',2);
INSERT INTO photo VALUES ('Jerry',3);
INSERT INTO photo VALUES ('Fred',4);