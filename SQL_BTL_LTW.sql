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
isadmin BOOLEAN DEFAULT FALSE,
token varchar(255) DEFAULT NULL,
token_expiration DATETIME DEFAULT NULL
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
content longtext NOT NULL,
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
content text NOT NULL,
PRIMARY KEY (blogid, userid, commentdate)
);

CREATE TABLE QnaEntry (
qnaid int PRIMARY KEY AUTO_INCREMENT
);

CREATE TABLE Message (
msgid int PRIMARY KEY AUTO_INCREMENT,
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
    status varchar(20) DEFAULT 'Unread',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
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

-- Insert initial QnaEntry records
INSERT INTO QnaEntry (qnaid) VALUES
(1),
(2),
(3);

-- Insert improved Messages for entries 1 to 3
INSERT INTO Message (msgid, content, qnaid, userid) VALUES
(1, "How do I deploy a PHP site to a live server?", 1, 4),
(2, "You can use tools like FileZilla or Git to upload your project.", 1, 5),
(3, "What’s the difference between INNER JOIN and LEFT JOIN?", 2, 5),
(4, "INNER JOIN returns matching records only; LEFT JOIN keeps all from the left table.", 2, 6),
(5, "Why won’t my CSS changes reflect after saving?", 3, 6),
(6, "Try clearing the browser cache or using a hard refresh.", 3, 4);

-- Insert random data into the Contact table
INSERT INTO Contact (name, phone, address, customerid) VALUES
('Customer 1', '1234567890', 'Address 1', 4),
('Customer 2', '0987654321', 'Address 2', 5),
('Customer 3', '1122334455', 'Address 3', 6);

-- Insert random data into the Order table
INSERT INTO `Order` (status, totalcost, customerid, contactid) VALUES
('Delivered', 100, 4, 1),
('Delivered', 200, 5, 2),
('Delivered', 300, 6, 3);

INSERT INTO Category (name) VALUES
('Cake'),
('Cookie'),
('Pie'),
('Tart'),
('Muffin');


INSERT INTO Manufacturer (name, country) VALUES
('Sweet Treats Co.', 'USA'),
("Baker\'s Delight", 'France'),
('Golden Crust', 'Italy');


INSERT INTO Product (name, price, description, avgrating, bought, mfgid, stock, cateid) VALUES
    ('Chocolate Cake', 150000, 'Rich chocolate layer cake', 4.6, 120, 1, 'In Stock', 1),
    ('Vanilla Cake', 140000, 'Classic vanilla sponge cake', 4.3, 95, 1, 'In Stock', 1),
    ('Strawberry Shortcake', 170000, 'Fresh strawberries and cream', 4.8, 80, 2, 'In Stock', 1),
    ('Oatmeal Cookie', 50000, 'Crunchy and healthy', 4.2, 130, 2, 'In Stock', 2),
    ('Chocolate Chip Cookie', 60000, 'Soft with gooey chips', 4.7, 200, 1, 'In Stock', 2),
    ('Butter Cookie', 45000, 'Melts in your mouth', 4.0, 70, 3, 'In Stock', 2),
    ('Apple Pie', 180000, 'Classic apple cinnamon pie', 4.4, 90, 3, 'In Stock', 3),
    ('Cherry Pie', 185000, 'Tart cherry filling', 4.1, 65, 2, 'In Stock', 3),
    ('Pumpkin Pie', 175000, 'Spiced pumpkin flavor', 4.5, 110, 1, 'In Stock', 3),
    ('Lemon Tart', 160000, 'Tangy and sweet', 4.6, 75, 1, 'In Stock', 4),
    ('Chocolate Tart', 165000, 'Dark chocolate ganache', 4.9, 88, 2, 'In Stock', 4),
    ('Fruit Tart', 170000, 'Fresh fruit and custard', 4.2, 50, 3, 'In Stock', 4),
    ('Blueberry Muffin', 80000, 'Soft muffin with blueberries', 4.0, 45, 2, 'In Stock', 5),
    ('Banana Muffin', 75000, 'Banana flavored muffin', 3.8, 35, 1, 'In Stock', 5),
    ('Chocolate Muffin', 85000, 'Rich chocolate muffin', 4.3, 70, 1, 'In Stock', 5),
    ('Raspberry Tart', 175000, 'Tart with fresh raspberries', 4.1, 30, 2, 'In Stock', 4),
    ('Mini Cheesecake', 120000, 'Creamy and sweet', 4.4, 55, 3, 'In Stock', 1),
    ('Peanut Butter Cookie', 60000, 'Rich peanut butter flavor', 4.5, 80, 2, 'In Stock', 2),
    ('Carrot Cake', 155000, 'Moist and spiced', 4.2, 65, 3, 'In Stock', 1),
    ('Molten Lava Cake', 180000, 'Warm chocolate center', 4.9, 95, 1, 'In Stock', 1);

INSERT INTO `Order` (status, totalcost, orderdate, customerid, contactid) VALUES
('Delivered', 200000, '2025-05-01', 4, 1),
('Delivered', 320000, '2025-05-02', 5, 2),
('Delivered', 450000, '2025-05-03', 6, 3),
('Delivered', 190000, '2025-05-04', 4, 1),
('Delivered', 210000, '2025-05-05', 5, 2),
('Delivered', 310000, '2025-05-06', 6, 3),
('Delivered', 275000, '2025-05-07', 4, 1),
('Delivered', 290000, '2025-05-08', 5, 2),
('Delivered', 230000, '2025-05-09', 6, 3),
('Delivered', 350000, '2025-05-10', 4, 1);


INSERT INTO HasProduct (orderid, productid, amount) VALUES
(1, 1, 1), (1, 4, 2),
(2, 2, 1), (2, 5, 1), (2, 6, 1),
(3, 3, 1), (3, 7, 1),
(4, 8, 2), (4, 9, 1),
(5, 10, 1), (5, 11, 1),
(6, 12, 1), (6, 13, 2),
(7, 14, 2), (7, 15, 1),
(8, 16, 1), (8, 17, 2),
(9, 18, 1), (9, 19, 1),
(10, 20, 2), (10, 1, 1);



INSERT INTO RateProduct (orderid, productid, rating, comment) VALUES
(1, 1, 5, 'Delicious!'),
(2, 2, 4, 'Tasty but a bit dry.'),
(3, 3, 5, 'Excellent dessert!'),
(4, 8, 3, 'Too sour.'),
(5, 10, 4, 'Tangy and fresh.'),
(6, 13, 5, 'Loved the blueberries.'),
(7, 14, 4, 'Rich chocolate flavor.'),
(8, 16, 5, 'Perfect portion size.'),
(9, 18, 4, 'Well-spiced.'),
(10, 20, 5, 'Heavenly lava cake!');

-- Insert meaningful FAQ entries
INSERT INTO FaqEntry (answer, question) VALUES
('You can reset your password by clicking "Forgot Password" on the login page and following the instructions.', 'How do I reset my password?'),
('Check your spam folder first. If it’s not there, ensure you entered the correct email or try resending the verification email.', 'I didn’t receive a verification email. What should I do?'),
('Navigate to Settings > Profile, then click "Edit" to update your personal information.', 'How can I update my profile information?'),
('Our platform supports JPG, PNG, and PDF files. Each file must be under 10MB.', 'What file types are supported for uploads?'),
('You can contact support through the Help Center or by emailing support@example.com.', 'How do I contact customer support?'),
('Yes, your data is encrypted in transit and at rest. We follow industry-standard security practices.', 'Is my data secure on your platform?'),
('Go to the dashboard and click "Create New Project" to start building your first project.', 'How do I start a new project?'),
('You can delete your account by navigating to Account Settings and clicking "Delete Account" at the bottom.', 'How do I delete my account permanently?'),
('We support all modern browsers including Chrome, Firefox, Safari, and Edge. Internet Explorer is not supported.', 'Which browsers are supported?'),
('Make sure JavaScript is enabled and that your browser is up to date. Clear cache if the issue persists.', 'Why isn’t the site loading correctly?');


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
