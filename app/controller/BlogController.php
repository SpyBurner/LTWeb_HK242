<?php
namespace controller;
use Core\Controller;
use service\AuthService;
use Service\BlogPostService;
use Service\CommentService;
use service\UserService;
use core\SessionHelper;

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

    public function search() {
        $keyword = $_GET['keyword'];
        $result = BlogPostService::searchBlogByKeyword($keyword);
        if (!$result['success']){
            $this->redirectWithMessage('/blog', $result['message']);
        }

        $posts = $result['data'];

        require_once __DIR__ . "/../view/news/news.php";
    }

    public function getBlogInfo() {
        $id = $_GET['id'];
        if ($_SERVER['REQUEST_METHOD'] === 'POST'){
            SessionHelper::setFlash('return_to', "/blog/view?id=$id");

            $this->requireAuth();

            $result = AuthService::validateSession();

            $userid = $result['user']['userid'];
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
                $user = UserService::findById($comment->getUserid());
                return [
                    'username' => $user['data']->getUsername(),
                    'avatar' => $user['avatar']
                ];
                // return UserService::findById($comment->getUserid())['data']->getUsername();    
            }, $comments);

            $likes = BlogPostService::allLikesByBlogId($id);
            if (!$likes['success']){
                $this->redirectWithMessage('/blog', $likes['message']);
            }
            $likes = $likes['data'];

            $isLiked = false;
            $authResult = AuthService::validateSession();
            if ($authResult['success']) {
                $userid = $authResult['user']['userid'];
                $likeStatus = BlogPostService::checkLiked($userid, $id);
                if ($likeStatus['success']) {
                    $isLiked = $likeStatus['data'];
                }
            }

            $related_posts = BlogPostService::findAllRelatedPosts($id)['data'];
            
            if (isset($_SESSION['REQUEST_METHOD']) && $_SESSION['REQUEST_METHOD']  === 'POST'){
                echo "TODO HANDLE POST REQUEST AT BLOGCONTROLLER";
            }

            require_once __DIR__ . "/../view/news/news-post.php";
        }
    }

    public function handleLike() {
        $this->requireAuth();
        
        $result = AuthService::validateSession();

        $userid = $result['user']['userid'];

        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        $blogid = $data['blogid'];

        $result = BlogPostService::addLike($userid, $blogid);
        if (!$result['success']){
            $this->redirectWithMessage('/blog', $result['message']);
        }
    }
}