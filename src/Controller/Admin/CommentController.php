<?php

namespace Controller\Admin;

use Framework\BaseController;
use Framework\Request;

class CommentController extends BaseController
{

    public function allCommentsAction(Request $request)
    {
        $comments_invisible = $this->getRepository('Comment')->findUnconfirmedComments();

        $comments_visible = $this->getRepository('Comment')->findVisibleComments();


        $users = $this->getRepository('User')->findMasAll();
        return $this->render('permit_comment.html.twig',
            ['commentsInvisible' => $comments_invisible,
                'commentsVisible' => $comments_visible,
                'users' => $users
            ]);
    }


}