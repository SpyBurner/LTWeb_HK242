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
INSERT INTO `customer` (`userid`, `avatarurl`) VALUES
(4, 'assets/repo/avatar/4/anh-mo-ta.jpg'),
(5, 'assets/repo/avatar/5/1.jpg'),
(6, 'assets/repo/avatar/6/2.jpg'),
(7, 'assets/repo/avatar/7/3.jpg'),
(8, 'assets/repo/avatar/8/4.jpg'),
(9, 'assets/repo/avatar/9/5.jpg'),
(10, 'assets/repo/avatar/10/6.jpg');

INSERT INTO BlogPost (adminid, content, title) VALUES
(1, 'Learn how to make a classic vanilla sponge cake with fluffy texture and rich flavor.', 'Perfect Vanilla Sponge Cake'),
(2, 'These moist and fudgy brownies are packed with chocolate and topped with a shiny crust.', 'Ultimate Chocolate Brownies'),
(3, 'A step-by-step guide to baking buttery sugar cookies with decorative icing.', 'Decorated Sugar Cookies'),
(1, 'Make macarons like a pro with this foolproof recipe and troubleshooting tips.', 'Mastering French Macarons'),
(2, 'Whip up a batch of chewy, gooey caramel candies with this simple stovetop method.', 'Homemade Caramel Candy'),
(3, 'This red velvet cake recipe is moist, flavorful, and topped with rich cream cheese frosting.', 'Classic Red Velvet Cake'),
(1, 'Create a stunning multi-layer birthday cake with vibrant colors and smooth buttercream.', 'Layered Birthday Cake Tutorial'),
(2, 'Learn the basics of tempering chocolate to make elegant chocolate truffles.', 'Tempering Chocolate 101'),
(3, 'These fun and colorful cake pops are perfect for parties and bake sales.', 'Cake Pops for Beginners'),
(1, 'A beginner’s guide to making candy apples with a glossy, sweet coating.', 'Easy Candy Apples'),
(2, 'This lemon drizzle cake has a moist crumb and tangy lemon glaze that everyone will love.', 'Zesty Lemon Drizzle Cake'),
(3, 'Make soft, fluffy cinnamon rolls with a gooey brown sugar filling and cream cheese glaze.', 'Homemade Cinnamon Rolls'),
(1, 'Try these peanut butter fudge squares for a creamy, melt-in-your-mouth treat.', 'No-Bake Peanut Butter Fudge'),
(2, 'Use royal icing and food coloring to decorate cookies with precision and creativity.', 'Royal Icing Cookie Designs'),
(3, 'These bite-sized chocolate-covered strawberries are a romantic and easy dessert.', 'Chocolate-Dipped Strawberries'),
(1, 'A soft, moist carrot cake recipe packed with spices and topped with rich frosting.', 'Moist Carrot Cake Recipe'),
(2, 'Make buttery shortbread cookies with only 3 ingredients and a tender crumb.', 'Classic Shortbread Cookies'),
(3, 'Try your hand at making honeycomb candy with this crunchy and fun recipe.', 'Golden Honeycomb Candy'),
(1, 'This chocolate lava cake has a gooey center and is best served warm with ice cream.', 'Molten Chocolate Lava Cake'),
(2, 'Turn leftover cake scraps into fun and easy-to-make cake truffles.', 'Cake Truffles from Leftovers'),
(3, 'This banana bread is soft, moist, and full of flavor—perfect for breakfast or dessert.', 'Best Banana Bread Ever'),
(1, 'Bake these sweet and tangy raspberry bars with a buttery crumble topping.', 'Raspberry Crumble Bars'),
(2, 'Try this marshmallow recipe for light, fluffy homemade marshmallows with no corn syrup.', 'DIY Fluffy Marshmallows'),
(3, 'Decorate cupcakes with buttercream flowers using just a few piping tips.', 'Buttercream Flower Cupcakes'),
(1, 'Make crunchy brittle filled with roasted nuts and a touch of sea salt.', 'Nutty Candy Brittle'),
(2, 'This no-bake cheesecake has a creamy filling and graham cracker crust.', 'Easy No-Bake Cheesecake'),
(3, 'Learn how to flavor and color fondant to decorate cakes with style.', 'Working with Fondant'),
(1, 'These sugar-dusted snowball cookies are a festive holiday treat.', 'Holiday Snowball Cookies'),
(2, 'Try these chocolate bark recipes with toppings like nuts, pretzels, and dried fruit.', 'Creative Chocolate Bark Ideas'),
(3, 'Get inspired with these cupcake designs for birthdays, holidays, and more.', 'Cupcake Decoration Ideas');


