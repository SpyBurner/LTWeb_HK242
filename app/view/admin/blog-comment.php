<?php 
assert($id);
assert($comments);
assert($commentUser);
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
        
        <!-- Main Content -->
        <div id="main">
            <?php require_once __DIR__."/../common/admin-header.php"; ?>

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
                                        <h6 class="my-2"><?= count($comments) ?> Bình luận</h6>
                                        <!-- <div class="btn-group">
                                            <button class="btn btn-outline-secondary btn-sm">
                                                <i class="fas fa-sort-alpha-down"></i> Mới nhất
                                            </button>
                                        </div> -->
                                    </div>

                                    <div class="card mb-0">
                                        <div class="card-body">
                                            <form class="input-group" action="/admin/blog/comment/search">
                                                <input type="hidden" name="blogid" value="<?= $id ?>">
                                                <input type="text" name="term" class="form-control" id="searchComments" placeholder="Search comments...">
                                                <button class="btn btn-outline-secondary" type="submit"><i class="fas fa-search"></i> Search</button>
                                            </form>
                                        </div>
                                    </div>
                                    
                                    <?php
                                        $i = 0;
                                        while ($i < count($comments)):
                                            $username = htmlspecialchars($commentUser[$i]);
                                            $commentDate = htmlspecialchars($comments[$i]->getCommentdate());
                                            $content = nl2br(htmlspecialchars($comments[$i]->getContent()));
                                            $blogId = (int) $id;
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
                                                        <button type="button" class="btn btn-outline-danger mt-2 delete-comment" blog-id="<?= $blogId ?>" postdate="<?= $commentDate ?>">
                                                            <i class="fas fa-trash"></i> Delete
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php
                                            $i++;
                                        endwhile;
                                    ?>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <a href="/admin/blog" class="btn btn-secondary me-2">Close</a>
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
                    <a href="#" class="btn btn-danger" id="confirmDeleteComment">Delete</a>
                </div>
            </div>
        </div>
    </div>

    <?php 
        require_once __DIR__."/../common/admin-script.php";
    ?>
    
    <script>
        // JavaScript for handling article and comment operations
        document.addEventListener('DOMContentLoaded', function() {            
            // Handle delete comment button clicks
            document.querySelectorAll('.delete-comment').forEach(button => {
                button.addEventListener('click', function() {
                    const blogId = this.getAttribute('blog-id');
                    const commentDate = this.getAttribute('postdate');
                    document.getElementById('confirmDeleteComment').href = `/admin/blog/comment/delete?blogid=${blogId}&commentdate=${commentDate}`;
                    // Show confirmation modal
                    new bootstrap.Modal(document.getElementById('deleteCommentModal')).show();
                });
            });
        });
    </script>
</body>
</html>