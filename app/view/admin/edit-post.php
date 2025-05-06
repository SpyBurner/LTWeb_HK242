<?php 
assert($post);
assert($admin);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>News Management - Admin Panel</title>

    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Replace textarea with div for Quill
        const textareaElement = document.getElementById('articleContent');
        const quillContainer = document.createElement('div');
        quillContainer.id = 'quill-editor';
        quillContainer.style.height = '300px';
        textareaElement.parentNode.insertBefore(quillContainer, textareaElement);
        textareaElement.style.display = 'none';
        
        // Initialize Quill editor
        const quill = new Quill('#quill-editor', {
            theme: 'snow',
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline', 'strike'],
                    ['blockquote', 'code-block'],
                    [{ 'header': 1 }, { 'header': 2 }],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    [{ 'script': 'sub'}, { 'script': 'super' }],
                    [{ 'indent': '-1'}, { 'indent': '+1' }],
                    ['link', 'image'],
                    ['clean']
                ]
            }
        });
        
        quill.root.innerHTML = textareaElement.value;
    
        // Get specific form by its action URL
        const form = document.querySelector('form[action^="/admin/blog/edit"]');
        if (form) {
            form.addEventListener('submit', function() {
                // Copy Quill content to textarea
                textareaElement.value = quill.root.innerHTML;
                console.log("Form submitting with content:", textareaElement.value);
            });
        }
        });
    </script>

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
                                    <h3 id="article-form-title">Sửa bài viết</h3>
                                    <form method="post" action="/admin/blog/edit?blogid=<?= $post->getBlogid() ?>" class="mt-4">  
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label for="articleAuthor" class="form-label">Tác giả</label>
                                                <input type="text" class="form-control" value="<?= $admin ?>" readonly>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="articleDate" class="form-label">Ngày đăng</label>
                                                <input type="date" class="form-control" value="<?= $post->getPostdate() ?>" readonly>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="articleTitle" class="form-label">Tiêu đề</label>
                                            <input type="text" name="title" value="<?= $post->getTitle() ?>" class="form-control" required>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="articleContent" class="form-label">Nội dung</label>
                                            <textarea id="articleContent" class="form-control" name="content" rows="12" required><?= htmlspecialchars($post->getContent()); ?></textarea>
                                        </div>
                                        
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="/admin/blog" class="btn btn-secondary">Hủy</a>
                                            <button type="submit" class="btn btn-primary">Cập nhật</button>
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
    
    <script>
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
    </script>
</body>
</html>