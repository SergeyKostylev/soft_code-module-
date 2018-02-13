<?php

namespace Model\Repository;


use Model\Entity\Tag;

class TagRepository
{
    protected $pdo;

    public function setPdo(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findById($id)
    {
        $sql='SELECT * From tag WHERE id = :id';
        $sth = $this->pdo->prepare($sql);
        $sth->execute([
            'id'=>$id
        ]);
        $res=$sth->fetch(\PDO::FETCH_ASSOC);

        if (!$res){
            return null;
        }
        $tag = new Tag($res['id'],$res['word']);
        return $tag;
    }

    public function findByWord($word)
    {
        $sql='SELECT * From tag WHERE word = "'.$word.'";';
        $sth = $this->pdo->query($sql);
        $res=$sth->fetch(\PDO::FETCH_ASSOC);
        if (!$res){
            return null;
        }
        $tag = new Tag($res['id'],$res['word']);
        return $tag;
    }

    public function getNewIdOfTag()
    {
        $sth = $this->pdo->query('SELECT MAX(id) FROM tag;');
        $res = $sth->fetch(\PDO::FETCH_ASSOC);
        $value= (int)$res['MAX(id)'];
        $value++;
        return $value;

    }

    public function addTag($id,$word)
    {
        $sth = $this->pdo->query('INSERT INTO tag (id, word) VALUES ('.$id.', "'.$word.'");');

    }


    public function findAll()
    {
        $collection=[];

        $sql='SELECT * From tag';
        $sth = $this->pdo->query($sql);

        while ($res = $sth->fetch(\PDO::FETCH_ASSOC)){
            $tag =new Tag($res['id'],$res['word']);
            $collection[]=$tag;
        }
        return $collection;
    }


    public function tagsByNewsId($news_id)
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