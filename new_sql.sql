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

CREATE TABLE ContactMessage (
    contactid int PRIMARY KEY AUTO_INCREMENT,
    name varchar(50) NOT NULL,
    email varchar(255) NOT NULL,
    title varchar(255),
    message varchar(255) NOT NULL,
    status varchar(20) DEFAULT 'Unread',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert Users (8 admins, 30 customers)
INSERT INTO User (username, password, email, isadmin) VALUES
('admin1', '$2y$10$dqxqb8t4hgUfUhXnq6gBbe0IlVtQ9vIYfRRurXkz2NSUF3pDYB3oS', 'admin1@candyshop.com', TRUE),
('admin2', '$2y$10$IOtBfhEgTICPChw3Rs2.W.UoNdDaHKGsr2wjjLqmK.yoDG8RTc11m', 'admin2@candyshop.com', TRUE),
('admin3', '$2y$10$GNUjqZhP0gGXgpj7fV9neu8phGHbybnsNnnl1cqN4ThUu6M/4fiUS', 'admin3@candyshop.com', TRUE),
('admin4', '$2y$10$jK51kdGlA.pntVFK.wNsb.X0kjsF0v7FYBw..gTBpaBCyXY1s70bi', 'admin4@candyshop.com', TRUE),
('admin5', '$2y$10$1bnvsyeByF9DS8/NoGluA.z47D5vghWzZGUH9t7h6jdH9ZqlHcKsC', 'admin5@candyshop.com', TRUE),
('admin6', '$2y$10$GKKrlr6mGErGEg2dUTUYweZ536BBUzne5rifj3lqohBwi3FijqtP2', 'admin6@candyshop.com', TRUE),
('admin7', '$2y$10$fykafLPbiquUcNgc2w8kQuiIqnF.3u0PfbkyszDUk5wEQQfRRDTOy', 'admin7@candyshop.com', TRUE),
('admin8', '$2y$10$qteyJua6GJOx2QV.xgM3J.0g4PCneV8CeNqeiUuPwFBdSiMtjQHHa', 'admin8@candyshop.com', TRUE),
('user1', '$2y$10$1KFonwKOW.lcabeyAMMuheqgwIbFkgkluHTixmPmsTS1fTd77orbW', 'user1@candyshop.com', FALSE),
('user2', '$2y$10$QHlDCCxt5iRKJW7xaAaJiOoNOzL8CYjseBLdhTVx06pN7KUIgxFTG', 'user2@candyshop.com', FALSE),
('user3', '$2y$10$qteyJua6GJOx2QV.xgM3J.0g4PCneV8CeNqeiUuPwFBdSiMtjQHHa', 'user3@candyshop.com', FALSE),
('user4', '$2y$10$fykafLPbiquUcNgc2w8kQuiIqnF.3u0PfbkyszDUk5wEQQfRRDTOy', 'user4@candyshop.com', FALSE),
('user5', '$2y$10$1bnvsyeByF9DS8/NoGluA.z47D5vghWzZGUH9t7h6jdH9ZqlHcKsC', 'user5@candyshop.com', FALSE),
('user6', '$2y$10$GKKrlr6mGErGEg2dUTUYweZ536BBUzne5rifj3lqohBwi3FijqtP2', 'user6@candyshop.com', FALSE),
('user7', '$2y$10$jK51kdGlA.pntVFK.wNsb.X0kjsF0v7FYBw..gTBpaBCyXY1s70bi', 'user7@candyshop.com', FALSE),
('user8', '$2y$10$dqxqb8t4hgUfUhXnq6gBbe0IlVtQ9vIYfRRurXkz2NSUF3pDYB3oS', 'user8@candyshop.com', FALSE),
('user9', '$2y$10$IOtBfhEgTICPChw3Rs2.W.UoNdDaHKGsr2wjjLqmK.yoDG8RTc11m', 'user9@candyshop.com', FALSE),
('user10', '$2y$10$GNUjqZhP0gGXgpj7fV9neu8phGHbybnsNnnl1cqN4ThUu6M/4fiUS', 'user10@candyshop.com', FALSE),
('user11', '$2y$10$1KFonwKOW.lcabeyAMMuheqgwIbFkgkluHTixmPmsTS1fTd77orbW', 'user11@candyshop.com', FALSE),
('user12', '$2y$10$QHlDCCxt5iRKJW7xaAaJiOoNOzL8CYjseBLdhTVx06pN7KUIgxFTG', 'user12@candyshop.com', FALSE),
('user13', '$2y$10$qteyJua6GJOx2QV.xgM3J.0g4PCneV8CeNqeiUuPwFBdSiMtjQHHa', 'user13@candyshop.com', FALSE),
('user14', '$2y$10$fykafLPbiquUcNgc2w8kQuiIqnF.3u0PfbkyszDUk5wEQQfRRDTOy', 'user14@candyshop.com', FALSE),
('user15', '$2y$10$1bnvsyeByF9DS8/NoGluA.z47D5vghWzZGUH9t7h6jdH9ZqlHcKsC', 'user15@candyshop.com', FALSE),
('user16', '$2y$10$GKKrlr6mGErGEg2dUTUYweZ536BBUzne5rifj3lqohBwi3FijqtP2', 'user16@candyshop.com', FALSE),
('user17', '$2y$10$jK51kdGlA.pntVFK.wNsb.X0kjsF0v7FYBw..gTBpaBCyXY1s70bi', 'user17@candyshop.com', FALSE),
('user18', '$2y$10$dqxqb8t4hgUfUhXnq6gBbe0IlVtQ9vIYfRRurXkz2NSUF3pDYB3oS', 'user18@candyshop.com', FALSE),
('user19', '$2y$10$IOtBfhEgTICPChw3Rs2.W.UoNdDaHKGsr2wjjLqmK.yoDG8RTc11m', 'user19@candyshop.com', FALSE),
('user20', '$2y$10$GNUjqZhP0gGXgpj7fV9neu8phGHbybnsNnnl1cqN4ThUu6M/4fiUS', 'user20@candyshop.com', FALSE),
('user21', '$2y$10$1KFonwKOW.lcabeyAMMuheqgwIbFkgkluHTixmPmsTS1fTd77orbW', 'user21@candyshop.com', FALSE),
('user22', '$2y$10$QHlDCCxt5iRKJW7xaAaJiOoNOzL8CYjseBLdhTVx06pN7KUIgxFTG', 'user22@candyshop.com', FALSE),
('user23', '$2y$10$qteyJua6GJOx2QV.xgM3J.0g4PCneV8CeNqeiUuPwFBdSiMtjQHHa', 'user23@candyshop.com', FALSE),
('user24', '$2y$10$fykafLPbiquUcNgc2w8kQuiIqnF.3u0PfbkyszDUk5wEQQfRRDTOy', 'user24@candyshop.com', FALSE),
('user25', '$2y$10$1bnvsyeByF9DS8/NoGluA.z47D5vghWzZGUH9t7h6jdH9ZqlHcKsC', 'user25@candyshop.com', FALSE),
('user26', '$2y$10$GKKrlr6mGErGEg2dUTUYweZ536BBUzne5rifj3lqohBwi3FijqtP2', 'user26@candyshop.com', FALSE),
('user27', '$2y$10$jK51kdGlA.pntVFK.wNsb.X0kjsF0v7FYBw..gTBpaBCyXY1s70bi', 'user27@candyshop.com', FALSE),
('user28', '$2y$10$dqxqb8t4hgUfUhXnq6gBbe0IlVtQ9vIYfRRurXkz2NSUF3pDYB3oS', 'user28@candyshop.com', FALSE),
('user29', '$2y$10$IOtBfhEgTICPChw3Rs2.W.UoNdDaHKGsr2wjjLqmK.yoDG8RTc11m', 'user29@candyshop.com', FALSE),
('user30', '$2y$10$GNUjqZhP0gGXgpj7fV9neu8phGHbybnsNnnl1cqN4ThUu6M/4fiUS', 'user30@candyshop.com', FALSE);

-- Insert Admins
INSERT INTO Admin (userid)
SELECT userid FROM User WHERE isadmin = TRUE;

-- Insert Customers
INSERT INTO Customer (userid)
SELECT userid FROM User WHERE isadmin = FALSE;

-- Insert Blog Posts (related to candies and cakes)
INSERT INTO BlogPost (adminid, content, title) VALUES
(1, 'Discover the art of crafting delicious chocolate cakes at home.', 'How to Make a Perfect Chocolate Cake'),
(2, 'Explore the history of gummy candies and their global popularity.', 'The Sweet History of Gummy Candies'),
(3, 'Tips for choosing the best candies for your next party.', 'Top 5 Candies for Party Favors'),
(4, 'Learn how to decorate cakes like a pro with simple tools.', 'Cake Decorating 101'),
(5, 'Why handmade candies make the best gifts for any occasion.', 'The Charm of Handmade Candies'),
(6, 'A guide to pairing chocolates with different beverages.', 'Chocolate and Drink Pairings'),
(7, 'How to make your own gummy candies at home.', 'DIY Gummy Candy Recipes'),
(8, 'The science behind the perfect cake texture.', 'Secrets to Fluffy Cakes');

-- Insert Likes
INSERT INTO `Like` (blogid, userid) VALUES
(1, 9), (1, 10), (1, 11), (1, 12), (2, 13), (2, 14), (2, 15),
(3, 16), (3, 17), (3, 18), (4, 19), (4, 20), (4, 21), (5, 22),
(5, 23), (6, 24), (6, 25), (7, 26), (7, 27), (8, 28), (8, 29), (8, 30);

-- Insert Blog Comments
INSERT INTO BlogComment (blogid, userid, content) VALUES
(1, 9, 'Loved the chocolate cake recipe!'),
(1, 10, 'Can’t wait to try this at home.'),
(2, 13, 'Gummy candies are my favorite!'),
(3, 16, 'Great party candy ideas.'),
(4, 19, 'The decorating tips were super helpful.'),
(5, 22, 'Handmade candies are so special!'),
(6, 24, 'Great pairing suggestions!'),
(7, 26, 'DIY gummies sound fun.'),
(8, 28, 'Learned a lot about cake texture!');

-- Insert Qna Entries
INSERT INTO QnaEntry (qnaid) VALUES
(1), (2), (3), (4), (5), (6), (7), (8);

-- Insert Messages (related to candies and cakes)
INSERT INTO Message (msgid, content, qnaid, userid) VALUES
(1, 'How do I store homemade cakes to keep them fresh?', 1, 9),
(2, 'Wrap tightly in plastic wrap and store in an airtight container.', 1, 10),
(3, 'What’s the best sugar for candy making?', 2, 11),
(4, 'Granulated sugar works well for most candies.', 2, 12),
(5, 'Can I freeze decorated cakes?', 3, 13),
(6, 'Yes, but wrap them well to avoid freezer burn.', 3, 9),
(7, 'How to make vegan candies?', 4, 14),
(8, 'Use plant-based gelatin substitutes like agar-agar.', 4, 15),
(9, 'What’s the shelf life of your chocolates?', 5, 16),
(10, 'Our chocolates last up to 6 months if stored properly.', 5, 17),
(11, 'Can you customize cakes for allergies?', 6, 18),
(12, 'Yes, we offer nut-free and gluten-free options.', 6, 19),
(13, 'How to make sugar-free candies?', 7, 20),
(14, 'Use sugar substitutes like stevia or erythritol.', 7, 21),
(15, 'Do you ship cakes internationally?', 8, 22),
(16, 'Currently, we ship within the country only.', 8, 23);

-- Insert Contacts
INSERT INTO Contact (name, phone, address, customerid) VALUES
('Alice Smith', '1234567890', '123 Candy Lane', 9),
('Bob Johnson', '0987654321', '456 Sweet St', 10),
('Clara Brown', '1122334455', '789 Sugar Rd', 11),
('David Wilson', '2233445566', '101 Cake Dr', 12),
('Emma Taylor', '3344556677', '202 Pastry Ln', 13),
('Finn Lee', '4455667788', '303 Choco Ct', 14),
('Grace Davis', '5566778899', '404 Candy St', 15),
('Henry Clark', '6677889900', '505 Sweet Way', 16),
('Isabella Adams', '7788990011', '606 Sugar Rd', 17),
('Jack Harris', '8899001122', '707 Cake Ave', 18),
('Kelly Moore', '9900112233', '808 Dessert Dr', 19),
('Liam Evans', '1011122334', '909 Sweet Ln', 20),
('Mia Carter', '2122233445', '1010 Candy Ct', 21),
('Noah Walker', '3233344556', '1111 Sugar St', 22),
('Olivia Scott', '4344455667', '1212 Pastry Rd', 23),
('Peter Young', '5455566778', '1313 Choco Ave', 24),
('Quinn Hall', '6566677889', '1414 Dessert Way', 25),
('Rose King', '7677788990', '1515 Sweet Dr', 26),
('Sam Green', '8788899001', '1616 Candy Ln', 27),
('Tina Bell', '9899000112', '1717 Sugar Ct', 28),
('Uma Ross', '0900112233', '1818 Cake St', 29),
('Vince Ward', '1011223344', '1919 Pastry Ave', 30);

-- Insert Orders
INSERT INTO `Order` (status, totalcost, customerid, contactid) VALUES
('Pending', 60000, 9, 1),
('Shipped', 85000, 10, 2),
('Delivered', 110000, 11, 3),
('Pending', 70000, 12, 4),
('Shipped', 95000, 13, 5),
('Delivered', 130000, 14, 6),
('Pending', 65000, 15, 7),
('Shipped', 90000, 16, 8),
('Delivered', 120000, 17, 9),
('Pending', 80000, 18, 10),
('Shipped', 100000, 19, 11),
('Delivered', 140000, 20, 12),
('Pending', 75000, 21, 13),
('Shipped', 105000, 22, 14),
('Delivered', 125000, 23, 15),
('Pending', 85000, 24, 16),
('Shipped', 115000, 25, 17),
('Delivered', 135000, 26, 18),
('Pending', 90000, 27, 19),
('Shipped', 110000, 28, 20),
('Delivered', 150000, 29, 21),
('Pending', 95000, 30, 22);

-- Insert Categories (specific to candies and cakes)
INSERT INTO Category (name) VALUES
('Cakes'),
('Chocolates'),
('Gummy Candies'),
('Hard Candies'),
('Pastries'),
('Gift Boxes'),
('Cookies'),
('Caramels');

-- Insert Manufacturers
INSERT INTO Manufacturer (name, country) VALUES
('SweetDelight', 'USA'),
('ChocoHeaven', 'Belgium'),
('GummyWorld', 'Germany'),
('CandyCraft', 'France'),
('PastryPro', 'Italy'),
('SugarGift', 'UK'),
('CookieCrave', 'Netherlands'),
('CaramelCo', 'Switzerland');

-- Insert Products (Expanded with more candy and cake products)
INSERT INTO `product` (`productid`, `name`, `price`, `description`, `avgrating`, `bought`, `mfgid`, `stock`, `cateid`, `avatarurl`) VALUES
(1, 'Chocolate Fudge Cake', 50000, 'Rich chocolate cake with creamy fudge frosting', 4.8, 250, 1, '1000', 1, 'assets/repo/product/1/download.jpg'),
(2, 'Vanilla Sponge Cake', 40000, 'Light and fluffy vanilla cake with buttercream', 4.6, 200, 1, '2000', 1, 'assets/repo/product/2/Lemon-Drizzle-Cake-4-500x500.jpg'),
(3, 'Red Velvet Cake', 55000, 'Classic red velvet with cream cheese frosting', 4.7, 220, 1, '200', 1, 'assets/repo/product/3/Red_Velvet_Cake_Waldorf_Astoria.jpg'),
(4, 'Carrot Cake', 52000, 'Moist carrot cake with walnuts and cream cheese', 4.5, 180, 1, '400', 1, 'assets/repo/product/4/Carrot-Cake-textless-videoSixteenByNineJumbo1600.jpg'),
(5, 'Lemon Drizzle Cake', 45000, 'Zesty lemon cake with a sugary glaze', 4.4, 160, 1, '200', 1, 'assets/repo/product/5/Lemon-Drizzle-Cake-4-500x500.jpg'),
(6, 'Dark Chocolate Truffles', 30000, 'Smooth dark chocolate truffles, 12 pieces', 4.9, 350, 2, '20', 2, 'assets/repo/product/6/images.jpg'),
(7, 'Milk Chocolate Pralines', 28000, 'Creamy milk chocolate pralines, 10 pieces', 4.5, 300, 2, '400', 2, 'assets/repo/product/7/images (1).jpg'),
(8, 'White Chocolate Bars', 25000, 'Pure white chocolate bars, 100g', 4.4, 250, 2, '200', 2, 'assets/repo/product/8/images.jpg'),
(9, 'Hazelnut Chocolate Bites', 32000, 'Dark chocolate with hazelnut filling, 15 pieces', 4.8, 280, 2, '100', 2, 'assets/repo/product/9/Lemon-Drizzle-Cake-4-500x500.jpg'),
(10, 'Salted Caramel Chocolates', 31000, 'Milk chocolate with salted caramel, 12 pieces', 4.7, 260, 2, '1000', 2, 'assets/repo/product/10/images.jpg'),
(11, 'Gummy Bears', 15000, 'Assorted fruit-flavored gummy bears, 200g', 4.3, 600, 3, '20000', 3, 'assets/repo/product/11/images (4).jpg'),
(12, 'Gummy Worms', 16000, 'Colorful gummy worms, 200g', 4.2, 550, 3, '200', 3, 'assets/repo/product/12/Oursons_gélatine_marché_Rouffignac.jpg'),
(13, 'Sour Gummy Mix', 17000, 'Tangy sour gummy candies, 200g', 4.1, 500, 3, '1000', 3, 'assets/repo/product/13/images.jpg'),
(14, 'Cola Gummy Bottles', 18000, 'Cola-flavored gummy bottles, 200g', 4.3, 450, 3, '4310', 3, 'assets/repo/product/14/Red_Velvet_Cake_Waldorf_Astoria.jpg'),
(15, 'Peach Gummy Rings', 17500, 'Sweet peach-flavored gummy rings, 200g', 4.2, 400, 3, '200', 3, 'assets/repo/product/15/images.jpg'),
(16, 'Peppermint Hard Candy', 12000, 'Refreshing peppermint hard candies, 150g', 4.0, 400, 4, '200', 4, 'assets/repo/product/16/Sponge-Cake-Recipe_-2.jpg'),
(17, 'Caramel Drops', 13000, 'Smooth caramel hard candies, 150g', 4.2, 350, 4, '898', 4, 'assets/repo/product/17/images.jpg'),
(18, 'Lemon Lollipops', 10000, 'Zesty lemon-flavored lollipops, 10 pieces', 4.1, 450, 4, '423', 4, 'assets/repo/product/18/Lemon-Drizzle-Cake-4-500x500.jpg'),
(19, 'Strawberry Hard Candy', 12500, 'Sweet strawberry hard candies, 150g', 4.0, 380, 4, '8080', 4, 'assets/repo/product/19/images (3).jpg'),
(20, 'Orange Drops', 11500, 'Tangy orange hard candies, 150g', 4.1, 360, 4, '20032', 4, 'assets/repo/product/20/Red_Velvet_Cake_Waldorf_Astoria.jpg'),
(21, 'Croissants', 20000, 'Buttery and flaky croissants, 4 pieces', 4.5, 300, 5, '890', 5, 'assets/repo/product/21/Lemon-Drizzle-Cake-4-500x500.jpg'),
(22, 'Fruit Tarts', 35000, 'Fresh fruit tarts with custard, 6 pieces', 4.7, 250, 5, '2004', 5, 'assets/repo/product/22/images (3).jpg'),
(23, 'Macarons', 30000, 'Assorted French macarons, 8 pieces', 4.8, 270, 5, '2006', 5, 'assets/repo/product/23/images (4).jpg'),
(24, 'Eclairs', 28000, 'Cream-filled eclairs with chocolate glaze, 6 pieces', 4.6, 240, 5, '2000', 5, 'assets/repo/product/24/images.jpg'),
(25, 'Danish Pastries', 25000, 'Assorted fruit-filled Danish pastries, 4 pieces', 4.5, 230, 5, '546', 5, 'assets/repo/product/25/Oursons_gélatine_marché_Rouffignac.jpg'),
(26, 'Deluxe Candy Gift Box', 60000, 'Premium assortment of chocolates and candies', 4.9, 150, 6, '795', 6, 'assets/repo/product/26/Lemon-Drizzle-Cake-4-500x500.jpg'),
(27, 'Mini Cake Gift Set', 45000, 'Set of 4 mini cakes in various flavors', 4.6, 140, 6, '7657', 6, 'assets/repo/product/27/images (4).jpg'),
(28, 'Chocolate and Gummy Combo', 40000, 'Mix of chocolates and gummy candies', 4.5, 180, 6, '75', 6, 'assets/repo/product/28/images.jpg'),
(29, 'Luxury Chocolate Box', 65000, 'Assorted premium chocolates, 24 pieces', 4.9, 120, 6, '200', 6, 'assets/repo/product/29/download.jpg'),
(30, 'Party Candy Pack', 50000, 'Large assortment of gummies and hard candies', 4.7, 160, 6, '686', 6, 'assets/repo/product/30/images.jpg'),
(31, 'Chocolate Chip Cookies', 20000, 'Classic chocolate chip cookies, 12 pieces', 4.6, 320, 7, '546', 7, 'assets/repo/product/31/images (2).jpg'),
(32, 'Oatmeal Raisin Cookies', 18000, 'Chewy oatmeal raisin cookies, 12 pieces', 4.4, 300, 7, '900', 7, 'assets/repo/product/32/Red_Velvet_Cake_Waldorf_Astoria.jpg'),
(33, 'Shortbread Cookies', 17000, 'Buttery shortbread cookies, 12 pieces', 4.3, 280, 7, '1288', 7, 'assets/repo/product/33/Sponge-Cake-Recipe_-2.jpg'),
(34, 'Peanut Butter Cookies', 19000, 'Creamy peanut butter cookies, 12 pieces', 4.5, 290, 7, '130', 7, 'assets/repo/product/34/images.jpg'),
(35, 'Sugar Cookies', 16000, 'Soft sugar cookies with sprinkles, 12 pieces', 4.4, 310, 7, '454', 7, 'assets/repo/product/35/images (3).jpg'),
(36, 'Salted Caramel Chews', 22000, 'Soft salted caramel chews, 200g', 4.7, 270, 8, '867', 8, 'assets/repo/product/36/images (1).jpg'),
(37, 'Toffee Caramels', 21000, 'Rich toffee caramels, 200g', 4.6, 260, 8, '54', 8, 'assets/repo/product/37/Best-Chocolate-Chip-Cookies-19.jpg'),
(38, 'Vanilla Caramels', 20000, 'Creamy vanilla caramels, 200g', 4.5, 250, 8, '7878', 8, 'assets/repo/product/38/anh-mo-ta.jpg');


-- Insert HasProduct
INSERT INTO HasProduct (orderid, productid, amount) VALUES
(1, 1, 1), (1, 6, 2), (2, 11, 3), (2, 16, 2), (3, 2, 1), (3, 21, 2),
(4, 7, 2), (4, 12, 3), (5, 3, 1), (5, 17, 2), (6, 8, 2), (6, 22, 2),
(7, 13, 3), (7, 23, 2), (8, 18, 2), (8, 26, 1), (9, 4, 1), (9, 9, 2),
(10, 14, 2), (10, 27, 1), (11, 5, 1), (11, 10, 2), (12, 15, 3), (12, 28, 1),
(13, 19, 2), (13, 29, 1), (14, 20, 2), (14, 30, 1), (15, 24, 2), (15, 31, 2),
(16, 25, 2), (16, 32, 2), (17, 33, 3), (17, 34, 2), (18, 35, 2), (18, 36, 1),
(19, 37, 2), (19, 1, 1), (20, 6, 2), (20, 11, 2), (21, 12, 3), (21, 16, 2),
(22, 17, 2), (22, 21, 1);

-- Insert RateProduct
INSERT INTO RateProduct (orderid, productid, rating, comment) VALUES
(1, 1, 5, 'Delicious chocolate cake!'),
(1, 6, 4, 'Truffles were amazing.'),
(2, 11, 4, 'Gummy bears are so tasty!'),
(2, 16, 5, 'Perfect peppermint flavor.'),
(3, 2, 5, 'Vanilla cake was fluffy.'),
(3, 21, 4, 'Croissants were fresh.'),
(4, 7, 4, 'Pralines melted in mouth.'),
(4, 12, 5, 'Gummy worms were fun!'),
(5, 3, 5, 'Red velvet was a hit.'),
(5, 17, 4, 'Caramel drops were great.'),
(6, 8, 4, 'White chocolate was creamy.'),
(6, 22, 5, 'Fruit tarts were divine.'),
(7, 13, 4, 'Sour gummies were tangy.'),
(7, 23, 5, 'Macarons were perfect.'),
(8, 18, 4, 'Lollipops were zesty.'),
(8, 26, 5, 'Gift box was a great choice.'),
(9, 4, 5, 'Carrot cake was moist.'),
(9, 9, 4, 'Hazelnut bites were yummy.'),
(10, 14, 4, 'Cola gummies were fun.'),
(10, 27, 5, 'Mini cakes were adorable.'),
(11, 5, 5, 'Lemon cake was refreshing.'),
(11, 10, 4, 'Salted caramel was rich.'),
(12, 15, 4, 'Peach rings were sweet.'),
(12, 28, 5, 'Luxury chocolates were divine.'),
(13, 19, 4, 'Strawberry candy was nice.'),
(13, 29, 5, 'Party pack was a hit.'),
(14, 20, 4, 'Orange drops were tangy.'),
(14, 30, 5, 'Cookies were crispy.'),
(15, 24, 4, 'Eclairs were creamy.'),
(15, 31, 5, 'Oatmeal cookies were chewy.'),
(16, 25, 4, 'Danish pastries were flaky.'),
(16, 32, 5, 'Shortbread was buttery.'),
(17, 33, 4, 'Peanut butter cookies were great.'),
(17, 34, 5, 'Sugar cookies were soft.'),
(18, 35, 4, 'Salted caramel chews were rich.'),
(18, 36, 5, 'Toffee caramels were perfect.'),
(19, 37, 4, 'Vanilla caramels were creamy.'),
(19, 1, 5, 'Another great cake.'),
(20, 6, 4, 'More truffles, please!'),
(20, 11, 5, 'Gummy bears never disappoint.'),
(21, 12, 4, 'Gummy worms were colorful.'),
(21, 16, 5, 'Peppermint candy was refreshing.'),
(22, 17, 4, 'Caramel drops were smooth.'),
(22, 21, 5, 'Croissants were amazing.');

-- Insert Faq Entries (tailored to candies and cakes)
INSERT INTO FaqEntry (answer, question) VALUES
('Store in an airtight container at room temperature for up to 3 days.', 'How do I store my cakes?'),
('Our candies are made fresh daily and last up to 6 months if stored properly.', 'What is the shelf life of your candies?'),
('Yes, we offer vegan and gluten-free options. Check our product descriptions.', 'Do you have vegan or gluten-free products?'),
('Contact us via email or phone within 24 hours to modify your order.', 'Can I change my order after placing it?'),
('We ship nationwide with temperature-controlled packaging.', 'Do you ship cakes and candies?'),
('Yes, we offer gift wrapping and personalized messages for all gift boxes.', 'Can I add a gift message to my order?'),
('Use a cool, dry place away from direct sunlight for chocolates.', 'How should I store chocolates?'),
('Yes, bulk discounts are available for orders over 50 items.', 'Do you offer discounts for bulk orders?'),
('We accept credit cards, PayPal, and bank transfers.', 'What payment methods do you accept?'),
('Email us at support@candyshop.com or call our helpline.', 'How do I contact customer support?'),
('Yes, we can customize cakes with your preferred flavors and designs.', 'Can I order a custom cake?'),
('Our sugar-free candies use safe sweeteners like stevia.', 'Are your sugar-free candies safe for diabetics?'),
('We recommend ordering at least 48 hours in advance for cakes.', 'How far in advance should I order a cake?');

-- Insert Contact Messages
INSERT INTO ContactMessage (name, email, title, message, status) VALUES
('Alice Smith', 'alice.smith@candyshop.com', 'Order Inquiry', 'Can you customize a cake for a birthday?', 'Unread'),
('Bob Johnson', 'bob.johnson@candyshop.com', 'Delivery Issue', 'My order hasn’t arrived yet.', 'Unread'),
('Clara Brown', 'clara.brown@candyshop.com', 'Feedback', 'Loved the gummy candies!', 'Read'),
('David Wilson', 'david.wilson@candyshop.com', 'Product Question', 'Are your chocolates nut-free?', 'Unread'),
('Emma Taylor', 'emma.taylor@candyshop.com', 'Bulk Order', 'Interested in ordering 100 gift boxes.', 'Read'),
('Finn Lee', 'finn.lee@candyshop.com', 'Complaint', 'Received wrong candy flavor.', 'Unread'),
('Grace Davis', 'grace.davis@candyshop.com', 'General Question', 'Do you have sugar-free options?', 'Unread'),
('Henry Clark', 'henry.clark@candyshop.com', 'Feedback', 'The macarons were fantastic!', 'Read'),
('Isabella Adams', 'isabella.adams@candyshop.com', 'Account Issue', 'Can’t reset my password.', 'Unread'),
('Jack Harris', 'jack.harris@candyshop.com', 'Suggestion', 'Add more vegan candies please!', 'Read'),
('Kelly Moore', 'kelly.moore@candyshop.com', 'Order Inquiry', 'Can you make a gluten-free cake?', 'Unread'),
('Liam Evans', 'liam.evans@candyshop.com', 'Feedback', 'The gift box was a hit!', 'Read'),
('Mia Carter', 'mia.carter@candyshop.com', 'Delivery Question', 'How long does shipping take?', 'Unread'),
('Noah Walker', 'noah.walker@candyshop.com', 'Product Question', 'Do you sell vegan chocolates?', 'Unread'),
('Olivia Scott', 'olivia.scott@candyshop.com', 'Complaint', 'My cake arrived damaged.', 'Unread');