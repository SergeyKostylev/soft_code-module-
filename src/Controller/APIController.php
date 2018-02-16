<?php

namespace Controller;

use Framework\BaseController;
use Framework\Request;
use Framework\Session;

class APIController extends BaseController
{
    public function indexAction(Request $request)
    {
        header('Content-type: application/json');
        return json_encode(['a' => 1]);
    }

    public function showAmount(Request $request)
    {

        $idNews = $request->get('idnews');
        $showNow = $request->get('shownow');

        $allShowAmount = $this->getRepository('API')->findAllShowAmuont($idNews);
        $newAmount = $allShowAmount+$showNow;
        $this->getRepository('API')->setShowAmuont($idNews,$newAmount);
        header('Content-type: application/json');

        return json_encode(['amount' => $newAmount]);

    }

//       /api/dispatch

    public function dispatch(Request $request)
    {
        $email = $request->post('email');
        $name = $request->post('name');
        $duplicate = $this->getRepository('API')->checkDuplicateDispatchEntry($email);

        if(!$duplicate){
        $this->getRepository('API')->setDispatchEntry($email,$name);
        }
    }




///    /api/add/comment

    public function addComment(Request $request )
    {
        if (!Session::get('user')){
            http_response_code(201);
            return null;
        }
        $news_id = $request->post('newsid');
        $news = $this->getRepository('News')->findNews($news_id);

        $news_category = $news->getCategoryId();
        $allow_show = 1;
        if ($news_category == 1){
            $allow_show =0;
        }
        $user = $this->getRepository('User')->getUserByEmail(Session::get('user'));
        $comment_body = $request->post('commentbody');

        $id = $this->getRepository('Comment')->uploadComment($news_id,$user->getId(),$comment_body, $allow_show);
        $comment = $this->getRepository('Comment')->findCommentById($id);

        $answer = [
            'userId' => $user->getId(),
            'userEmail' => $user->getEmail(),
            'commentId' => $comment->getId(),
            'commentBody' => $comment->getBody(),
            'date' => $comment->getDate()
        ];

        http_response_code(200);
        header('Content-type: application/json');
        return json_encode($answer);



    }

//          /api/search
    public function search(Request $request )
    {
        $tegs =$this->getRepository('Tag')->findAll();
        $collection = [];
        $ids = [];
        $tagword =[];
        foreach ($tegs as $teg){
            $collection[$teg->getId()] = $teg->getWord();
            $ids[] = $teg->getId();
            $tagword [] =  $teg->getWord();
        }
        header('Content-type: application/json');

        return json_encode([
                            'collection' => $collection,
                            'ids' => $ids,
                            'tagword' => $tagword
        ]);

    }

