<?php

namespace Model\Repository;

use Framework\Session;
use Model\Entity\News;
use Model\Entity\Tag;

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

    public function analiticNews(array $options = [], $hydrationArray = false)
    {
        $limitSql = '';

        if (isset($options['current_page']) && isset($options['items_on_page'])) {
            $page = $options['current_page'] - 1;
            $count = $page * $options['items_on_page'];
            $limitSql = "limit {$count}, {$options['items_on_page']}";
        }
        $collection=[];

        $sth = $this->pdo->query('select * from news WHERE analitic = 1 ' . $limitSql . ';');

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
                ->setTags($this->getTagsByNewsId($res['id']))
            ;
            $collection[]= $news;

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
                ->setTags($this->getTagsByNewsId($res['id']))
            ;
            $collection[]= $news;
        }


        return $collection;

    }

    public function findLastFour()
    {

        $topTwo=[];

        $sth = $this->pdo->query('SELECT * FROM news ORDER BY create_data DESC LIMIT 4;');

        while ($res = $sth->fetch(\PDO::FETCH_ASSOC)){
            $news =(new News())
                ->setId($res['id'])
                ->setName($res['name'])
                ->setCategoryId($res['category_id'])
                ->setNewsBody($res['news_body'])
                ->setTitleImage($res['title_image'])
                ->setCreteDate($res['create_data'])
                ->setShowAmount($res['show_amount'])
                ->setAnalitic($res['analitic'])
                ->setTags($this->getTagsByNewsId($res['id']))
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
                ->setTags($this->getTagsByNewsId($res['id']))
            ;
            $collection[]=$category;
        }

        return $collection;

    }

    public function newsDyTag($tag_id, array $options = [], $hydrationArray = false)
    {
        $limitSql = '';

        if (isset($options['current_page']) && isset($options['items_on_page'])) {
            $page = $options['current_page'] - 1;
            $count = $page * $options['items_on_page'];
            $limitSql = "limit {$count}, {$options['items_on_page']}";
        }

        $collection=[];


        $sth = $this->pdo->prepare('SELECT n.id,
                                            n.name,
                                            n.category_id,
                                            n.news_body,
                                            n.title_image,
                                            n.create_data,
                                            n.show_amount,
                                            n.analitic
                                            From news n
                                            JOIN news_tag nt ON n.id = nt.news_id
                                            JOIN tag t ON nt.tag_id = t.id
                                            WHERE t.id = :id '. $limitSql .';');
        $sth->execute([
            'id' => $tag_id
        ]);

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
                ->setTags($this->getTagsByNewsId($res['id']))
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
        $sentences_array = array_splice($sentences_array,0,3);

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
            ->setAnalitic($res['analitic'])
            ->setTags($this->getTagsByNewsId($res['id']))
            ;

    }

    public function count($category_id)
    {
        $sth = $this->pdo->prepare('select count(*) as count from news WHERE category_id= :id');
        $sth->execute(['id' => $category_id]);
        return $sth->fetchColumn();
    }

    public function countAnaliticNews()
    {
        $sth = $this->pdo->query('select count(*) as count from news WHERE analitic = 1;');

        return $sth->fetchColumn();
    }

    public function countTagNews($tag_id)
    {
        $sth = $this->pdo->prepare('SELECT count(*) as count From news n
                                                JOIN news_tag nt ON n.id = nt.news_id
                                                JOIN tag t ON nt.tag_id = t.id
                                                WHERE t.id = :id;');
        $sth->execute([
            'id' => $tag_id
        ]);

        return $sth->fetchColumn();
    }


    public function getTagsByNewsId($news_id)
    {
        $collection=[];
        $sth = $this->pdo->prepare('Select t.id, t.word 
                                                from news_tag nt
                                                JOIN tag t ON  t.id = nt.tag_id
                                                WHERE nt.news_id = :id;');
        $sth->execute([
            'id' => $news_id
        ]);
        while ($res = $sth->fetch(\PDO::FETCH_ASSOC)){
            $tag =new Tag($res['id'],$res['word']);
            $collection[]=$tag;
        }

        return $collection;
    }

}