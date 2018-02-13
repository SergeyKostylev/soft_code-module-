<?php

namespace Controller\Admin;

use Framework\BaseController;
use Framework\Request;

class CommentController extends BaseController
{

    public function allCommentsAction(Request $request)
    {
        $comments_invisible = $this->getRepository('comment')->findUnconfirmedComments();

        $comments_visible = $this->getRepository('comment')->findVisibleComments();


        $users = $this->getRepository('user')->findMasAll();
        return $this->render('permit_comment.html.twig',
            ['commentsInvisible' => $comments_invisible,
                'commentsVisible' => $comments_visible,
                'users' => $users
            ]);
    }


}