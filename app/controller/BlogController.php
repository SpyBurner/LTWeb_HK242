<?php
namespace controller;
use Core\Controller;
use Dom\Comment;
use Service\BlogPostService;
use Service\CommentService;
use service\UserService;

class BlogController extends Controller {
    public function index() {
        $result = BlogPostService::findAll();
        if (!$result['success']){
            $this->redirectWithMessage('/blog', $result['message']);
        }

        $posts = $result['data'];
        if (isset($_SESSION['REQUEST_METHOD']) && $_SESSION['REQUEST_METHOD']  === 'POST'){
            echo "TODO HANDLE POST REQUEST AT BLOGCONTROLLER";
        }

        require_once __DIR__ . "/../view/news/news.php";
    }

    public function getBlogInfo() {
        $id = $_GET['id'];
        if ($_SERVER['REQUEST_METHOD'] === 'POST'){
            $userid = $_SESSION['userid'];
            $content = $_POST['content'];

            $model = CommentService::createInstance($id, $userid, $content);
            $result = CommentService::save($model);
            if (!$result['success']){
                $this->redirectWithMessage('/blog', $result['message']);
            }
            $this->redirectWithMessage('/blog/view?id=' . $id, $result['message']);
        }
        else {
            $blog_info = BlogPostService::findById($id);
            if (!$blog_info['success']){
                $this->redirectWithMessage('/blog', $blog_info['message']);
            }
            $blog_info = $blog_info['data'];

            $comments = CommentService::findByBlogId($id);
            if (!$comments['success']){
                $this->redirectWithMessage('/blog', $comments['message']);
            }
            $comments = $comments['data'];

            $commentUser = array_map(function($comment) {
                return UserService::findById($comment->getUserid())['data']->getUsername();    
            }, $comments);

            $likes = BlogPostService::allLikesByBlogId($id);
            if (!$likes['success']){
                $this->redirectWithMessage('/blog', $likes['message']);
            }
            $likes = $likes['data'];

            $related_posts = BlogPostService::findAllRelatedPosts($id)['data'];
            
            if (isset($_SESSION['REQUEST_METHOD']) && $_SESSION['REQUEST_METHOD']  === 'POST'){
                echo "TODO HANDLE POST REQUEST AT BLOGCONTROLLER";
            }

            require_once __DIR__ . "/../view/news/news-post.php";
        }
    }


}