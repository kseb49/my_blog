<?php

namespace controllers;

use core\Auth;
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
    public function create(array $datas)
    {
        try {
            if (Auth::checkToken($datas['#token']) === false) {
                throw new Exception("Vous ne pouvez pas commenter");
            }

            $comment = new CommentModel();
            if ($comment->loadDatas($datas)->validate() === true) {
                if ($comment->createComment() === true) {
                    $mail = new Mail();
                    $message = $this->twig->render("templates/mail/moderation-mail.twig",["comment" => $comment->comment, "url" => BASE."dashboard"]);
                    $mail->mail(adress: $this->admin, subject : "Demande de modération", message :$message);
                    Flash::flash('success','Votre commentaire a était pris en compte');
                    $this->redirect(REF);
                }
                throw new Exception("Commentaire non pris en compte");
            }
        } catch (Exception $e){
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
    public function editComment(array $datas)
    {
        try {
            if (Auth::checkToken($datas['#token']) === false) {
                throw new Exception("Vous ne pouvez pas commenter");
            }
            $comment = new CommentModel();
            $comment->loadDatas($datas)->validate();
            if ($comment->editComment($datas['#id']) === true) {
                $comment->single($datas['#id']);
                $mail = new Mail();
                $message = $this->twig->render("templates/mail/moderation-mail.twig",["comment" => $comment->comment, "url" => BASE."dashboard"]);
                $mail->mail(adress: $this->admin, subject : "Demande de modération", message :$message);
                Flash::flash("success","Vous avez bien modifié votre commentaire, il est en attende de modération");
                $this->redirect('dashboard');
            }
            throw new Exception("Le commentaire n'a pas été modifié");
        } catch (Exception $e) {
            Flash::flash("danger",$e->getMessage());
            $this->redirect(REF);
        }

    }


    /**
     * Get the comment to edit
     *
     * @param string $com_id
     * @return void
     */
    public function getComment(string $com_id)
    {
        $comment = new CommentModel();
        if ($comment->oneCommment($com_id) === true) {
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
    public function commentsLists()
    {
        try {
            $comments = new CommentModel();
            if ($comments->pendingComments() === true) {
                return $this->twig->display("templates/comments-to-moderate.twig",["pend_comments" => $comments->pending_comments]);
            }
            throw new Exception("Il n'y a pas de commentaires en  attente");
        }catch (Exception $e) {
            Flash::flash('danger',$e->getMessage());
            $this->redirect('dashboard');
        }

    }


    /**
     * All the comments
     *
     * @return void
     */
    public function allComments()
    {
        $comments = new CommentModel();
        if ($comments->all() === true) {
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
    public function accept(array $comment)
    {
        try {
            if (Auth::checkToken($comment[1]) === false) {
                throw new Exception("Vous ne pouvez pas modérer");
            }
            $accept = new CommentModel();
            if ($accept->acceptComment($comment[0]) === true) {
                if ($accept->single($comment[0]) === true) {
                    $mail = new Mail();
                    $user = new UserModel();
                    $user->user($comment[2]);
                    $message = $this->twig->render("templates/mail/response-mail.twig",["comment" => $accept->comment, "url" => BASE."blog", "message" => "Votre commentaire est en ligne", "accept"]);
                    $mail->mail($user->user['email'],$message,"Votre commentaire est en ligne",$user->user["f_name"]);
                    Flash::flash('success','Ce commentaire est accepté');
                    $this->redirect('dashboard');
                }
                throw new Exception("Le mail d'information n'a pas était envoyé");
            }
            throw new Exception("Une erreur est survenue");
        } catch (Exception $e) {
            Flash::flash('danger',$e->getMessage());
            $this->redirect('dashboard');
        }

    }


    /**
     * Delete a comment
     *
     * @param array $params $params[0] == comment_id
     * @return void
     */
    public function deleteComment(array $params)
    {
        try {
            if (Auth::checkToken($params[1]) === false) {
                throw new Exception("Vous ne pouvez pas effacer ce commentaire");
            }
            $comment = new CommentModel();
            if ($comment->deleteComment($params[0]) === true) {
                Flash::flash('success',"Commentaire supprimé");
                $this->redirect(REF);
            }
            throw new Exception("Le commentaire n'a pas était trouvé");
        } catch (Exception $e) {
            Flash::flash('danger',$e->getMessage());
            $this->redirect('dashboard');
        }

    }


    /**
     * Reject a comment
     *
     * @param array $comment
     * @return void
     */
    public function reject(array $comment)
    {
        try {
            if (Auth::checkToken($comment[1]) === false) {
            throw new Exception("Vous ne pouvez pas modérer");
            }
            $reject = new CommentModel();
            if ($reject->single($comment[0]) === true) {
                if ($reject->deleteComment($comment[0]) === true) {
                    $mail = new Mail();
                    $user = new UserModel();
                    $user->user($comment[2]);
                    $message = $this->twig->render("templates/mail/response-mail.twig",["comment" => $reject->comment, "url" => BASE."blog", "message" => "Votre commentaire est refusé"]);
                    $mail->mail($user->user['email'],$message,"Votre commentaire a été refusé",$user->user["f_name"]);
                    Flash::flash('success','Ce commentaire a été refusé et supprimé');
                    $this->redirect(REF);
                }
                throw new Exception("Le mail d'information n'a pas était envoyé");
            }
            throw new Exception("Une erreur est survenue");
        } catch (Exception $e) {
            Flash::flash('danger',$e->getMessage());
            $this->redirect('dashboard');
        }

    }


}