INSERT INTO `Like` (blogid, userid) VALUES
(1, 2), (1, 5), (2, 3), (2, 7), (3, 1),
(4, 4), (4, 6), (5, 2), (5, 9), (6, 8),
(7, 1), (7, 3), (8, 4), (9, 6), (9, 10),
(10, 5), (10, 7), (11, 2), (11, 9), (12, 6),
(13, 1), (13, 8), (14, 3), (15, 7), (16, 2),
(17, 5), (18, 9), (18, 10), (19, 4), (20, 6),
(21, 3), (21, 7), (22, 1), (23, 2), (24, 5),
(25, 6), (25, 8), (26, 10), (27, 3), (28, 4),
(29, 7), (30, 1), (30, 9);


INSERT INTO BlogComment (blogid, userid, content) VALUES
(1, 5, 'Love this cake recipe! Turned out amazing.'),
(2, 3, 'Thanks for the brownie tips, super helpful.'),
(3, 1, 'These cookies are perfect for holidays.'),
(4, 6, 'Macaron guide was so clear and easy to follow!'),
(5, 2, 'Caramel turned out just right. Thanks!'),
(6, 9, 'This red velvet cake was a hit at my party.'),
(7, 4, 'Awesome decorating ideas.'),
(8, 8, 'Finally learned how to temper chocolate.'),
(9, 10, 'Kids loved the cake pops!'),
(10, 7, 'Candy apples worked great for Halloween.'),
(11, 3, 'Zesty and moist, loved it!'),
(12, 6, 'These cinnamon rolls are addictive.'),
(13, 1, 'Fudge was creamy and perfect.'),
(14, 5, 'Love your cookie decoration tips.'),
(15, 4, 'So easy and tasty!'),
(16, 2, 'Best carrot cake I’ve ever made.'),
(17, 8, 'Shortbread was buttery and simple.'),
(18, 9, 'Never thought I could make honeycomb!'),
(19, 7, 'Lava cake center was perfect.'),
(20, 3, 'Nice way to reuse cake scraps!'),
(21, 10, 'Banana bread was moist and delicious.'),
(22, 5, 'These raspberry bars are so good.'),
(23, 1, 'My marshmallows turned out great!'),
(24, 6, 'Beautiful cupcakes, thanks!'),
(25, 2, 'Brittle was fun to make.'),
(26, 4, 'Loved the cheesecake recipe.'),
(27, 9, 'Fondant tips saved my cake!'),
(28, 3, 'Snowballs were a hit at Christmas.'),
(29, 7, 'Great bark ideas!'),
(30, 1, 'Cupcake designs were really helpful.');


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


INSERT INTO `product` (`productid`, `name`, `price`, `description`, `avgrating`, `bought`, `mfgid`, `stock`, `cateid`, `avatarurl`) VALUES
(1, 'Chocolate Cake', 150000, 'Rich chocolate layer cake', 4.6, 120, 1, '500', 1, 'assets/repo/product/1/20.jpg'),
(2, 'Vanilla Cake', 140000, 'Classic vanilla sponge cake', 4.3, 95, 1, '500', 1, 'assets/repo/product/2/19.jpg'),
(3, 'Strawberry Shortcake', 170000, 'Fresh strawberries and cream', 4.8, 80, 2, '500', 1, 'assets/repo/product/3/18.jpg'),
(4, 'Oatmeal Cookie', 50000, 'Crunchy and healthy', 4.2, 130, 2, '500', 2, 'assets/repo/product/4/17.jpg'),
(5, 'Chocolate Chip Cookie', 60000, 'Soft with gooey chips', 4.7, 200, 1, '500', 2, 'assets/repo/product/5/chocolate-chip-cookie-recipe.jpg'),
(6, 'Butter Cookie', 45000, 'Melts in your mouth', 4.0, 70, 3, '500', 2, 'assets/repo/product/6/15.jpg'),
(7, 'Apple Pie', 180000, 'Classic apple cinnamon pie', 4.4, 90, 3, '500', 3, 'assets/repo/product/7/14.jpg'),
(8, 'Cherry Pie', 185000, 'Tart cherry filling', 4.1, 65, 2, '500', 3, 'assets/repo/product/8/13.jpg'),
(9, 'Pumpkin Pie', 175000, 'Spiced pumpkin flavor', 4.5, 110, 1, '500', 3, 'assets/repo/product/9/12.jpg'),
(10, 'Lemon Tart', 160000, 'Tangy and sweet', 4.6, 75, 1, '500', 4, 'assets/repo/product/10/11.jpg'),
(11, 'Chocolate Tart', 165000, 'Dark chocolate ganache', 4.9, 88, 2, '500', 4, 'assets/repo/product/11/10.jpg'),
(12, 'Fruit Tart', 170000, 'Fresh fruit and custard', 4.2, 50, 3, '500', 4, 'assets/repo/product/12/9.jpg'),
(13, 'Blueberry Muffin', 80000, 'Soft muffin with blueberries', 4.0, 45, 2, '500', 5, 'assets/repo/product/13/8.jpg'),
(14, 'Banana Muffin', 75000, 'Banana flavored muffin', 3.8, 35, 1, '500', 5, 'assets/repo/product/14/7.jpg'),
(15, 'Chocolate Muffin', 85000, 'Rich chocolate muffin', 4.3, 70, 1, '500', 5, 'assets/repo/product/15/6.jpg'),
(16, 'Raspberry Tart', 175000, 'Tart with fresh raspberries', 4.1, 30, 2, '500', 4, 'assets/repo/product/16/Raspberry-Tart-Feature.jpg'),
(17, 'Mini Cheesecake', 120000, 'Creamy and sweet', 4.4, 55, 3, '500', 1, 'assets/repo/product/17/images.jpg'),
(18, 'Peanut Butter Cookie', 60000, 'Rich peanut butter flavor', 4.5, 82, 2, '498', 2, 'assets/repo/product/18/peanut-butter-cookies.jpg'),
(19, 'Carrot Cake', 155000, 'Moist and spiced', 4.2, 66, 3, '499', 1, 'assets/repo/product/19/Carrot-Cake-textless-videoSixteenByNineJumbo1600.jpg'),
(20, 'Molten Lava Cake', 180000, 'Warm chocolate center', 4.9, 97, 1, '498', 1, 'assets/repo/product/20/download (1).jpg');


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


