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



//    public function add($name,$category,$news_bod,$title_image, $analitic)
//    {
//
//        $sth = $this->pdo->prepare('INSERT INTO `news` (
//                                    `id`,
//                                    `name`,
//                                    `category_id`,
//                                    `news_body`,
//                                    `title_image`,
//                                    `create_data`,
//                                    `show_amount`,
//                                    `analitic`)
//                                    VALUES (NULL, :news_name, :category_id, :news_body, :title_image, CURRENT_TIMESTAMP, :show_amount, :analitic);');
//        $sth->execute([
//            'news_name' => $name,
//            'category_id' => $category,
//            'news_body' => $news_bod,
//            'title_image' => $title_image,
//            'show_amount' => 0 ,
//            'analitic' => $analitic
//        ]);
//
//    }
//

}