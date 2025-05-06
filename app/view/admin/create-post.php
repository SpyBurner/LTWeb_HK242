<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>News Management - Admin Panel</title>
    <!-- Add these to your head section -->
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
        
        // Copy Quill content to textarea before form submission
        document.querySelector('form').addEventListener('submit', function() {
            textareaElement.value = quill.root.innerHTML;
        });
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
                                    <h3 id="article-form-title">Tạo bài viết mới</h3>
                                    <form method="post" action="/admin/blog/create" class="mt-4">                                        
                                        <div class="mb-3">
                                            <label for="articleTitle" class="form-label">Tiêu đề</label>
                                            <input type="text" class="form-control" id="articleTitle" name="title" required>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="articleContent" class="form-label">Nội dung</label>
                                            <textarea class="form-control" id="articleContent" name="content" rows="12" required></textarea>
                                        </div>
                                
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
</body>
</html>