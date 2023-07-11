<?php 
namespace controllers;

use Exception;
use utils\Mail;
use utils\Flash;
use core\Controller;
use models\UserModel;
use models\CommentModel;

class CommentController extends Controller
{

    /**
     * Create a comment
     *
     * @param array $datas $_POST
     * @return void
     */
    public function create(array $datas) {
        try{
            // if($this->isUser()) {
                if($datas['#token'] !== $_SESSION['user']['token']) {
                    throw new Exception("Vous ne pouvez pas commenter");
                 }
                 $comment = new CommentModel();
                 if($comment->loadDatas($datas)->validate()) {
                    if($comment->createComment()) {
                        $mail = new Mail();
                        $message = $this->twig->render("templates/mail/moderation-mail.twig",["comment"=>$comment->comment,"url"=>BASE."dashboard"]);
                        $mail->mail(adress: $this->admin, subject : "Demande de modération", message :$message);
                        Flash::flash('success','Votre commentaire a était pris en compte');
                        $this->redirect(REF);
                    }
                    throw new Exception("Commentaire non pris en compte");
                    // Flash::flash('danger','Commentaire non pris en compte');
                    // $this->redirect('referer');
                 }
            // }

        }catch (Exception $e ){
            Flash::flash('danger',$e);
            $this->redirect(REF);
        }

    }

    /**
     * Edit a comment
     *
     * @param array $datas [string $content, string $token, string $commentid]
     * @return void
     */
    public function editComment(array $datas) {
        if($datas['#token'] !== $_SESSION['user']['token']) {
            throw new Exception("Vous ne pouvez pas commenter");
         }
        $comment = new CommentModel();
        if($comment->loadDatas($datas)->validate()) {
            if($comment->editComment($datas['#id'])) {
            Flash::flash("success","Vous avez bien modifié votre commentaire");
            $this->redirect('dashboard');
             }
        }
        Flash::flash("danger","Le commentaire n'a pas était trouvé");
        $this->redirect(REF);
   }
    /**
     * Get the comment to edit
     *
     * @param string $id
     * @return void
     */
    public function getComment(string $id) {
        $comment = new CommentModel();
        if($comment->oneCommment($id)) {
           return $this->twig->display("templates/edit-comment.twig",["comments" => $comment->single_comment]);
        }
        Flash::flash("danger","Le commentaire n'a pas était trouvé");
        $this->redirect(REF);
   }
        /**
         * list of the pending comments
         *
         * @return void
         */
        public function commentsLists (){
            $comments = new CommentModel();
            if($comments->pendingComments()){
                return $this->twig->display("templates/comments-to-moderate.twig",["pend_comments" => $comments->pending_comments]);
            }
            Flash::flash('danger',"Les commentaires sont introuvables");
            $this->redirect(REF);
        }
        /**
         * All the comments
         *
         * @return void
         */
        public function allComments (){
            $comments = new CommentModel();
            if($comments->all()){
                return $this->twig->display("templates/comments.twig",["comments" => $comments->comments]);
            }
            Flash::flash('danger',"Les commentaires sont introuvables");
            $this->redirect(REF);
        }

        /**
         * Accept a comment
         *
         * @param array $comment [string comment id,string token, string writer id]
         * @return void
         */
        public function accept (array $comment) {
            if($comment[1] !== $_SESSION['user']['token']) {
                throw new Exception("Vous ne pouvez pas modérer");
             }
             $accept = new CommentModel();
             if($accept->acceptComment($comment[0])){
                if($accept->single($comment[0])) {
                    $mail = new Mail();
                    $user = new UserModel();
                    $user->user($comment[2]);
                    $message = $this->twig->render("templates/mail/response-mail.twig",["comment"=>$accept->comment,"url" => BASE."blog","message"=>"Votre commentaire est en ligne","accept"]);
                    $mail->mail($user->user['email'],$message,"Votre commentaire est en ligne",$user->user["f_name"]);
                    Flash::flash('success','Ce commentaire est accepté');
                    $this->redirect('dashboard');
                }
                Flash::flash('danger',"Le mail d'information n'a pas était envoyé");
                $this->redirect('dashboard');
            }
            Flash::flash('danger',"Une erreur est survenue");
            $this->redirect('dashboard');
        }

        public function deleteComment(string $id) {
             $comment = new CommentModel();
             if($comment->deleteComment($id)) {
                Flash::flash('success',"Commentaire supprimé");
                $this->redirect(REF);
             }
             Flash::flash("danger","Le commentaire n'a pas était trouvé");
             $this->redirect(REF);

        }

        /**
         * Reject a comment
         *
         * @param array $comment
         * @return void
         */
        public function reject (array $comment){
             if($comment[1] !== $_SESSION['user']['token']) {
                throw new Exception("Vous ne pouvez pas modérer");
             }
             $reject = new CommentModel();
             if($reject->single($comment[0])) {
                if($reject->deleteComment($comment[0])){
                $mail = new Mail();
                $user = new UserModel();
                $user->user($comment[2]);
                $message = $this->twig->render("templates/mail/response-mail.twig",["comment"=>$reject->comment,"url" => BASE."blog","message"=>"Votre commentaire est refusé"]);
                $mail->mail($user->user['email'],$message,"Votre commentaire a été refusé",$user->user["f_name"]);
                Flash::flash('success','Ce commentaire a été refusé et supprimé');
                $this->redirect(REF);
             }
             Flash::flash('danger',"Le mail d'information n'a pas était envoyé");
             $this->redirect(REF);
        }
        Flash::flash('danger',"Une erreur est survenue");
        $this->redirect(REF);
    }

}