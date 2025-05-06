<?php
assert(isset($posts));
assert(isset($authors));
assert(isset($likes));
?>
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
                                <!-- Articles Tab -->
                                <div class="tab-pane fade show active">
                                    <!-- Search and Filter -->
                                    <div class="row search-container">
                                        <div class="col-md-6">
                                            <form class="input-group" action="/admin/blog/search">
                                                <input type="text" class="form-control" name="term" placeholder="Tìm kiếm bài viết...">
                                                <button class="btn btn-outline-secondary" type="submit"><i class="fas fa-search"></i> Tìm kiếm</button>
                                            </form>
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
                                                    $i = 0;
                                                    while ($i < count($posts)): 
                                                    // Bảo vệ dữ liệu đầu ra
                                                    $blogId = (int) $posts[$i]->getBlogid();
                                                    $title = htmlspecialchars($posts[$i]->getTitle());
                                                    $author = htmlspecialchars($authors[$i]);
                                                    $postDate = htmlspecialchars($posts[$i]->getPostdate());
                                                    $like = htmlspecialchars($likes[$i]);
                                                    
                                                    $i++;
                                                    // Tạo đường dẫn
                                                    $comment_url = "/admin/blog/comment?blogid=".$blogId;
                                                    $edit_url = "/admin/blog/edit?blogid=".$blogId;
                                                ?>
                                                <tr>
                                                    <td><?= $blogId ?></td>
                                                    <td><?= $title ?></td>
                                                    <td><?= $author ?></td>
                                                    <td><?= $postDate ?></td>
                                                    <td class="action-buttons">
                                                        <a class="btn btn-sm btn-info edit-article" href="<?= $edit_url ?>" data-id="<?= $blogId ?>">
                                                            <i class="fas fa-edit"></i> Edit
                                                        </a>
                                                        <button class="btn btn-sm btn-danger delete-article" data-id="<?= $blogId ?>">
                                                            <i class="fas fa-trash"></i> Delete
                                                        </button>
                                                    </td>
                                                    <td>
                                                        <i class="fa-solid fa-thumbs-up fs-5"></i>
                                                        <span class="fs-5"><?= $like ?></span>
                                                    </td>
                                                    <td>
                                                        <a href="<?= $comment_url ?>" class="btn btn-primary">All Comments</a>
                                                    </td>
                                                </tr>
                                                <?php 
                                                    endwhile;
                                                ?>
                                            </tbody>
                                        </table>
                                        
                                        <!-- Pagination -->
                                        <nav class="pagination justify-content-center" id="pagination">
                                            <!-- <ul>
                                                <li class="page-item disabled">
                                                    <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                                                </li>
                                                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                                <li class="page-item"><a class="page-link" href="#">2</a></li>
                                                <li class="page-item"><a class="page-link" href="#">3</a></li>
                                                <li class="page-item">
                                                    <a class="page-link" href="#">Next</a>
                                                </li>
                                            </ul> -->
                                        </nav>
                                    </div>
                                </div>
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
                    <h5 class="modal-title" id="deleteArticleModalLabel">Xác nhận</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Bạn có chắc chắn muốn xóa bài viết này không? Hành động này không thể hoàn tác.
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn btn-danger" id="confirmDeleteArticle">Xóa</a>
                </div>
            </div>
        </div>
    </div>

    <?php require_once __DIR__."/../common/admin-script.php"; ?>
    
    <script>
        document.querySelectorAll('.delete-article').forEach(button => {
            button.addEventListener('click', function() {
                const articleId = this.getAttribute('data-id');
                // Cập nhật href cho nút xác nhận xóa trong modal
                document.getElementById('confirmDeleteArticle').href = `/admin/blog/delete?blogid=${articleId}`;
                // Hiển thị modal xác nhận
                new bootstrap.Modal(document.getElementById('deleteArticleModal')).show();
            });
        });
    </script>
</body>
</html>