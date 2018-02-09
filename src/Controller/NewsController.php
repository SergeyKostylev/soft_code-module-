<?php

namespace Controller;

use Framework\BaseController;
use Framework\Request;


class NewsController extends BaseController
{
    public function indexAction(Request $request)
    {
        return $this->render('index.html.twig');
    }

    public function newsByCategoryAction(Request $request )
    {
        $category_id = $request->get('id');
        $category = $this->getRepository('category')->findByID($category_id);
        if (!$category) {
            throw new \Exception('Категория отсутствует');
        }
        $news = $this->getRepository('news')->newsDyCategory($category_id);

        return $this->render('news_by_category.html.twig',
            ['category' => $category,
                'news_collection' => $news
            ]);

    }

    public function paperShowAction(Request $request)
    {
        $paper_id = $request->get('id');
        $news = $this->getRepository('news')->findNews($paper_id);
        if (!$news) {
            throw new \Exception('Новость не найдена');
        }
        $category = $this->getRepository('category')->findByID($news->getCategoryId());

//        dump($news);
//        dump($category);
        return $this->
            render('show.html.twig', [
            'news' => $news,
            'category' => $category
            ]);


    }




//$new_arr = array_diff($arr, array(0, null));

}