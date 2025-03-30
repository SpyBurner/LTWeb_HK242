<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>News Management - Admin Panel</title>
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Mazer Template CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/compiled/css/app.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/compiled/css/app-dark.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/compiled/css/iconly.css">
    
    <style>
        .action-buttons .btn {
            margin-right: 5px;
        }
        .tab-content {
            padding: 20px 0;
        }
        .search-container {
            margin-bottom: 20px;
        }
        .table-responsive {
            margin-bottom: 30px;
        }
        .comment-author {
            font-weight: bold;
        }
        .comment-date {
            font-size: 0.8em;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <?php 
        $url = $_SERVER['REQUEST_URI']; 
        $parts = explode("/", $url); 
        $id = end($parts); 

        include_once "./config.php";

        $env = parse_ini_file("../../../config/.env");
        $servername = $env["DB_HOST"];
        $username = "root";
        $password = "";
        $dbname = $env["DB_NAME"];
    
        // Create connection
        $conn = mysqli_connect($servername, $username, $password, $dbname);
    
        // Check connection
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
    ?>
    <script src="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/static/js/initTheme.js"></script>
    <div id="app">
        <!-- Sidebar -->
        <div id="sidebar" class="active">
            <div class="sidebar-wrapper active">
                <div class="sidebar-header position-relative">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="logo">
                            <a href="../../../public/index.php"><img src="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/compiled/svg/logo.svg" alt="Logo" srcset=""></a>
                        </div>
                        <div class="theme-toggle d-flex gap-2  align-items-center mt-2">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" class="iconify iconify--system-uicons" width="20" height="20" preserveAspectRatio="xMidYMid meet" viewBox="0 0 21 21"><g fill="none" fill-rule="evenodd" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M10.5 14.5c2.219 0 4-1.763 4-3.982a4.003 4.003 0 0 0-4-4.018c-2.219 0-4 1.781-4 4c0 2.219 1.781 4 4 4zM4.136 4.136L5.55 5.55m9.9 9.9l1.414 1.414M1.5 10.5h2m14 0h2M4.135 16.863L5.55 15.45m9.899-9.9l1.414-1.415M10.5 19.5v-2m0-14v-2" opacity=".3"></path><g transform="translate(-210 -1)"><path d="M220.5 2.5v2m6.5.5l-1.5 1.5"></path><circle cx="220.5" cy="11.5" r="4"></circle><path d="m214 5l1.5 1.5m5 14v-2m6.5-.5l-1.5-1.5M214 18l1.5-1.5m-4-5h2m14 0h2"></path></g></g></svg>
                            <div class="form-check form-switch fs-6">
                                <input class="form-check-input  me-0" type="checkbox" id="toggle-dark" >
                                <label class="form-check-label" ></label>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" class="iconify iconify--mdi" width="20" height="20" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><path fill="currentColor" d="m17.75 4.09l-2.53 1.94l.91 3.06l-2.63-1.81l-2.63 1.81l.91-3.06l-2.53-1.94L12.44 4l1.06-3l1.06 3l3.19.09m3.5 6.91l-1.64 1.25l.59 1.98l-1.7-1.17l-1.7 1.17l.59-1.98L15.75 11l2.06-.05L18.5 9l.69 1.95l2.06.05m-2.28 4.95c.83-.08 1.72 1.1 1.19 1.85c-.32.45-.66.87-1.08 1.27C15.17 23 8.84 23 4.94 19.07c-3.91-3.9-3.91-10.24 0-14.14c.4-.4.82-.76 1.27-1.08c.75-.53 1.93.36 1.85 1.19c-.27 2.86.69 5.83 2.89 8.02a9.96 9.96 0 0 0 8.02 2.89m-1.64 2.02a12.08 12.08 0 0 1-7.8-3.47c-2.17-2.19-3.33-5-3.49-7.82c-2.81 3.14-2.7 7.96.31 10.98c3.02 3.01 7.84 3.12 10.98.31Z"></path></svg>
                        </div>
                        <div class="sidebar-toggler x">
                            <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                        </div>
                    </div>
                </div>
                
                <!-- Sidebar Menu -->
                <div class="sidebar-menu">
                    <ul class="menu">
                        <li class="sidebar-title">Menu</li>
                        
                        <li class="sidebar-item">
                            <a href="#" class='sidebar-link'>
                                <i class="bi bi-grid-fill"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                        
                        <li class="sidebar-item active">
                            <a href="#" class='sidebar-link'>
                                <i class="bi bi-newspaper bi-stack"></i>
                                <span>Bài viết</span>
                            </a>
                            <ul class="submenu active submenu-open">
                                <li class="submenu-item">
                                    <a href="<?= BASE_URL.'/news-admin.php' ?>" class="submenu-link">Danh sách bài viết</a>
                                </li>
                                <li class="submenu-item">
                                    <a href="<?= BASE_URL.'/create-post.php' ?>" class="submenu-link">Tạo bài viết mới</a>
                                </li>
                                <li class="submenu-item active">
                                    <a class="submenu-link">Bình luận</a>
                                </li>
                            </ul>
                        </li>
                        
                        <li class="sidebar-item">
                            <a href="#" class='sidebar-link'>
                                <i class="bi bi-cart-fill"></i>
                                <span>Products</span>
                            </a>
                        </li>
                        
                        <li class="sidebar-item">
                            <a href="#" class='sidebar-link'>
                                <i class="bi bi-people-fill"></i>
                                <span>Users</span>
                            </a>
                        </li>
                        
                        <li class="sidebar-item">
                            <a href="#" class='sidebar-link'>
                                <i class="bi bi-gear-fill"></i>
                                <span>Settings</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        
        <!-- Main Content -->
        <div id="main">
            <header class="mb-3">
                <a href="#" class="burger-btn d-block d-xl-none">
                    <i class="bi bi-justify fs-3"></i>
                </a>
            </header>

            <div class="page-content">
                <section class="section">
                    <div class="card">
                        <div class="card-body">
                        <div class="modal-header">
                            <h5 class="modal-title">Tất cả bình luận của bài viết <?= $id?></h5>
                        </div>
                        <div class="modal-body">
                            <div class="comment-management">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <?php 
                                        $sql = "SELECT COUNT(*) as amount FROM blogcomment where blogid = $id";
                                        $result = mysqli_query($conn, $sql);
                                        $row = mysqli_fetch_assoc($result)
                                    ?>
                                    <h6 class="my-2"><?= $row["amount"] ?> Bình luận</h6>
                                    <div class="btn-group">
                                        <button class="btn btn-outline-secondary btn-sm">
                                            <i class="fas fa-sort-alpha-down"></i> Mới nhất
                                        </button>
                                    </div>
                                </div>

                                <div class="card mb-0">
                                    <div class="card-body">
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="searchComments" placeholder="Search comments...">
                                            <button class="btn btn-outline-secondary" type="button"><i class="fas fa-search"></i> Search</button>
                                        </div>
                                    </div>
                                </div>
                                
                                <?php
                                    $sql = "SELECT b.*, u.username FROM blogcomment b inner join user u on b.userid = u.userid where blogid = $id";
                                    $result = mysqli_query($conn, $sql);
                                    if ($result->num_rows > 0):
                                        while ($row = $result->fetch_assoc()):
                                            $username = htmlspecialchars($row["username"]);
                                            $commentDate = htmlspecialchars($row["commentdate"]);
                                            $content = nl2br(htmlspecialchars($row["content"]));
                                            $blogId = (int) $row["blogid"];
                                ?>
                                    <div class="card mb-3">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <div>
                                                    <h6 class="mb-2"><?= $username ?></h6>
                                                    <small class="text-muted"><?= $commentDate ?></small>
                                                </div>
                                            </div>
                                            <p class="comment-text my-2"><?= $content ?></p>
                                            <div class="d-flex">
                                                <div class="btn-group btn-group-sm">
                                                    <button type="button" class="btn btn-outline-danger mt-2 delete-comment" data-id="<?= $blogId ?>">
                                                        <i class="fas fa-trash"></i> Delete
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="mt-2 border border-light rounded p-3 del-comment-box d-none">
                                                <div class="del-comment-header d-flex justify-content-end">
                                                    <button type="button" class="btn-close close-box" aria-label="Close"></button>
                                                </div>
                                                <div class="del-comment-body mt-2">
                                                    Are you sure you want to delete this comment? This action cannot be undone.
                                                </div>
                                                <div class="del-comment-footer d-flex justify-content-end mt-3">
                                                    <button type="button" class="btn btn-danger" id="confirmDeleteComment">Delete</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php
                                    endwhile;
                                    endif;
                                ?>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary">Save Changes</button>
                        </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
    
    <!-- Delete Article Confirmation Modal -->
    <div class="modal fade" id="deleteArticleModal" tabindex="-1" aria-labelledby="deleteArticleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteArticleModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this article? This action cannot be undone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteArticle">Delete</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Delete Comment Confirmation Modal -->
    <div class="modal fade" id="deleteCommentModal" tabindex="-1" aria-labelledby="deleteCommentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteCommentModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this comment? This action cannot be undone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteComment">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Mazer Template JS -->
    <script src="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/static/js/components/dark.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/compiled/js/app.js"></script>

    <!-- Need: Apexcharts -->
    <script src="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/extensions/apexcharts/apexcharts.min.js"></script>
    
    <script>
        // JavaScript for handling article and comment operations
        document.addEventListener('DOMContentLoaded', function() {            
            // Handle delete comment button clicks
            document.querySelectorAll('.delete-comment').forEach(button => {
                button.addEventListener('click', function() {
                    const commentId = this.getAttribute('data-id');
                    document.getElementById('confirmDeleteComment').setAttribute('data-id', commentId);
                    // Show confirmation modal
                    new bootstrap.Modal(document.getElementById('deleteCommentModal')).show();
                });
            });

            document.querySelectorAll('.close-box').forEach(button => {
                button.addEventListener('click', function() {
                    document.getElementsByClassName('del-comment-box')[0].classList.add('d-none');
                });
            });
            
            // Handle confirm delete article
            document.getElementById('confirmDeleteArticle').addEventListener('click', function() {
                const articleId = this.getAttribute('data-id');
                deleteArticle(articleId);
                // Close the modal
                bootstrap.Modal.getInstance(document.getElementById('deleteArticleModal')).hide();
            });
            
            // Handle confirm delete comment
            document.getElementById('confirmDeleteComment').addEventListener('click', function() {
                const commentId = this.getAttribute('data-id');
                deleteComment(commentId);
                // Close the modal
                bootstrap.Modal.getInstance(document.getElementById('deleteCommentModal')).hide();
            });
            
            // Example function to fetch article data (would be replaced with AJAX in production)
            function fetchArticleData(id) {
                console.log(`Fetching data for article ID: ${id}`);
                // This would be an AJAX call in a real application
                document.getElementById('articleId').value = id;
                document.getElementById('articleTitle').value = 'Sample Article ' + id;
                document.getElementById('articleContent').value = 'This is the content of article ' + id;
                document.getElementById('articleAuthor').value = 'Admin User';
                document.getElementById('articleDate').value = '2023-06-15';
                document.getElementById('articleStatus').value = 'published';
            }
            
            // // Example functions for CRUD operations (would be replaced with AJAX in production)
            // function createNewArticle() {
            //     console.log('Creating new article');
            //     alert('Article created successfully!');
            //     // Reset form and switch tabs
            //     document.getElementById('articleForm').reset();
            //     document.getElementById('articles-tab').click();
            // }
            
            // function updateArticle(id) {
            //     console.log(`Updating article ID: ${id}`);
            //     alert(`Article ${id} updated successfully!`);
            //     // Reset form and switch tabs
            //     document.getElementById('articleForm').reset();
            //     document.getElementById('articleId').value = '';
            //     document.getElementById('articles-tab').click();
            // }
            
            function deleteArticle(id) {
                console.log(`Deleting article ID: ${id}`);
                alert(`Article ${id} deleted successfully!`);
                // In production, you would remove the row from the DOM after successful deletion
            }
            
            function deleteComment(id) {
                console.log(`Deleting comment ID: ${id}`);
                alert(`Comment ${id} deleted successfully!`);
                // In production, you would remove the row from the DOM after successful deletion
            }

            // Comment modal functionality
            const viewCommentsModal = document.getElementById('viewCommentsModal');
            viewCommentsModal.addEventListener('show.bs.modal', event => {
                const button = event.relatedTarget;
                const postId = button.getAttribute('data-post-id');
                // Here you would fetch comments for the post with ID 'postId'
                // For demo purposes, we'll just update the modal title
                const modalTitle = viewCommentsModal.querySelector('.modal-title');
                modalTitle.textContent = `Tất cả bình luận của bài viết #${postId}`;
            });
        });
    </script>
</body>
</html>