-- Additional QnaEntry records (IDs 4 to 23)
INSERT INTO QnaEntry (qnaid) VALUES
(4), (5), (6), (7), (8), (9), (10), (11), (12), (13),
(14), (15), (16), (17), (18), (19), (20), (21), (22), (23);

-- Revised Messages for QnA entries 4 to 23 (e-commerce for cakes/cookies)
INSERT INTO Message (msgid, content, qnaid, userid) VALUES
-- QnA 4
(7, "How do I manage inventory for a bakery product shop?", 4, 1),
(8, "Use a POS system with real-time stock updates.", 4, 2),
(9, "Track expiry dates, especially for fresh items.", 4, 3),
(10, "Group items by category: cookies, cakes, etc.", 4, 4),
(11, "Use barcodes to streamline the process.", 4, 5),

-- QnA 5
(12, "What's the best way to display cakes and cookies on my website?", 5, 2),
(13, "Use high-quality images with clean backgrounds.", 5, 3),
(14, "Group products by occasion, type, or flavor.", 5, 4),
(15, "Add customer reviews and ratings.", 5, 5),
(16, "Include clear pricing and delivery options.", 5, 6),

-- QnA 6
(17, "How do I handle same-day delivery for fresh baked goods?", 6, 3),
(18, "Partner with local couriers for fast delivery.", 6, 4),
(19, "Limit same-day delivery to nearby areas.", 6, 5),
(20, "Clearly show cut-off times on the website.", 6, 6),
(21, "Use insulated packaging to keep items fresh.", 6, 7),

-- QnA 7
(22, "What payment methods should I offer in an online cake shop?", 7, 4),
(23, "Accept credit/debit cards and mobile wallets.", 7, 5),
(24, "Offer cash on delivery if your region supports it.", 7, 6),
(25, "Make sure the checkout process is simple.", 7, 7),
(26, "Use a secure and trusted payment gateway.", 7, 8),

-- QnA 8
(27, "How can I attract more customers to my online store?", 8, 5),
(28, "Use social media ads targeted at dessert lovers.", 8, 6),
(29, "Offer discounts for first-time buyers.", 8, 7),
(30, "Run seasonal promotions like 'Valentine's Specials'.", 8, 8),
(31, "Encourage referrals with reward codes.", 8, 9),

-- QnA 9
(32, "How do I handle customer complaints?", 9, 6),
(33, "Respond quickly and politely to all feedback.", 9, 7),
(34, "Offer refunds or replacements when necessary.", 9, 8),
(35, "Log issues to improve future service.", 9, 9),
(36, "Train staff to manage difficult cases professionally.", 9, 10),

-- QnA 10
(37, "Should I list nutritional info for my products?", 10, 7),
(38, "Yes, especially for health-conscious customers.", 10, 8),
(39, "Include allergy warnings like 'Contains nuts'.", 10, 9),
(40, "Use supplier data to estimate calories and ingredients.", 10, 10),
(41, "Transparency builds customer trust.", 10, 1),

