DROP DATABASE IF EXISTS BTL_LTW;

CREATE DATABASE BTL_LTW
CHARACTER SET utf8
COLLATE utf8_unicode_ci;

USE BTL_LTW;

CREATE TABLE User (
userid int AUTO_INCREMENT PRIMARY KEY,
username varchar(255) NOT NULL,
password varchar(100) NOT NULL,
joindate DATETIME NOT NULL DEFAULT current_timestamp(),
email varchar(255) NOT NULL UNIQUE,
isadmin BOOLEAN DEFAULT FALSE
);

CREATE TABLE Admin (
userid int PRIMARY KEY,
CONSTRAINT refadminid_admin FOREIGN KEY (userid) REFERENCES User(userid) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE Customer (
userid int PRIMARY KEY,
avatarurl varchar(255) NOT NULL DEFAULT 'assets/img/avatar_male.png',
CONSTRAINT refcustomerid_customer FOREIGN KEY (userid) REFERENCES User(userid) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE BlogPost (
blogid int PRIMARY KEY AUTO_INCREMENT,
adminid int NOT NULL,
postdate DATE DEFAULT CURRENT_DATE,
content varchar(255) NOT NULL,
title varchar(255) NOT NULL,
CONSTRAINT refadminid_blogpost FOREIGN KEY (adminid) REFERENCES Admin(userid) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE `Like` (
blogid int,
userid int,
CONSTRAINT refblogid_like FOREIGN KEY (blogid) REFERENCES BlogPost(blogid) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT refuserid_like FOREIGN KEY (userid) REFERENCES User(userid) ON DELETE CASCADE ON UPDATE CASCADE,
PRIMARY KEY (blogid, userid)
);

CREATE TABLE BlogComment (
blogid int,
userid int,
CONSTRAINT refblogid_blogcomment FOREIGN KEY (blogid) REFERENCES BlogPost(blogid) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT refuserid_blogcomment FOREIGN KEY (userid) REFERENCES User(userid) ON DELETE CASCADE ON UPDATE CASCADE,
commentdate DATE DEFAULT CURRENT_DATE,
content varchar(255) NOT NULL,
PRIMARY KEY (blogid, userid, commentdate)
);

CREATE TABLE QnaEntry (
qnaid int PRIMARY KEY
);

CREATE TABLE Message (
msgid int PRIMARY KEY,
senddate date DEFAULT CURRENT_DATE,
content varchar(255) NOT NULL,
qnaid int NOT NULL,
userid int,
CONSTRAINT refqnaid_msg FOREIGN KEY (qnaid) REFERENCES QnaEntry(qnaid) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT refuserid_msg FOREIGN KEY (userid) REFERENCES User(userid) ON DELETE SET NULL ON UPDATE CASCADE
);

CREATE TABLE Contact (
contactid int PRIMARY KEY AUTO_INCREMENT,
name varchar(255) NOT NULL,
phone varchar(12),
address varchar(255),
customerid int NOT NULL,
CONSTRAINT refcustomerid_contact FOREIGN KEY (customerid) REFERENCES Customer(userid) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE `Order` (
orderid int AUTO_INCREMENT PRIMARY KEY,
status varchar(255),
totalcost int,
orderdate date DEFAULT CURRENT_DATE,
customerid int,
contactid int,
CONSTRAINT refcustomerid_order FOREIGN KEY (customerid) REFERENCES Customer(userid) ON DELETE SET NULL ON UPDATE CASCADE,
CONSTRAINT refcontactid_order FOREIGN KEY (contactid) REFERENCES Contact(contactid) ON DELETE SET NULL ON UPDATE CASCADE
);

CREATE TABLE Category (
cateid int PRIMARY KEY AUTO_INCREMENT,
name varchar(255) NOT NULL
);

CREATE TABLE Manufacturer (
mfgid int PRIMARY KEY AUTO_INCREMENT,
name varchar(255) NOT NULL,
country varchar(50) NOT NULL
);

CREATE TABLE Product (
productid int PRIMARY KEY AUTO_INCREMENT,
name varchar(255) NOT NULL,
price int,
description varchar(255),
avgrating float(2,1),
bought int,
mfgid int NOT NULL,
stock varchar(255) NOT NULL,
cateid int NOT NULL,
avatarurl varchar(255) NOT NULL,
CONSTRAINT refcategoryid_product FOREIGN KEY (cateid) REFERENCES Category(cateid) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT refmfgid_product FOREIGN KEY (mfgid) REFERENCES Manufacturer(mfgid) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE HasProduct (
orderid int,
productid int,
amount int NOT NULL,
PRIMARY KEY(orderid, productid),
CONSTRAINT reforderid_hasproduct FOREIGN KEY (orderid) REFERENCES `Order`(orderid) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT refproductid_hasproduct FOREIGN KEY (productid) REFERENCES Product(productid) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE RateProduct (
orderid int,
productid int,
rating int NOT NULL,
comment varchar(255) NOT NULL,
ratingdate date DEFAULT CURRENT_DATE,
PRIMARY KEY(orderid, productid),
CONSTRAINT reforderid_rateproduct FOREIGN KEY (orderid) REFERENCES `Order`(orderid) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT refproductid_rateproduct FOREIGN KEY (productid) REFERENCES Product(productid) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE FaqEntry (
faqid int PRIMARY KEY AUTO_INCREMENT,
answer varchar(255) NOT NULL,
question varchar(255) NOT NULL
);

CREATE TABLE ContactMessage(
    contactid int PRIMARY KEY AUTO_INCREMENT,
    name varchar(50) NOT NULL,
    email varchar(255) NOT NULL,
    title varchar(255),
    message varchar(255) NOT NULL,
    status varchar(20) DEFAULT 'Unread'
);

-- Password1
-- Password2
-- Password3
-- ...
INSERT INTO User (username, password, email, isadmin) VALUES
('admin1', '$2y$10$dqxqb8t4hgUfUhXnq6gBbe0IlVtQ9vIYfRRurXkz2NSUF3pDYB3oS', 'admin1@example.com', TRUE),
('admin2', '$2y$10$IOtBfhEgTICPChw3Rs2.W.UoNdDaHKGsr2wjjLqmK.yoDG8RTc11m', 'admin2@example.com', TRUE),
('admin3', '$2y$10$GNUjqZhP0gGXgpj7fV9neu8phGHbybnsNnnl1cqN4ThUu6M/4fiUS', 'admin3@example.com', TRUE),
('user1', '$2y$10$1KFonwKOW.lcabeyAMMuheqgwIbFkgkluHTixmPmsTS1fTd77orbW', 'user1@example.com', FALSE),
('user2', '$2y$10$QHlDCCxt5iRKJW7xaAaJiOoNOzL8CYjseBLdhTVx06pN7KUIgxFTG', 'user2@example.com', FALSE),
('user3', '$2y$10$qteyJua6GJOx2QV.xgM3J.0g4PCneV8CeNqeiUuPwFBdSiMtjQHHa', 'user3@example.com', FALSE),
('user4', '$2y$10$fykafLPbiquUcNgc2w8kQuiIqnF.3u0PfbkyszDUk5wEQQfRRDTOy', 'user4@example.com', FALSE),
('user5', '$2y$10$1bnvsyeByF9DS8/NoGluA.z47D5vghWzZGUH9t7h6jdH9ZqlHcKsC', 'user5@example.com', FALSE),
('user6', '$2y$10$GKKrlr6mGErGEg2dUTUYweZ536BBUzne5rifj3lqohBwi3FijqtP2', 'user6@example.com', FALSE),
('user7', '$2y$10$jK51kdGlA.pntVFK.wNsb.X0kjsF0v7FYBw..gTBpaBCyXY1s70bi', 'user7@example.com', FALSE);

-- Insert admins into the Admin table
INSERT INTO Admin (userid)
SELECT userid FROM User WHERE isadmin = TRUE;

-- Insert customers into the Customer table
INSERT INTO Customer (userid)
SELECT userid
FROM User WHERE isadmin = FALSE;

-- Insert random data into the BlogPost table
INSERT INTO BlogPost (adminid, content, title) VALUES
(1, 'Content for blog post 1', 'Title 1'),
(2, 'Content for blog post 2', 'Title 2'),
(3, 'Content for blog post 3', 'Title 3');

-- Insert random data into the Like table
INSERT INTO `Like` (blogid, userid) VALUES
(1, 4),
(1, 5),
(2, 6),
(3, 7);

-- Insert random data into the BlogComment table
INSERT INTO BlogComment (blogid, userid, content) VALUES
(1, 4, 'Comment 1 on blog post 1'),
(1, 5, 'Comment 2 on blog post 1'),
(2, 6, 'Comment 1 on blog post 2'),
(3, 7, 'Comment 1 on blog post 3');

-- Insert random data into the QnaEntry table
INSERT INTO QnaEntry (qnaid) VALUES
(1),
(2),
(3);

-- Insert random data into the Message table
INSERT INTO Message (msgid, content, qnaid, userid) VALUES
(1, "Message 1", 1, 4),
(2, "Message 2", 2, 5),
(3, "Message 3", 3, 6);

-- Insert random data into the Contact table
INSERT INTO Contact (name, phone, address, customerid) VALUES
('Customer 1', '1234567890', 'Address 1', 4),
('Customer 2', '0987654321', 'Address 2', 5),
('Customer 3', '1122334455', 'Address 3', 6);

-- Insert random data into the Order table
INSERT INTO `Order` (status, totalcost, customerid, contactid) VALUES
('Pending', 100, 4, 1),
('Shipped', 200, 5, 2),
('Delivered', 300, 6, 3);

-- Insert random data into the Category table
INSERT INTO Category (name) VALUES
('Category 1'),
('Category 2'),
('Category 3');

-- Insert random data into the Manufacturer table
INSERT INTO Manufacturer (name, country) VALUES
('Manufacturer 1', 'Country 1'),
('Manufacturer 2', 'Country 2'),
('Manufacturer 3', 'Country 3');

-- Insert random data into the Product table
INSERT INTO Product (name, price, description, avgrating, bought, mfgid, stock, cateid) VALUES
('Product 1', 10, 'Description 1', 4.5, 100, 1, 'In Stock', 1),
('Product 2', 20, 'Description 2', 4.0, 200, 2, 'In Stock', 2),
('Product 3', 30, 'Description 3', 3.5, 300, 3, 'In Stock', 3);

-- Insert random data into the HasProduct table
INSERT INTO HasProduct (orderid, productid, amount) VALUES
(1, 1, 2),
(2, 2, 3),
(3, 3, 1);

-- Insert random data into the RateProduct table
INSERT INTO RateProduct (orderid, productid, rating, comment) VALUES
(1, 1, 5, 'Great product!'),
(2, 2, 4, 'Good product!'),
(3, 3, 3, 'Average product.');

-- Insert random data into the FaqEntry table
INSERT INTO FaqEntry (answer, question) VALUES
('Answer 1', 'Question 1'),
('Answer 2', 'Question 2'),
('Answer 3', 'Question 3');

INSERT INTO ContactMessage (name, email, title, message, status) VALUES
('Alice Smith', 'alice.smith@example.com', 'Inquiry', 'I would like to know more about your services.', 'Unread'),
('John Doe', 'john.doe@example.com', 'Support Request', 'Having trouble logging into my account.', 'Unread'),
('Emma Brown', 'emma.brown@example.com', 'Feedback', 'Great experience with your website!', 'Read'),
('Michael Lee', 'michael.lee@example.com', 'Bug Report', 'Found a bug on the checkout page.', 'Unread'),
('Sophia Wilson', 'sophia.wilson@example.com', 'Partnership', 'Interested in collaborating with your company.', 'Read'),
('Daniel Martinez', 'daniel.martinez@example.com', 'Complaint', 'My order has not arrived yet.', 'Unread'),
('Olivia Taylor', 'olivia.taylor@example.com', 'General Question', 'Do you ship internationally?', 'Unread'),
('Liam Johnson', 'liam.johnson@example.com', 'Feature Request', 'Would love to see a dark mode option.', 'Read'),
('Emily Davis', 'emily.davis@example.com', 'Account Help', 'I forgot my password, need assistance.', 'Unread'),
('Noah White', 'noah.white@example.com', 'Other', 'Just saying hi, love the content!', 'Read');