    public function applause(Request $request)
    {
        $idComment = $request->post('commentid');
        $CommentBody = $request->post('commentbody');
        $this->getRepository('API')->applauseComment($idComment,$CommentBody);

    }
//      /api/likes
    public function likes(Request $request)
    {
        if ( !Session::get('user')){
            http_response_code(201);
            header('Content-type: application/json');
            return json_encode([
                'answer' => "Вы не вошли в аккаунт"
            ]);
        }
        $user_email = Session::get('user');
        $user = $this->getRepository('User')->getUserByEmail($user_email);
        $user_id = $user->getId();
        $comment_id = $request->post('commentid');
        $sense = $this->getRepository('Comment')->getMark($user_id, $comment_id);

        $comment = $this->getRepository('Comment')->findCommentById($comment_id);

        $likes_amount = $comment->getLikes();
        $dislikes_amount = $comment->getDislikes();

//        dump($sense);die;

        if($sense == null){
            $mark = 1;
            $this->getRepository('Comment')->insertMark($user_id,$comment_id,$mark);     //   РАСКОММЕНТИРОВАТЬ
            $likes_amount++;
            $this->getRepository('Comment')->updateLikes($comment_id,$likes_amount);    //  РАСКОММЕНТИРОВАТЬ
            http_response_code(200);
            header('Content-type: application/json');
            return json_encode([
                'likes' => $likes_amount,
                'dislikes' => $dislikes_amount,
                'answer' => "Вы поставили лайк"
            ]);
        }
        if($sense == 0){
            $mark = 1;
            $this->getRepository('Comment')->updateMark($user_id,$comment_id,$mark);      //  РАСКОММЕНТИРОВАТЬ
            $likes_amount++;
            $this->getRepository('Comment')->updateLikes($comment_id,$likes_amount);     // РАСКОММЕНТИРОВАТЬ
            http_response_code(200);
            header('Content-type: application/json');
            return json_encode([
                'likes' => $likes_amount,
                'dislikes' => $dislikes_amount,
                'answer' => "Вы поставили лайк"
            ]);
        }

        if($sense == 1){
            $mark = 0;
            $this->getRepository('Comment')->updateMark($user_id,$comment_id,$mark);      //  РАСКОММЕНТИРОВАТЬ
            $likes_amount--;
            $this->getRepository('Comment')->updateLikes($comment_id,$likes_amount);     // РАСКОММЕНТИРОВАТЬ
            header('Content-type: application/json');
            return json_encode([
                'likes' => $likes_amount,
                'dislikes' => $dislikes_amount,
                'answer' => "Вы отменили свой лайк"
            ]);
        }
        if($sense == -1){
            $mark = 1;
            $this->getRepository('Comment')->updateMark($user_id,$comment_id,$mark);      //  РАСКОММЕНТИРОВАТЬ
            $likes_amount++;
            $dislikes_amount--;
            $this->getRepository('Comment')->updateLikes($comment_id,$likes_amount);     // РАСКОММЕНТИРОВАТЬ
            $this->getRepository('Comment')->updateDislikes($comment_id,$dislikes_amount);     // РАСКОММЕНТИРОВАТЬ
            http_response_code(200);
            header('Content-type: application/json');
            return json_encode([
                'likes' => $likes_amount,
                'dislikes' => $dislikes_amount,
                'answer' => "Вы отменили свой дизлайк и поставили лайк"
            ]);
        }
    }







//      /api/dislikes
    public function dislikes(Request $request)
    {
        if ( !Session::get('user')){
            http_response_code(201);
            header('Content-type: application/json');
            return json_encode([
                'answer' => "Вы не вошли в аккаунт"
            ]);
        }
        $user_email = Session::get('user');
        $user = $this->getRepository('User')->getUserByEmail($user_email);
        $user_id = $user->getId();
        $comment_id = $request->post('commentid');
        $sense = $this->getRepository('Comment')->getMark($user_id, $comment_id);

        $comment = $this->getRepository('Comment')->findCommentById($comment_id);

        $likes_amount = $comment->getLikes();
        $dislikes_amount = $comment->getDislikes();

//                dump($sense);die;

        if($sense == null){
            $mark = -1;
            $this->getRepository('Comment')->insertMark($user_id,$comment_id,$mark);     //   РАСКОММЕНТИРОВАТЬ
            $dislikes_amount++;
            $this->getRepository('Comment')->updateDislikes($comment_id,$dislikes_amount);    //  РАСКОММЕНТИРОВАТЬ
            http_response_code(200);
            header('Content-type: application/json');
            return json_encode([
                'likes' => $likes_amount,
                'dislikes' => $dislikes_amount,
                'answer' => "Вы поставили дизлайк"
            ]);
        }
        if($sense == 0){
            $mark = -1;
            $this->getRepository('Comment')->updateMark($user_id,$comment_id,$mark);      //  РАСКОММЕНТИРОВАТЬ
            $dislikes_amount++;
            $this->getRepository('Comment')->updateDislikes($comment_id,$dislikes_amount);     // РАСКОММЕНТИРОВАТЬ
            http_response_code(200);
            header('Content-type: application/json');
            return json_encode([
                'likes' => $likes_amount,
                'dislikes' => $dislikes_amount,
                'answer' => "Вы поставили дизлайк"
            ]);
        }
        if($sense == 1){
            $mark = -1;
            $this->getRepository('Comment')->updateMark($user_id,$comment_id,$mark);      //  РАСКОММЕНТИРОВАТЬ
            $likes_amount--;
            $dislikes_amount++;
            $this->getRepository('Comment')->updateLikes($comment_id,$likes_amount);     // РАСКОММЕНТИРОВАТЬ
            $this->getRepository('Comment')->updateDislikes($comment_id,$dislikes_amount);     // РАСКОММЕНТИРОВАТЬ
            header('Content-type: application/json');
            return json_encode([
                'likes' => $likes_amount,
                'dislikes' => $dislikes_amount,
                'answer' => "Вы отменили свой лайк и поставили дизлайк"
            ]);
        }
        if($sense == -1){
            $mark = 0;
            $this->getRepository('Comment')->updateMark($user_id,$comment_id,$mark);      //  РАСКОММЕНТИРОВАТЬ
            $dislikes_amount--;
            $this->getRepository('Comment')->updateDislikes($comment_id,$dislikes_amount);     // РАСКОММЕНТИРОВАТЬ
            http_response_code(200);
            header('Content-type: application/json');
            return json_encode([
                'likes' => $likes_amount,
                'dislikes' => $dislikes_amount,
                'answer' => "Вы отменили свой дизлайк"
            ]);
        }







    }

}