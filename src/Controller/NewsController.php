<?php

namespace Controller;

use Framework\BaseController;
use Framework\Request;
use Framework\Session;
use Model\Pagination\Pagination;


class NewsController extends BaseController
{
    const NEWS_PER_PAGE = 5;

    public function indexAction(Request $request)
    {
        return $this->render('index.html.twig');
    }


    public function newsByCategoryAction(Request $request )
    {
        $page = $request->get('page', 1);

        $category_id = $request->get('id');
        $category = $this->getRepository('category')->findByID($category_id);
        if (!$category) {
            throw new \Exception('Категория отсутствует');
        }
        $repo = $this->getRepository('News');

        $count = $repo->count($category_id);

        $news = $repo
            ->newsDyCategory
            ($category_id,
            [
            'current_page' => $page,
                'items_on_page' => self::NEWS_PER_PAGE
            ]);

        $pagination = new Pagination([
            'itemsCount' => $count,
            'itemsPerPage' => self::NEWS_PER_PAGE,
            'currentPage' => $page
        ]);


        return $this->render('news_by_category.html.twig', [
            'category' => $category,
            'news_collection' => $news,
            'pagination' => $pagination
            ]);

    }

    public function newsByTagAction(Request $request)
    {
        $page = $request->get('page', 1);

        $tag_id = $request->get('id');
        $tag = $this->getRepository('tag')->findById($tag_id);

        if (!$tag) {
            throw new \Exception('Такой тег не существует');
        }
        $repo = $this->getRepository('News');

        $count = $repo->countTagNews($tag_id);



        $news = $repo
            ->newsDyTag
            ($tag_id,
                [
                    'current_page' => $page,
                    'items_on_page' => self::NEWS_PER_PAGE
                ]);

        $pagination = new Pagination([
            'itemsCount' => $count,
            'itemsPerPage' => self::NEWS_PER_PAGE,
            'currentPage' => $page
        ]);

        return $this->render('news_by_tag.html.twig', [
            'tag' => $tag,
            'news_collection' => $news,
            'pagination' => $pagination
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

        return $this->
            render('show.html.twig', [
            'news' => $news,
            'category' => $category
            ]);


    }


    public function analiticNewsAction(Request $request )
    {
        $page = $request->get('page', 1);


        $analiticNews = $this->getRepository('news')->analiticNews();
        if (!$analiticNews) {
            throw new \Exception('Аналитические статьи отсутствуют');
        }
        $repo = $this->getRepository('News');


        $count = $repo->countAnaliticNews();

        $news = $repo
            ->analiticNews
            ([
                    'current_page' => $page,
                    'items_on_page' => self::NEWS_PER_PAGE
                ]);
        $pagination = new Pagination([
            'itemsCount' => $count,
            'itemsPerPage' => self::NEWS_PER_PAGE,
            'currentPage' => $page
        ]);
        $a= $this->getRepository('News')->analiticNews();

        return $this->render('analitic_news.html.twig', [
            'news_collection' => $news,
            'pagination' => $pagination
        ]);

    }

}