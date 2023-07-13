<?php 
namespace controllers;

use \core\Controller;
use models\BlogModel;
use models\CommentModel;

class BlogController extends Controller
{


    /**
     * Display the blog home page
     *
     * @return void
     */
      public function index()
      {
        $datas = new BlogModel();
        $datas = $datas->index();
        return $this->twig->display('blog.twig',['datas' => $datas]);
      }


      /**
       * Get a single post
       *
       * @param string $post_id 
       * @return void
       */
      public function single(string $post_id)
      {
        $datas = new BlogModel();
        $datas = $datas->single($post_id);
        $comments = new Commentmodel();
        $comments->fetch($post_id);
        return $this->twig->display('post.twig',['datas' => $datas, 'comments' => $comments->comments]);

      }

}
