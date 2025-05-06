USE BTL_LTW;

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
