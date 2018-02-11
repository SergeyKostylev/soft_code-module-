<?php

namespace Model\Repository;

//use Model\Entity\Category;

use Framework\Session;
use Model\Entity\News;

class NewsRepository
{
    protected $pdo;

    public function setPdo(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }



    public function add($name,$category,$news_bod,$title_image, $analitic)
    {

        $sth = $this->pdo->prepare('INSERT INTO `news` (
                                    `id`,
                                    `name`,
                                    `category_id`,
                                    `news_body`,
                                    `title_image`,
                                    `create_data`,
                                    `show_amount`,
                                    `analitic`)
                                    VALUES (NULL, :news_name, :category_id, :news_body, :title_image, CURRENT_TIMESTAMP, :show_amount, :analitic);');
        $sth->execute([
            'news_name' => $name,
            'category_id' => $category,
            'news_body' => $news_bod,
            'title_image' => $title_image,
            'show_amount' => 0 ,
            'analitic' => $analitic
        ]);

    }

    public function analiticNews()
    {
        $collection=[];

        $sth = $this->pdo->query('select* from news WHERE analitic = 1;');
        $res = $sth->fetch(\PDO::FETCH_ASSOC);
        if (!$res)
            return null;
        while ($res = $sth->fetch(\PDO::FETCH_ASSOC)){
            $news = (new News())
                ->setId($res['id'])
                ->setName($res['name'])
                ->setCategoryId($res['category_id'])
                ->setNewsBody($res['news_body'])
                ->setTitleImage($res['title_image'])
                ->setCreteDate($res['create_data'])
                ->setShowAmount($res['show_amount'])
                ->setAnalitic($res['analitic'])
            ;
            $collection[]= $news;
//            $collection
        }
        return $collection;

    }




    public function findFiveLastNews($categoryId)
    {
        $collection=[];

        $sth = $this->pdo->prepare('SELECT  *  From news
                                      WHERE category_id= :id ORDER BY create_data ASC LIMIT 5;');
        $sth->execute([
                'id' => $categoryId
        ]);
        $res = $sth->fetch(\PDO::FETCH_ASSOC);
        if (!$res)
            return null;
        while ($res = $sth->fetch(\PDO::FETCH_ASSOC)){
            $news = (new News())
                ->setId($res['id'])
                ->setName($res['name'])
                ->setCategoryId($res['category_id'])
                ->setNewsBody($res['news_body'])
                ->setTitleImage($res['title_image'])
                ->setCreteDate($res['create_data'])
                ->setShowAmount($res['show_amount'])
                ->setAnalitic($res['analitic'])
            ;
            $collection[]= $news;
        }


        return $collection;

    }

    public function findLastFour()
    {

        $topTwo=[];

        $sth = $this->pdo->query('SELECT * FROM news ORDER BY create_data DESC LIMIT 4;');

        $res = $sth->fetch(\PDO::FETCH_ASSOC);
        if (!$res)
            return null;
        while ($res = $sth->fetch(\PDO::FETCH_ASSOC)){
            $news =(new News())
                ->setId($res['id'])
                ->setName($res['name'])
                ->setCategoryId($res['category_id'])
                ->setNewsBody($res['news_body'])
                ->setTitleImage($res['title_image'])
                ->setCreteDate($res['create_data'])
                ->setShowAmount($res['show_amount'])
            ;
            $topTwo[]=$news;
        }
        return $topTwo;

    }


    public function newsDyCategory($category_id, array $options = [], $hydrationArray = false)
    {
        $limitSql = '';

        if (isset($options['current_page']) && isset($options['items_on_page'])) {
            $page = $options['current_page'] - 1;
            $count = $page * $options['items_on_page'];
            $limitSql = "limit {$count}, {$options['items_on_page']}";
        }

        $collection=[];


        $sth = $this->pdo->prepare('SELECT * From news WHERE category_id = :id '. $limitSql .';');
        $sth->execute([
            'id' => $category_id
        ]);
        $res = $sth->fetch(\PDO::FETCH_ASSOC);
        if (!$res)
            return null;

        if ($hydrationArray) {
            return $res = $sth->fetchAll(\PDO::FETCH_ASSOC);
        }


        while ($res = $sth->fetch(\PDO::FETCH_ASSOC)){
            $category =(new News())
                ->setId($res['id'])
                ->setName($res['name'])
                ->setCategoryId($res['category_id'])
                ->setNewsBody($res['news_body'])
                ->setTitleImage($res['title_image'])
                ->setCreteDate($res['create_data'])
                ->setShowAmount($res['show_amount'])
                ->setAnalitic($res['analitic'])
            ;
            $collection[]=$category;
        }

        return $collection;

    }


    public  function findNews($id)
    {
        $sth = $this->pdo->prepare('SELECT * From news WHERE id = :id');
        $sth->execute(['id' => $id]);
        $res = $sth->fetch(\PDO::FETCH_ASSOC);
        if (!$res)
            return null;

        $news_body = $res['news_body'];
        $prepareText='';
        $sentences_array =preg_split("/[.?!] |[.?!]|\.{3,}/", $news_body);
        $sentences_array = array_splice($sentences_array,0,5);

        foreach ($sentences_array as $item){
            $prepareText.= $item . '. ';
         }

         if (!(Session::get('user'))){
             $res['news_body'] = $prepareText;
        }

        return (new News())
            ->setId($res['id'])
            ->setName($res['name'])
            ->setCategoryId($res['category_id'])
            ->setNewsBody($res['news_body'])
            ->setTitleImage($res['title_image'])
            ->setCreteDate($res['create_data'])
            ->setShowAmount($res['show_amount'])
            ->setAnalitic($res['analitic']);

    }

    public function count($category_id)
    {
        $sth = $this->pdo->prepare('select count(*) as count from news WHERE category_id= :id');
        $sth->execute(['id' => $category_id]);
        return $sth->fetchColumn();
    }




}