/*
 *    File name: create-photo_database.sql
 *       Author: Solon Pitts Rupesh Pancholi
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
  phone      	VARCHAR(255) NOT NULL,
  address      	VARCHAR(255) NOT NULL,
  admin_rights	BOOLEAN NOT NULL,
  CONSTRAINT pk_customer PRIMARY KEY (username)
);

DROP TABLE IF EXISTS Photo;
CREATE TABLE photo (
  username      VARCHAR(30) NOT NULL, 
  id			int not null,
  event_name	VARCHAR(30) NOT NULL,
  event_date	DATE NOT NULL,
  selected		BOOLEAN NOT NULL,
  deleted		BOOLEAN NOT NULL,
  CONSTRAINT pk_photo PRIMARY KEY (id),
  Constraint fk_photo_customer FOREIGN KEY (username) references Customer(username)
);

DROP TABLE IF EXISTS Purchase_summary;
CREATE TABLE Purchase_summary (
  username      VARCHAR(30) NOT NULL, 
  purchase_id	int not null AUTO_INCREMENT,
  description	VARCHAR(255) NOT NULL,
  purchase_date	DATE NOT NULL,
  CONSTRAINT pk_purchase PRIMARY KEY (purchase_id),
  Constraint fk_purchase_customer FOREIGN KEY (username) references Customer(username)
);

DROP TABLE IF EXISTS frames;
CREATE TABLE frames (
  frame_name      VARCHAR(30) NOT NULL, 
  id			int not null AUTO_INCREMENT,
  image_file_name	VARCHAR(30) NOT NULL,
  price DECIMAL(4,2) NOT NULL,
  inventory int not null,
  deleted		BOOLEAN NOT NULL,
  CONSTRAINT pk_frame PRIMARY KEY (id)
);

DROP TABLE IF EXISTS mats;
CREATE TABLE mats (
  mat_name      VARCHAR(30) NOT NULL, 
  id			int not null AUTO_INCREMENT,
  image_file_name	VARCHAR(30) NOT NULL,
  price DECIMAL(4,2) NOT NULL,
  inventory int not null,
  deleted		BOOLEAN NOT NULL,
  CONSTRAINT pk_mat PRIMARY KEY (id)
);

DROP TABLE IF EXISTS sizes;
CREATE TABLE sizes (
  size      VARCHAR(30) NOT NULL, 
  dimensions	VARCHAR(30) NOT NULL,
  price DECIMAL(4,2) NOT NULL,
  deleted		BOOLEAN NOT NULL,
  CONSTRAINT pk_mat PRIMARY KEY (size)
);

-- Insert statements test
INSERT INTO customer VALUES ('Tom','p1','tom@gmail.com','214-100-1000','1000 N Big Rd, Dallas Tx 75001',true);
INSERT INTO customer VALUES ('Bill','p2','bill@gmail.com','214-200-2000','2000 N Big Rd, Dallas Tx 75002',false);
INSERT INTO customer VALUES ('Jerry','p3','jerry@gmail.com','214-300-3000','3000 N Big Rd, Dallas Tx 75003',false);
INSERT INTO customer VALUES ('Fred','p4','fred@gmail.com','214-400-4000','4000 N Big Rd, Dallas Tx 75004',false);
INSERT INTO customer VALUES ('admin','d82494f05d6917ba02f7aaa29689ccb444bb73f20380876cb05d1f37537b7892','admin@gmail.com','214-100-1000','1000 N Admin Rd, Dallas Tx 75001',true);


INSERT INTO photo VALUES ('Tom',1,"Birthday","2001/04/10",false,false);
INSERT INTO photo VALUES ('Tom',5,"Company Party","2002/06/22",false,false);
INSERT INTO photo VALUES ('Tom',6,"Dance","2002/06/22",false,false);
INSERT INTO photo VALUES ('Tom',7,"Food Competition","2002/06/22",false,false);
INSERT INTO photo VALUES ('Tom',8,"Birthday","2001/04/10",false,false);
INSERT INTO photo VALUES ('Tom',9,"Company Party","2002/06/22",false,false);
INSERT INTO photo VALUES ('Tom',10,"Birthday","2001/04/10",false,false);
INSERT INTO photo VALUES ('Tom',11,"Company Party","2002/06/22",false,false);
INSERT INTO photo VALUES ('Bill',2,"Birthday","2002/06/22",false,false);
INSERT INTO photo VALUES ('Jerry',3,"Birthday","2002/06/22",false,false);
INSERT INTO photo VALUES ('Fred',4,"Birthday","2002/06/22",false,false);

INSERT INTO frames(frame_name, image_file_name, price, inventory, deleted) VALUES ('Black Decorated', 'black_rect_thick', "49.99","100",false);
INSERT INTO frames(frame_name, image_file_name, price, inventory, deleted) VALUES ('Black Modern', 'black_rect_thin', "14.99","100",false);
INSERT INTO frames(frame_name, image_file_name, price, inventory, deleted) VALUES ('Black Modern Showcase', 'black_sq_thin', "10.99","100",false);
INSERT INTO frames(frame_name, image_file_name, price, inventory, deleted) VALUES ('Black Modern Impact', 'black_sq_thin_2', "10.99","100",false);
INSERT INTO frames(frame_name, image_file_name, price, inventory, deleted) VALUES ('Bold Gold', 'gold_rect_thick', "39.99","100",false);
INSERT INTO frames(frame_name, image_file_name, price, inventory, deleted) VALUES ('Gold Modern', 'gold_rect_thin', "29.99","100",false);
INSERT INTO frames(frame_name, image_file_name, price, inventory, deleted) VALUES ('Classic White', 'white_rect_thick', "15.99","100",false);
INSERT INTO frames(frame_name, image_file_name, price, inventory, deleted) VALUES ('White Filigree', 'white_round_thick', "24.99","100",false);
INSERT INTO frames(frame_name, image_file_name, price, inventory, deleted) VALUES ('White Modern Showcase', 'white_sq_thick', "17.99","100",false);
INSERT INTO frames(frame_name, image_file_name, price, inventory, deleted) VALUES ('Simple Wood', 'wood_sq_thin', "9.99", "100", false);

INSERT INTO mats(mat_name, image_file_name, price, inventory, deleted) VALUES ('Jet Black', 'black', "1.99","100",false);
INSERT INTO mats(mat_name, image_file_name, price, inventory, deleted) VALUES ('Royal Blue', 'blue', "1.99","100",false);
INSERT INTO mats(mat_name, image_file_name, price, inventory, deleted) VALUES ('Vintage Cream', 'cream', "1.99","100",false);
INSERT INTO mats(mat_name, image_file_name, price, inventory, deleted) VALUES ('Emerald Green', 'green', "1.99","100",false);
INSERT INTO mats(mat_name, image_file_name, price, inventory, deleted) VALUES ('Demure Grey', 'grey', "1.99","100",false);
INSERT INTO mats(mat_name, image_file_name, price, inventory, deleted) VALUES ('Vibrant Red', 'red', "1.99","100",false);
INSERT INTO mats(mat_name, image_file_name, price, inventory, deleted) VALUES ('Classic White', 'white', "1.99","100",false);

INSERT INTO sizes(size, dimensions, price, deleted) VALUES ('A4', '210mm x 297mm', "9.99",false);
INSERT INTO sizes(size, dimensions, price, deleted) VALUES ('A3', '297mm x 420mm', "11.99",false);
INSERT INTO sizes(size, dimensions, price, deleted) VALUES ('A3+', '329mm x 483mm', "12.99",false);
INSERT INTO sizes(size, dimensions, price, deleted) VALUES ('A2', '420mm x 594mm', "15.99",false);