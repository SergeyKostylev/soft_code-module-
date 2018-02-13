<?php

namespace Model\Repository;
use Model\Entity\Category;


class APIRepository
{
    protected $pdo;

    public function setPdo(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findAllShowAmuont($idNews)
    {

        $sth = $this->pdo->prepare('SELECT show_amount FROM news WHERE id= :id ;');
        $sth->execute([
            'id' => $idNews
        ]);
        $res = $sth->fetch(\PDO::FETCH_ASSOC);
        if (!$res)
            return null;
        return $amount = $res['show_amount'];

    }

    public function setShowAmuont($idNews,$newAmount)
    {

        $sth = $this->pdo->prepare('UPDATE news SET show_amount = :newamount WHERE news.id = :id;');

        $sth->execute([
            'newamount' => $newAmount,
            'id' => $idNews
        ]);

    }

    public function applauseComment($idComment,$CommentBody)
    {
        $sth = $this->pdo->prepare('UPDATE comment SET body = :commentbody, allow_show  = 1 WHERE comment.id = :commentid;');

        $sth->execute([
            'commentbody' => $CommentBody,
            'commentid' => $idComment
        ]);

    }




}