USE BTL_LTW;

-- Additional QnaEntry records (IDs 4 to 23)
INSERT INTO QnaEntry (qnaid) VALUES
(4), (5), (6), (7), (8), (9), (10), (11), (12), (13),
(14), (15), (16), (17), (18), (19), (20), (21), (22), (23);

-- Additional Messages for entries 4 to 23 (with userids 1–10)
INSERT INTO Message (msgid, content, qnaid, userid) VALUES
                                                        (7, "How do I connect to a MySQL database using PHP?", 4, 1),
                                                        (8, "Use mysqli or PDO. mysqli_connect() is straightforward.", 4, 2),
                                                        (9, "Also check your credentials and host address.", 4, 3),
                                                        (10, "PDO is more secure and flexible.", 4, 4),
                                                        (11, "Don't forget to handle connection errors!", 4, 5),

                                                        (12, "What's the difference between HTTP GET and POST?", 5, 2),
                                                        (13, "GET sends data in the URL; POST sends it in the body.", 5, 3),
                                                        (14, "Use POST for sensitive data like passwords.", 5, 4),
                                                        (15, "GET is good for bookmarking and links.", 5, 5),
                                                        (16, "POST can handle more data.", 5, 6),

                                                        (17, "How do I center a div in CSS?", 6, 3),
                                                        (18, "Use flexbox: display: flex; justify-content: center; align-items: center;", 6, 4),
                                                        (19, "margin: auto also works in some layouts.", 6, 5),
                                                        (20, "Grid layout is another option.", 6, 6),
                                                        (21, "Check for parent container styles too.", 6, 7),

                                                        (22, "What is a foreign key in SQL?", 7, 4),
                                                        (23, "It enforces a link between two tables.", 7, 5),
                                                        (24, "Foreign keys prevent orphan records.", 7, 6),
                                                        (25, "They help maintain referential integrity.", 7, 7),
                                                        (26, "Always index foreign key columns for performance.", 7, 8),

                                                        (27, "Best way to hash passwords in PHP?", 8, 5),
                                                        (28, "Use password_hash() and password_verify().", 8, 6),
                                                        (29, "Avoid MD5 or SHA1 — they're insecure.", 8, 7),
                                                        (30, "bcrypt is used behind password_hash().", 8, 8),
                                                        (31, "Don't roll your own crypto.", 8, 9),

                                                        (32, "How can I paginate results in MySQL?", 9, 6),
                                                        (33, "Use LIMIT and OFFSET clauses.", 9, 7),
                                                        (34, "Example: LIMIT 10 OFFSET 20 for page 3 with 10 per page.", 9, 8),
                                                        (35, "Remember to sanitize page inputs!", 9, 9),
                                                        (36, "Use prepared statements to avoid SQL injection.", 9, 10),

                                                        (37, "Difference between var, let, and const in JS?", 10, 7),
                                                        (38, "var is function-scoped, let/const are block-scoped.", 10, 8),
                                                        (39, "const means you can’t reassign the variable.", 10, 9),
                                                        (40, "Always prefer let/const in modern JS.", 10, 10),
                                                        (41, "Avoid var unless supporting legacy browsers.", 10, 1),

                                                        (42, "What's the role of MVC in web development?", 11, 8),
                                                        (43, "It separates logic (Model), UI (View), and control flow (Controller).", 11, 9),
                                                        (44, "Helps maintain clean and testable code.", 11, 10),
                                                        (45, "Popular in frameworks like Laravel and Rails.", 11, 1),
                                                        (46, "Keeps the application modular and scalable.", 11, 2),

                                                        (47, "How to make an API request in JavaScript?", 12, 9),
                                                        (48, "Use fetch() or axios for simplicity.", 12, 10),
                                                        (49, "Don't forget async/await or .then().", 12, 1),
                                                        (50, "Handle errors with try/catch or .catch().", 12, 2),
                                                        (51, "Check response.ok before parsing JSON.", 12, 3),

                                                        (52, "When should I use NoSQL over SQL?", 13, 10),
                                                        (53, "Use NoSQL for unstructured or large-scale data.", 13, 1),
                                                        (54, "MongoDB is great for JSON-like documents.", 13, 2),
                                                        (55, "SQL is still better for relational data.", 13, 3),
                                                        (56, "Use the right tool for the right job.", 13, 4),

                                                        (57, "What’s CSRF and how to prevent it?", 14, 1),
                                                        (58, "CSRF tricks users into submitting actions.", 14, 2),
                                                        (59, "Use CSRF tokens in forms.", 14, 3),
                                                        (60, "Verify tokens on server side.", 14, 4),
                                                        (61, "Use same-site cookies if possible.", 14, 5),

                                                        (62, "Is HTML5 input validation secure?", 15, 2),
                                                        (63, "It's useful, but not secure alone.", 15, 3),
                                                        (64, "Always validate on the server too.", 15, 4),
                                                        (65, "Client-side validation improves UX.", 15, 5),
                                                        (66, "Use regex and type attributes wisely.", 15, 6),

                                                        (67, "What is event bubbling in JS?", 16, 3),
                                                        (68, "It's how events propagate up the DOM tree.", 16, 4),
                                                        (69, "You can use event.stopPropagation() to block it.", 16, 5),
                                                        (70, "Also learn about event capturing.", 16, 6),
                                                        (71, "Understanding it helps with delegation.", 16, 7),

                                                        (72, "How to write cleaner PHP code?", 17, 4),
                                                        (73, "Use functions to reduce repetition.", 17, 5),
                                                        (74, "Follow PSR standards for formatting.", 17, 6),
                                                        (75, "Use Composer for dependencies.", 17, 7),
                                                        (76, "Avoid mixing logic and HTML too much.", 17, 8),

                                                        (77, "What's the best way to handle timezones?", 18, 5),
                                                        (78, "Always store in UTC, convert on output.", 18, 6),
                                                        (79, "Use DateTime and DateTimeZone classes.", 18, 7),
                                                        (80, "Let users pick their own timezone.", 18, 8),
                                                        (81, "Be consistent throughout the system.", 18, 9),

                                                        (82, "How do sessions work in PHP?", 19, 6),
                                                        (83, "They store data server-side tied to a session ID.", 19, 7),
                                                        (84, "session_start() begins the session.", 19, 8),
                                                        (85, "Stored in $_SESSION superglobal.", 19, 9),
                                                        (86, "Use secure cookies for session IDs.", 19, 10),

                                                        (87, "What's the use of indexes in SQL?", 20, 7),
                                                        (88, "They speed up data retrieval.", 20, 8),
                                                        (89, "Use them on columns that are frequently searched.", 20, 9),
                                                        (90, "Too many indexes can hurt insert performance.", 20, 10),
                                                        (91, "Always analyze your query plan.", 20, 1),

                                                        (92, "Why use prepared statements?", 21, 8),
                                                        (93, "They prevent SQL injection.", 21, 9),
                                                        (94, "Also improve performance on repeated queries.", 21, 10),
                                                        (95, "Use bind_param() or placeholders.", 21, 1),
                                                        (96, "They're a must for secure apps.", 21, 2),

                                                        (97, "How to compress images in PHP?", 22, 9),
                                                        (98, "Use GD or Imagick libraries.", 22, 10),
                                                        (99, "Adjust quality for JPEGs via imagejpeg().", 22, 1),
                                                        (100, "Consider resizing to reduce file size.", 22, 2),
                                                        (101, "Always test output for quality loss.", 22, 3),

                                                        (102, "How to debug JavaScript in the browser?", 23, 10),
                                                        (103, "Use console.log() for simple checks.", 23, 1),
                                                        (104, "Chrome DevTools is your best friend.", 23, 2),
                                                        (105, "Set breakpoints and inspect variables.", 23, 3),
                                                        (106, "Use the Network tab to debug API calls.", 23, 4);