-- QnA 11
(42, "Do I need a return policy for baked goods?", 11, 8),
(43, "Yes, even if it's limited to damaged or wrong items.", 11, 9),
(44, "Clearly state what’s refundable or not.", 11, 10),
(45, "Mention time limits (e.g., within 24 hours).", 11, 1),
(46, "Policies protect both you and your customers.", 11, 2),

-- QnA 12
(47, "How do I manage product photos and descriptions?", 12, 9),
(48, "Take multiple angles under good lighting.", 12, 10),
(49, "Describe size, flavor, ingredients, and packaging.", 12, 1),
(50, "Keep the tone friendly and appetizing.", 12, 2),
(51, "Use consistent formatting for all listings.", 12, 3),

-- QnA 13
(52, "Is it worth offering gift packaging?", 13, 10),
(53, "Yes! Many customers buy cakes as gifts.", 13, 1),
(54, "Offer themes like birthdays, weddings, holidays.", 13, 2),
(55, "Add this option at checkout.", 13, 3),
(56, "Show sample photos to increase appeal.", 13, 4),

-- QnA 14
(57, "How do I prevent delivery issues?", 14, 1),
(58, "Double-check address info before shipping.", 14, 2),
(59, "Use a tracking system and notify customers.", 14, 3),
(60, "Work with reliable delivery partners.", 14, 4),
(61, "Offer pickup as an alternative.", 14, 5),

-- QnA 15
(62, "How can I encourage repeat purchases?", 15, 2),
(63, "Send email reminders and seasonal deals.", 15, 3),
(64, "Use loyalty points or punch cards.", 15, 4),
(65, "Ask for feedback and act on it.", 15, 5),
(66, "Keep product quality consistently high.", 15, 6),

-- QnA 16
(67, "Should I open a physical store too?", 16, 3),
(68, "It depends on your budget and local demand.", 16, 4),
(69, "Start online, then expand if sales grow.", 16, 5),
(70, "Pop-up booths are a good test.", 16, 6),
(71, "A shop boosts brand visibility.", 16, 7),

-- QnA 17
(72, "How do I price my products competitively?", 17, 4),
(73, "Research local competitors' prices.", 17, 5),
(74, "Factor in cost, delivery, and packaging.", 17, 6),
(75, "Don't undervalue premium or unique items.", 17, 7),
(76, "Offer bundles for better value.", 17, 8),

-- QnA 18
(77, "Can I sell cakes on marketplaces like Shopee?", 18, 5),
(78, "Yes, but check their rules for food items.", 18, 6),
(79, "Label everything clearly with expiry info.", 18, 7),
(80, "Use strong packaging to survive shipping.", 18, 8),
(81, "Link to your brand page from the listing.", 18, 9),

-- QnA 19
(82, "How do I collect customer feedback?", 19, 6),
(83, "Ask for reviews after delivery.", 19, 7),
(84, "Add QR codes linking to feedback forms.", 19, 8),
(85, "Use social media polls or messages.", 19, 9),
(86, "Reward customers who leave detailed reviews.", 19, 10),

-- QnA 20
(87, "Do I need a business license to sell online?", 20, 7),
(88, "Yes, check your local government’s requirements.", 20, 8),
(89, "Register as a food seller if applicable.", 20, 9),
(90, "You may need safety inspections too.", 20, 10),
(91, "Consult a local business advisor.", 20, 1),

-- QnA 21
(92, "What platform should I use for my cake store?", 21, 8),
(93, "Shopify and WooCommerce are beginner-friendly.", 21, 9),
(94, "Use themes made for food or dessert shops.", 21, 10),
(95, "Pick one with good delivery integration.", 21, 1),
(96, "Focus on mobile responsiveness.", 21, 2),

-- QnA 22
(97, "How do I deal with surplus or unsold items?", 22, 9),
(98, "Offer end-of-day discounts.", 22, 10),
(99, "Donate to charities or shelters if possible.", 22, 1),
(100, "Track trends to reduce future overstocking.", 22, 2),
(101, "Avoid over-ordering from suppliers.", 22, 3),

-- QnA 23
(102, "What should I include in a product label?", 23, 10),
(103, "Name, weight, ingredients, and expiry date.", 23, 1),
(104, "Add allergen info like dairy, gluten, nuts.", 23, 2),
(105, "Mention storage instructions, e.g., 'Keep refrigerated'.", 23, 3),
(106, "Include your brand name and contact.", 23, 4);