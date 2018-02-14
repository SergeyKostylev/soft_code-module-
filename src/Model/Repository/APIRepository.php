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

    public function setDispatchEntry($email,$name)
    {
        $sth = $this->pdo->prepare('INSERT INTO dispatch (id, email, user_name) VALUES (NULL,:email , :userName );');

        $sth->execute([
            'email' => $email,
            'userName' => $name
        ]);
    }

    public function checkDuplicateDispatchEntry($email)
    {
        $sth = $this->pdo->prepare('SELECT id, email, user_name FROM dispatch WHERE email = :email');
        $sth->execute([
            'email' => $email
        ]);
        $res = $sth->fetch(\PDO::FETCH_ASSOC);
        return $res;
    }



}