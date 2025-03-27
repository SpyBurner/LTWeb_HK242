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
                            <a href="index.html"><img src="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/compiled/svg/logo.svg" alt="Logo" srcset=""></a>
                        </div>
                        <div class="theme-toggle d-flex gap-2  align-items-center mt-2">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" class="iconify iconify--system-uicons" width="20" height="20" preserveAspectRatio="xMidYMid meet" viewBox="0 0 21 21"><g fill="none" fill-rule="evenodd" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M10.5 14.5c2.219 0 4-1.763 4-3.982a4.003 4.003 0 0 0-4-4.018c-2.219 0-4 1.781-4 4c0 2.219 1.781 4 4 4zM4.136 4.136L5.55 5.55m9.9 9.9l1.414 1.414M1.5 10.5h2m14 0h2M4.135 16.863L5.55 15.45m9.899-9.9l1.414-1.415M10.5 19.5v-2m0-14v-2" opacity=".3"></path><g transform="translate(-210 -1)"><path d="M220.5 2.5v2m6.5.5l-1.5 1.5"></path><circle cx="220.5" cy="11.5" r="4"></circle><path d="m214 5l1.5 1.5m5 14v-2m6.5-.5l-1.5-1.5M214 18l1.5-1.5m-4-5h2m14 0h2"></path></g></g></svg>
                            <div class="form-check form-switch fs-6">
                                <input class="form-check-input me-0" type="checkbox" id="toggle-dark" >
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
                                <li class="submenu-item active">
                                    <a class="submenu-link">Danh sách bài viết</a>
                                </li>
                                <li class="submenu-item">
                                    <a href="<?= BASE_URL.'/create-post.php' ?>" class="submenu-link">Tạo bài viết mới</a>
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
            
            <div class="page-heading">
                <div class="page-title">
                    <div class="row">
                        <div class="col-12 col-md-6 order-md-1 order-last">
                            <h3>Quản lí bài viết</h3>
                            <p class="text-subtitle text-muted">Quản lí bài viết và bình luận</p>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">News Management</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>

            <div class="page-content">
                <section class="section">
                    <div class="card">
                        <div class="card-body">
                            <!-- Tab Content -->
                            <div class="tab-content" id="newsAdminTabsContent">
                                <!-- Articles Tab -->
                                <div class="tab-pane fade show active">
                                    <!-- Search and Filter -->
                                    <div class="row search-container">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="searchArticles" placeholder="Search articles...">
                                                <button class="btn btn-outline-secondary" type="button"><i class="fas fa-search"></i> Search</button>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Articles Table -->
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <!-- Table content from original page -->
                                            <thead class="table-light">
                                                <tr>
                                                    <th scope="col">#</th>
                                                    <th scope="col">Tiêu đề</th>
                                                    <th scope="col">Tác giả</th>
                                                    <th scope="col">Ngày đăng</th>
                                                    <th scope="col">Actions</th>
                                                    <th scope="col">Likes</th>
                                                    <th scope="col">Bình luận</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php 
                                                    // Chuẩn bị truy vấn SQL với JOIN và GROUP BY
                                                    $sql = "SELECT p.*, u.username AS author, COUNT(l.userid) AS likes 
                                                    FROM blogpost p 
                                                    INNER JOIN user u ON p.adminid = u.userid 
                                                    LEFT JOIN `like` l ON p.blogid = l.blogid 
                                                    GROUP BY p.blogid";

                                                    $stmt = $conn->prepare($sql);
                                                    $stmt->execute();
                                                    $result = $stmt->get_result();

                                                    if ($result->num_rows > 0): 
                                                    while ($row = $result->fetch_assoc()): 
                                                    // Bảo vệ dữ liệu đầu ra
                                                    $blogId = (int) $row["blogid"];
                                                    $title = htmlspecialchars($row["title"]);
                                                    $author = htmlspecialchars($row["author"]);
                                                    $postDate = htmlspecialchars($row["postdate"]);
                                                    $likes = (int) $row["likes"];

                                                    // Tạo đường dẫn
                                                    $url = "comment-admin.php/$blogId";
                                                    $edit = "edit-post.php/$blogId";
                                                ?>
                                                <tr>
                                                    <td><?= $blogId ?></td>
                                                    <td><?= $title ?></td>
                                                    <td><?= $author ?></td>
                                                    <td><?= $postDate ?></td>
                                                    <td class="action-buttons">
                                                        <a class="btn btn-sm btn-info edit-article" href="<?= $edit ?>" data-id="<?= $blogId ?>">
                                                            <i class="fas fa-edit"></i> Edit
                                                        </a>
                                                        <button class="btn btn-sm btn-danger delete-article" data-id="<?= $blogId ?>">
                                                            <i class="fas fa-trash"></i> Delete
                                                        </button>
                                                    </td>
                                                    <td>
                                                        <i class="fa-solid fa-thumbs-up fs-5"></i>
                                                        <span class="fs-5"><?= $likes ?></span>
                                                    </td>
                                                    <td>
                                                        <a href="<?= $url ?>" class="btn btn-primary">All Comments</a>
                                                    </td>
                                                </tr>
                                                <?php 
                                                    endwhile;
                                                    else: 
                                                ?>
                                                    <tr><td colspan="7" class="text-center">No results found</td></tr>
                                                <?php 
                                                    endif;
                                                    $stmt->close();
                                                ?>
                                            </tbody>
                                        </table>
                                        
                                        <!-- Pagination -->
                                        <nav>
                                            <ul class="pagination justify-content-center">
                                                <li class="page-item disabled">
                                                    <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                                                </li>
                                                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                                <li class="page-item"><a class="page-link" href="#">2</a></li>
                                                <li class="page-item"><a class="page-link" href="#">3</a></li>
                                                <li class="page-item">
                                                    <a class="page-link" href="#">Next</a>
                                                </li>
                                            </ul>
                                        </nav>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
            
            <!-- <footer>
                <div class="footer clearfix mb-0 text-muted">
                    <div class="float-start">
                        <p>2023 &copy; Mazer Admin Template</p>
                    </div>
                    <div class="float-end">
                        <p>Customized with <span class="text-danger"><i class="bi bi-heart-fill"></i></span> by <a href="#">Your Company</a></p>
                    </div>
                </div>
            </footer> -->
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
            // Handle edit article button clicks
            document.querySelectorAll('.edit-article').forEach(button => {
                button.addEventListener('click', function() {
                    const articleId = this.getAttribute('data-id');
                    // Fetch article data and populate the form
                    fetchArticleData(articleId);
                    // Show the add/edit tab
                    document.getElementById('article-form-title').textContent = 'Edit Article';
                    document.getElementById('add-article-tab').click();
                });
            });
            
            // Handle delete article button clicks
            document.querySelectorAll('.delete-article').forEach(button => {
                button.addEventListener('click', function() {
                    const articleId = this.getAttribute('data-id');
                    document.getElementById('confirmDeleteArticle').setAttribute('data-id', articleId);
                    // Show confirmation modal
                    new bootstrap.Modal(document.getElementById('deleteArticleModal')).show();
                });
            });
            
            // Handle delete comment button clicks
            document.querySelectorAll('.delete-comment').forEach(button => {
                button.addEventListener('click', function() {
                    const commentId = this.getAttribute('data-id');
                    document.getElementById('confirmDeleteComment').setAttribute('data-id', commentId);
                    // Show confirmation modal
                    // new bootstrap.Modal(document.getElementById('deleteCommentModal')).show();
                    document.getElementsByClassName('del-comment-box')[0].classList.remove('d-none');
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
                document.getElementsByClassName('del-comment-box')[0].classList.add('d-none');
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