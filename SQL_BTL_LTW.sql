CREATE DATABASE BTL_LTW
	CHARACTER SET utf8
  	COLLATE utf8_unicode_ci;
    
use BTL_LTW;

CREATE TABLE Users (
	UserID int AUTO_INCREMENT PRIMARY KEY,
    Username varchar(255) NOT NULL UNIQUE,
    Password varchar(100) not null,
    JoinDate DATETIME not null default current_timestamp(),
    Email varchar(255) not null unique, 
    isAdmin BOOLEAN DEFAULT FALSE 
);
create table Admin (
	UserID int PRIMARY KEY,
    constraint refAdminID_Admin FOREIGN KEY (UserID) REFERENCES Users(UserID) on DELETE CASCADE on UPDATE CASCADE
);
create table Customer (
	UserID int PRIMARY KEY,
    AvatarURL varchar(255) not null,
    constraint refCustomerID_Customer FOREIGN KEY (UserID) REFERENCES Users(UserID) on DELETE CASCADE on UPDATE CASCADE
);
create table BlogPost (
	BlogID int PRIMARY KEY AUTO_INCREMENT,
    AdminID int not null,
    PostDate DATE DEFAULT CURRENT_DATE,
    Content varchar(255) not null,
    Title varchar(255) not null,
    constraint refAdminID_BlogPost FOREIGN KEY (AdminID) REFERENCES Admin(UserID) on DELETE CASCADE on UPDATE CASCADE
);
create table Likes (
	BlogID int,
    UserID int,
    constraint refBlogID_Likes FOREIGN KEY (BlogID) REFERENCES BlogPost(BlogID) on DELETE CASCADE on UPDATE CASCADE,
    constraint refUserID_Likes FOREIGN KEY (UserID) REFERENCES Users(UserID) on DELETE CASCADE on UPDATE CASCADE,
	PRIMARY KEY (BlogID, UserID)
);
create table BlogComment (
	BlogID int,
    UserID int,
    constraint refBlogID_BlogComment FOREIGN KEY (BlogID) REFERENCES BlogPost(BlogID) on DELETE CASCADE on UPDATE CASCADE,
    constraint refUserID_BlogComment FOREIGN KEY (UserID) REFERENCES Users(UserID) on DELETE CASCADE on UPDATE CASCADE,
    CommentDate DATE DEFAULT CURRENT_DATE,
    Content varchar(255) not null,
    PRIMARY KEY (BlogID, UserID, CommentDate)
);
create table QnAEntry (
	QnAID int PRIMARY KEY
);
create table msg (
	msgID int PRIMARY KEY,
    sendDate date DEFAULT CURRENT_DATE,
    QnAID int not null,
    UserID int,
    constraint refQnAID_msg FOREIGN KEY (QnAID) REFERENCES QnAEntry(QnAID) on DELETE CASCADE on UPDATE CASCADE,
    constraint refUserID_msg FOREIGN KEY (UserID) REFERENCES Users(UserID) on DELETE SET NULL on UPDATE CASCADE
);
create table Contact (
	contactID int PRIMARY KEY AUTO_INCREMENT,
    name varchar(255) not null,
    phone varchar(12),
    address varchar(255),
    CustomerID int not null,
    constraint refCustomerID_Contact FOREIGN KEY (CustomerID) REFERENCES Customer(UserID) on DELETE CASCADE on UPDATE CASCADE
);
create table Orders (
	orderID int AUTO_INCREMENT PRIMARY KEY,
    status varchar(255),
    totalCost int,
    orderDate date DEFAULT CURRENT_DATE,
    customerID int,
    contactID int,
    constraint refCustomerID_Order FOREIGN KEY (customerID) REFERENCES Customer(userID) on DELETE SET NULL on UPDATE CASCADE,
    constraint refContactID_Order FOREIGN KEY (contactID) REFERENCES Contact(contactID) on DELETE SET NULL on UPDATE CASCADE
);
create table Category (
	cateID int PRIMARY KEY AUTO_INCREMENT,
    name varchar(255) not null
);
create table Manufacturer (
	mfgID int PRIMARY KEY AUTO_INCREMENT,
    name varchar(255) not null,
    country varchar(50) not null
);
create table Product (
	productID int PRIMARY KEY AUTO_INCREMENT,
    name varchar(255) not null,
    price int,
    description varchar(255),
    avgRating float(2,1),
    bought int,
    mfgID int not null,
    stock varchar(255) not null,
    cateID int not null,
    constraint refCategoryID_Product FOREIGN KEY (cateID) REFERENCES Category(cateID) on DELETE CASCADE on UPDATE CASCADE,
    constraint refMfgID_Product FOREIGN KEY (mfgID) REFERENCES Manufacturer(mfgID) on DELETE CASCADE on UPDATE CASCADE
);
CREATE TABLE HasProduct(
	orderID int,
    productID int, 
    amount int not null,
    PRIMARY KEY(orderID, productID),
    constraint refOrderID_HasProduct FOREIGN KEY (orderID) REFERENCES Orders(orderID) on DELETE CASCADE on UPDATE CASCADE,
    constraint refProductID_HasProduct FOREIGN KEY (productID) REFERENCES Product(productID) on DELETE CASCADE on UPDATE CASCADE
);
CREATE TABLE RateProduct(
	orderID int,
    productID int, 
    rating int not null,
    comment varchar(255) not null,
    ratingDate date DEFAULT CURRENT_DATE,
    PRIMARY KEY(orderID, productID),
    constraint refOrderID_RateProduct FOREIGN KEY (orderID) REFERENCES Orders(orderID) on DELETE CASCADE on UPDATE CASCADE,
    constraint refProductID_RateProduct FOREIGN KEY (productID) REFERENCES Product(productID) on DELETE CASCADE on UPDATE CASCADE
);
create table FAQEntry (
	faqID int PRIMARY KEY AUTO_INCREMENT,
    answer varchar(255) not null,
    question varchar(255) not null
);

INSERT INTO Users (Username, Password, Email, isAdmin) VALUES
('admin1', 'hashedpassword1', 'admin1@example.com', TRUE),
('admin2', 'hashedpassword2', 'admin2@example.com', TRUE),
('admin3', 'hashedpassword3', 'admin3@example.com', TRUE),
('user1', 'hashedpassword4', 'user1@example.com', FALSE),
('user2', 'hashedpassword5', 'user2@example.com', FALSE),
('user3', 'hashedpassword6', 'user3@example.com', FALSE),
('user4', 'hashedpassword7', 'user4@example.com', FALSE),
('user5', 'hashedpassword8', 'user5@example.com', FALSE),
('user6', 'hashedpassword9', 'user6@example.com', FALSE),
('user7', 'hashedpassword10', 'user7@example.com', FALSE);

-- Insert admins into the Admin table
INSERT INTO Admin (UserID)
SELECT UserID FROM Users WHERE isAdmin = TRUE;

-- Insert customers into the Customer table
INSERT INTO Customer (UserID, AvatarURL)
SELECT UserID, CONCAT('https://example.com/avatars/', Username, '.png')
FROM Users WHERE isAdmin = FALSE;