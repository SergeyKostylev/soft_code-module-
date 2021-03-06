<?php
/**
 * Created by PhpStorm.
 * User: Sergey
 * Date: 15.12.2017
 * Time: 16:28
 */

namespace Model\Repository;


use Model\Entity\User;

class UserRepository
{
    /**
     *  @var \PDO
     */
    protected $pdo;

    public function setPdo(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findByEmail ($email)
    {
        $sql='SELECT * FROM `user` WHERE email= :email limit 1';
        $sth = $this->pdo->prepare($sql);
        $sth->execute([
                'email'=>$email
        ]);
        $res=$sth->fetch(\PDO::FETCH_ASSOC);

        if (!$res){
            return null;
        }
        $user = (new User ($res['email']))
                ->setPassword($res['password']
                )->setRole($res['role'])
        ;
        return $user;
    }

    public function searchByEmail($email)
    {
        $sql = 'SELECT * FROM `user` WHERE email= :email';
        $sth = $this->pdo->prepare($sql);
        $sth->execute([
                'email' => $email
        ]);
        $res = $sth->fetch(\PDO::FETCH_ASSOC);

        return $res;
    }

    public function getUserByEmail($email)
    {

        $sql = 'SELECT * FROM `user` WHERE email= :email LIMIT 1';
        $sth = $this->pdo->prepare($sql);
        $sth->execute([
            'email' => $email
        ]);
        $res = $sth->fetch(\PDO::FETCH_ASSOC);
        if (!$res) {
            return null;
        }
        $user = (new User ($res['email']))
            ->setId($res['id'])
            ->setPassword($res['password'])
            ->setRole($res['role'])
        ;
        return $user;
    }



    public function userAdd($email, $password)
    {
        $sql ='INSERT INTO `user` (email, password) VALUES (:email, :password)';
        $sth = $this->pdo->prepare($sql);
        $password = password_hash($password, PASSWORD_DEFAULT);
        $sth->execute([
            'email' => $email,
            'password' => $password
            ]);

    }

    public function findById($id)
    {
        $sql='SELECT * FROM user WHERE id = :id';
        $sth = $this->pdo->prepare($sql);
        $sth->execute([
            'id'=>$id
        ]);
        $res=$sth->fetch(\PDO::FETCH_ASSOC);

        if (!$res){
            return null;
        }
        $user = (new User ($res['email']))
            ->setId($res['id'])
            ->setPassword($res['password'])
            ->setRole($res['role'])
        ;
        return $user;
    }


    public function findMasAll()
    {
        $sql='SELECT * FROM user';
        $sth = $this->pdo->query($sql);
        $users= [];
        while ($res = $sth->fetch(\PDO::FETCH_ASSOC)){
            $users[$res['id']] = $res['email'];
        }
        return $users;


    }
    public function topFiveUsersByComments()
    {
        $sql='SELECT u.id, u.email, COUNT(c.id) 
                                            as count From user u
                                            JOIN comment c ON c.user_id =u.id 
                                            GROUP BY u.id ORDER BY count DESC LIMIT 5;';
        $sth = $this->pdo->query($sql);
        $users= [];
        while ($res = $sth->fetch(\PDO::FETCH_ASSOC)){
            $users[$res['id']] = $res['email'];
        }
        return $users;


    }




}