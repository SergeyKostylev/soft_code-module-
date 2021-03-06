<?php

namespace Controller;

use Framework\BaseController;
use Framework\Request;
use Framework\Session;



class DefaultController extends BaseController
{
    public function indexAction(Request $request)
    {
        $categorys = $this->getRepository('Category')->findAll();
        $analiticNews = array_slice($this->getRepository('News')->analiticNews(), 0, 5);
        $collection['Аналитика'] = $analiticNews;

        foreach ($categorys as $category){
            $category_id = $category->getId();
            $news = $this->getRepository('News')->findFiveLastNews($category_id);
            $collection[$category->getName()] = $news;
        }
        $lastFour = $this->getRepository('News')->findLastFour();
        foreach ($collection as $key => $new){
            if (($new == null)){unset($collection[$key]);}
        }

        $top_five_users_by_comments = $this->getRepository('User')->topFiveUsersByComments();
        $top_tree_discussion_news = $this->getRepository('News')->topTreeDiscussionNews();

        return $this->render('index.html.twig',
            ['collectionNews' => $collection,
                'lastFour' => $lastFour,
                'topFiveUsersByComment' => $top_five_users_by_comments,
                'topTreeDiscussionNews' => $top_tree_discussion_news
            ]);
    }

}