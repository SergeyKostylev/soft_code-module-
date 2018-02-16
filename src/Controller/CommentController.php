<?php

namespace Controller;

use Framework\BaseController;
use Framework\Request;
use Model\Entity\User;
use Model\Pagination\Pagination;


class CommentController extends BaseController
{
    const COMMENTS_PER_PAGE = 5;

    public function indexAction(Request $request)
    {

        $page = $request->get('page', 1);

        $user_id = $request->get('id');
        $user = $this->getRepository('User')->findById($user_id);
        if (!$user) {
            throw new \Exception('Пользователь не найден');
        }

        $repo = $this->getRepository('Comment');

        $count = $repo->countUserComment($user_id);
        $comments = $repo
            ->finbByUserID
            ($user_id,
                [
                    'current_page' => $page,
                    'items_on_page' => self::COMMENTS_PER_PAGE
                ]);
        $pagination = new Pagination([
            'itemsCount' => $count,
            'itemsPerPage' => self::COMMENTS_PER_PAGE,
            'currentPage' => $page
        ]);





        return $this->render('show_comments.html.twig', [
            'user' => $user,
            'comments' => $comments,
            'pagination' => $pagination
        ]);


    }


}