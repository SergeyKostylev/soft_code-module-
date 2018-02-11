<?php

namespace Controller;

use Framework\BaseController;
use Framework\Request;
use Framework\Session;
use GuzzleHttp\Client;
use Model\Entity\User;

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

        $allShowAmount = $this->getRepository('api')->findAllShowAmuont($idNews);
        $newAmount = $allShowAmount+$showNow;
        $this->getRepository('api')->setShowAmuont($idNews,$newAmount);
        header('Content-type: application/json');

        return json_encode(['amount' => $newAmount]);

    }


    public function addComment(Request $request )
    {
        if (!Session::get('user')){
            return null;
        }
        $news_id = $request->post('newsid');
        $news = $this->getRepository('news')->findNews($news_id);

        $news_category = $news->getCategoryId();
        $allow_show = 1;
        if ($news_category == 1){
            $allow_show =0;
        }

        $user = $this->getRepository('user')->getUserByEmail(Session::get('user'));
        $comment_body = $request->post('commentbody');


        $this->getRepository('comment')->uploadComment($news_id,$user->getId(),$comment_body, $allow_show);


    }

}