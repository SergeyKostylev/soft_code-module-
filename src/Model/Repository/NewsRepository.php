<?php

namespace Model\Repository;

//use Model\Entity\Category;

use Model\Entity\News;

class NewsRepository
{
    protected $pdo;

    public function setPdo(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }



    public function add($name,$category,$news_bod,$title_image)
    {
        $sth = $this->pdo->prepare('INSERT INTO `news` (
                                    `id`,
                                    `name`,
                                    `category_id`,
                                    `news_body`,
                                    `title_image`,
                                    `create_data`,
                                    `show_amount`)
                                    VALUES (NULL, :news_name, :category_id, :news_body, :title_image, CURRENT_TIMESTAMP, :show_amount);');
        $sth->execute([
            'news_name' => $name,
            'category_id' => $category,
            'news_body' => $news_bod,
            'title_image' => $title_image,
            'show_amount' => 0
        ]);

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


    public function newsDyCategory($category_id)
    {
        $collection=[];
        $sth = $this->pdo->prepare('SELECT * From news WHERE category_id = :id ;');
        $sth->execute([
            'id' => $category_id
        ]);
        $res = $sth->fetch(\PDO::FETCH_ASSOC);
        if (!$res)
            return null;
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

        ////////////////////////////////////////////////////////Сделать проверку АНАЛИТЕКИ ПОСЛЕ ТОГО КАК СДЕЛАЮ РЕГИСТРАЦИЮ

        if (!$res)
            return null;
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


}