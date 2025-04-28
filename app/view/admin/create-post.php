<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>News Management - Admin Panel</title>
    
    <?php require_once __DIR__."/../common/admin-link.php"; ?>
    
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
    <div id="app">
        <?php require_once __DIR__."/../common/admin-sidebar.php"; ?>
        
        <!-- Main Content -->
        <div id="main">
            <?php require_once __DIR__."/../common/admin-header.php"; ?>
            
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
                                <!-- Add/Edit Article Tab -->
                                <div class="tab-pane fade active show" id="add-article">
                                    <!-- Form content from original page -->
                                    <h3 id="article-form-title">Tạo bài viết mới</h3>
                                    <form method="post" action="/admin/blog/create" class="mt-4">                                        
                                        <div class="mb-3">
                                            <label for="articleTitle" class="form-label">Tiêu đề</label>
                                            <input type="text" class="form-control" id="articleTitle" name="title" required>
                                        </div>
                                        
                                        <!-- <div class="mb-3">
                                            <label for="articleCategorySelect" class="form-label">Category</label>
                                            <select class="form-select" id="articleCategorySelect" required>
                                                <option value="">Select category</option>
                                                <option value="tech">Technology</option>
                                                <option value="health">Health</option>
                                                <option value="sport">Sport</option>
                                                <option value="finance">Finance</option>
                                            </select>
                                        </div> -->
                                        
                                        <div class="mb-3">
                                            <label for="articleContent" class="form-label">Nội dung</label>
                                            <textarea class="form-control" id="articleContent" name="content" rows="12" required></textarea>
                                        </div>
                                        
                                        <!-- <div class="mb-3">
                                            <label for="articleImage" class="form-label">Featured Image</label>
                                            <input class="form-control" type="file" id="articleImage">
                                        </div> -->
                                        
                                        <!-- <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label for="articleAuthor" class="form-label">Author</label>
                                                <input type="text" class="form-control" id="articleAuthor" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="articleDate" class="form-label">Publish Date</label>
                                                <input type="date" class="form-control" id="articleDate" required>
                                            </div>
                                        </div> -->
                                        
                                        <!-- <div class="mb-3">
                                            <label for="articleStatus" class="form-label">Status</label>
                                            <select class="form-select" id="articleStatus" required>
                                                <option value="published">Published</option>
                                                <option value="draft">Draft</option>
                                            </select>
                                        </div>
                                        
                                        <div class="mb-3 form-check">
                                            <input type="checkbox" class="form-check-input" id="articleFeatured">
                                            <label class="form-check-label" for="articleFeatured">Feature this article</label>
                                        </div> -->
                                        
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="/admin/blog" class="btn btn-secondary">Hủy</a>
                                            <button type="reset" class="btn btn-danger">Reset</button>
                                            <button type="submit" class="btn btn-primary">Tạo bài viết</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>

    <?php 
        require_once __DIR__."/../common/admin-script.php";
    ?>
    
    <!-- <script>
        // JavaScript for handling article and comment operations
        document.addEventListener('DOMContentLoaded', function() {            
            // Handle article form submission
            document.getElementById('articleForm').addEventListener('submit', function(e) {
                e.preventDefault();
                const articleId = document.getElementById('articleId').value;
                if (articleId) {
                    updateArticle(articleId);
                } else {
                    createNewArticle();
                }
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
            
            // Example functions for CRUD operations (would be replaced with AJAX in production)
            function createNewArticle() {
                console.log('Creating new article');
                alert('Article created successfully!');
                // Reset form and switch tabs
                document.getElementById('articleForm').reset();
                document.getElementById('articles-tab').click();
            }
        });
    </script> -->
</body>
</html>