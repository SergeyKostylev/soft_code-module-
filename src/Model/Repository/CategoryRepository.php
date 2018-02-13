<?php

namespace Model\Repository;
//use Model\Entity\News;
use Model\Entity\Category;
use Model\Entity\News;

class CategoryRepository
{
    protected $pdo;

    public function setPdo(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function add($category_name)
    {
        $sth = $this->pdo->prepare('INSERT INTO category (id, name) VALUES (NULL, :nameCategory);');
        $sth->execute([
            'nameCategory' => $category_name
        ]);

    }


    public function findAll()
    {
        $collection=[];
        $sth = $this->pdo->query('SELECT  * From category;');
        while ($res = $sth->fetch(\PDO::FETCH_ASSOC)){
            $category =(new Category())
                ->setId($res['id'])
                ->setName($res['name'])
            ;
            $collection[]=$category;
        }
        return $collection;

    }
    public function findByID($category_id)
    {
        $sth = $this->pdo->prepare('SELECT * From category WHERE id = :id ;');
        $sth->execute([
            'id' => $category_id
        ]);
        $res = $sth->fetch(\PDO::FETCH_ASSOC);
        if (!$res)
            return null;
        $category = (new Category())
            ->setId($res['id'])
            ->setName($res['name']);


        return $category;
    }





}