<?php
namespace controller;


// use core\Controller;
use Service\BlogPostService;
use Service\CommentService;
use service\UserService;
use service\AuthService;

class AdminController extends BaseController {
    public function index() {
        if (isset($_SESSION['REQUEST_METHOD']) && $_SESSION['REQUEST_METHOD']  === 'POST'){
            echo "TODO HANDLE POST REQUEST AT AdminController";
        }
        else{
            require_once __DIR__ . "/../view/admin/dashboard.php";
        }
    }

    public function contact(){
        if (isset($_SESSION['REQUEST_METHOD']) && $_SESSION['REQUEST_METHOD']  === 'POST'){
            echo "TODO HANDLE POST REQUEST AT AdminController";
        }
        else{
            require_once __DIR__ . "/../view/admin/contact.php";
        }
    }

    public function qna(){
        if (isset($_SESSION['REQUEST_METHOD']) && $_SESSION['REQUEST_METHOD']  === 'POST'){
            echo "TODO HANDLE POST REQUEST AT AdminController";
        }
        else{
            require_once __DIR__ . "/../view/admin/qna.php";
        }
    }

    // blog management
    public function getAllBlog(){
        $result = BlogPostService::findAll();
        if (!$result['success']){
            $this->redirectWithMessage('/blog', $result['message']);
        }

        $posts = $result['data'];
        $authors = array_map(function($post) {
            return BlogPostService::findAuthorById($post->getAdminid())['data'];
        }, $posts);

        $likes = array_map(function($post) {
            return BlogPostService::allLikesByBlogId($post->getBlogid())['data'];
        }, $posts);

        if (isset($_SESSION['REQUEST_METHOD']) && $_SESSION['REQUEST_METHOD']  === 'POST'){
            echo "TODO HANDLE POST REQUEST AT BLOGCONTROLLER";
        }

        $this->render('admin/bloglist', [
            'posts' => $posts,
            'authors' => $authors,
            'likes' => $likes,
        ]);
    }

    public function getCommentsByBlogid() {
        $id = $_GET['blogid'];

        $comments = CommentService::findByBlogId($id);
        if (!$comments['success']){
            $this->redirectWithMessage('/blog', $comments['message']);
        }
        $comments = $comments['data'];

        $commentUser = array_map(function($comment) {
            return UserService::findById($comment->getUserid())['data']->getUsername();    
        }, $comments);

        $this->render('admin/blog-comment', [
            'id' => $id,
            'comments' => $comments,
            'commentUser' => $commentUser,
        ]);
    }

    public function deleteComment() {
        $blogid = $_GET['blogid'];
        $userid = $_GET['userid'];
        $commentdate = $_GET['commentdate'];
        $result = CommentService::deleteComment($blogid, $userid, $commentdate);
        if (!$result['success']){
            $this->redirectWithMessage('/blog', $result['message']);
        }

        $this->redirectWithMessage('/admin/blog/comment?blogid='.$blogid, $result['message']);
    }

    public function deleteBlog() {
        $blogid = $_GET['blogid'];
        $result = BlogPostService::deleteById($blogid);
        if (!$result['success']){
            $this->redirectWithMessage('/blog', $result['message']);
        }

        $this->redirectWithMessage('/admin/blog', $result['message']);
    }

    public function createBlog() {
        // if (isset($_SESSION['REQUEST_METHOD']) && $_SESSION['REQUEST_METHOD']  === 'POST'){
        if ($_SERVER['REQUEST_METHOD'] === 'POST'){
            $title = $_POST['title'];
            $content = $_POST['content'];
            
            $result = AuthService::validateSession();
            $adminid = $result['user']['userid'];

            $result = BlogPostService::save(BlogPostService::createInstance(null, $title, $content, $adminid));
            if (!$result['success']){
                $this->redirectWithMessage('/blog', $result['message']);
            }
            $this->redirectWithMessage('/admin/blog', $result['message']);
        }
        else{
            $this->render('admin/create-post');
        }
    }

    public function getPostInfo() {
        $id = $_GET['blogid'];
        $result = BlogPostService::findById($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST'){
            $title = $_POST['title'];
            $content = $_POST['content'];
            $result = BlogPostService::save(BlogPostService::createInstance($id, $title, $content, $result['data']->getAdminid()));
            if (!$result['success']){
                $this->redirectWithMessage('/blog', $result['message']);
            }
            $this->redirectWithMessage('/admin/blog/edit?blogid='.$id, $result['message']);
        }
        else {
            if (!$result['success']){
                $this->redirectWithMessage('/blog', $result['message']);
            }
            $post = $result['data'];
            $admin = UserService::findById($post->getAdminid())['data']->getUsername();

            $this->render('admin/edit-post', [
                'post' => $post,
                'admin' => $admin,
            ]);
        }
    }

    public function searchBlog() {
        $search = $_GET['term'];
        $result = BlogPostService::searchTitle($search);
        if (!$result['success']){
            $this->redirectWithMessage('/blog', $result['message']);
        }

        $posts = $result['data'];
        $authors = array_map(function($post) {
            return BlogPostService::findAuthorById($post->getAdminid())['data'];
        }, $posts);

        $likes = array_map(function($post) {
            return BlogPostService::allLikesByBlogId($post->getBlogid())['data'];
        }, $posts);

        $this->render('admin/bloglist', [
            'posts' => $posts,
            'authors' => $authors,
            'likes' => $likes,
        ]);
    }

    public function searchComment() {
        $blogid = $_GET['blogid'];
        $search = $_GET['term'];
        $result = CommentService::searchComment($blogid, $search);
        if (!$result['success']){
            $this->redirectWithMessage('/blog', $result['message']);
        }
        
        $id = $blogid;
        $comments = $result['data'];
        
        $commentUser = array_map(function($comment) {
            return UserService::findById($comment->getUserid())['data']->getUsername();    
        }, $comments);
        
        $this->render('admin/blog-comment', [
            'id' => $id,
            'comments' => $comments,
            'commentUser' => $commentUser,
        ]);
    }
}