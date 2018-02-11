<?php

namespace Controller;

use Framework\BaseController;
use Framework\Request;
use Framework\Session;



class DefaultController extends BaseController
{
    public function indexAction(Request $request)
    {
        $categorys = $this->getRepository('category')->findAll();
        $analiticNews = array_slice($this->getRepository('news')->analiticNews(), 0, 5);
        $collection['Аналитика'] = $analiticNews;

        foreach ($categorys as $category){
            $category_id = $category->getId();
            $news = $this->getRepository('news')->findFiveLastNews($category_id);
            $collection[$category->getName()] = $news;
        }
        $lastFour = $this->getRepository('news')->findLastFour();
        foreach ($collection as $key => $new){
            if (($new == null)){unset($collection[$key]);}
        }


        return $this->render('index.html.twig',
            ['collectionNews' => $collection,
                'lastFour' => $lastFour
            ]);
    }

}