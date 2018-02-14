<?php
/**
 * Created by PhpStorm.
 * User: Sergey
 * Date: 15.12.2017
 * Time: 16:28
 */

namespace Model\Repository;


use Model\Entity\Comment;
use Model\Entity\User;

class CommentRepository
{
    /**
     *  @var \PDO
     */
    protected $pdo;

    public function setPdo(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function uploadComment($news_id, $user_id, $comment_body, $allow_show)
    {
        $sql='INSERT INTO `comment` (
                            `id`,
                            `news_id`,
                            `user_id`,
                            `body`,
                            `allow_show`,
                            `likes`,
                            `dislikes`,
                            `date`) 
                            VALUES (NULL, :newsid, :userid, :commentbody, :allow_show, 0, 0, CURRENT_TIMESTAMP);';
        $sth = $this->pdo->prepare($sql);
        $sth->execute([
                'newsid' => $news_id,
                'userid' => $user_id,
                'commentbody' => $comment_body,
                'allow_show' => $allow_show
        ]);
        return $this->pdo->lastInsertId();
    }


    public function findUnconfirmedComments()
    {
        $collection =[];

        $sth = $this->pdo->query('SELECT * From comment WHERE allow_show = 0;');

        while ($res = $sth->fetch(\PDO::FETCH_ASSOC)){

            $comment =(new Comment())
                ->setId($res['id'])
                ->setNewsId($res['news_id'])
                ->setUserId($res['user_id'])
                ->setBody($res['body'])
                ->setAllowShow($res['allow_show'])
                ->setLikes($res['likes'])
                ->setDislikes($res['dislikes'])
                ->setDate($res['date'])
            ;


            $collection[]=$comment;
        }
        return $collection;

    }

    public function findVisibleComments()
    {
        $collection =[];

        $sth = $this->pdo->query('SELECT * From comment WHERE allow_show = 1;');

        while ($res = $sth->fetch(\PDO::FETCH_ASSOC)){

            $comment =(new Comment())
                ->setId($res['id'])
                ->setNewsId($res['news_id'])
                ->setUserId($res['user_id'])
                ->setBody($res['body'])
                ->setAllowShow($res['allow_show'])
                ->setLikes($res['likes'])
                ->setDislikes($res['dislikes'])
                ->setDate($res['date'])
            ;
            $collection[]=$comment;
        }
        return $collection;
    }

    public function findCommentById($comment_id)
    {
        $sth = $this->pdo->prepare('SELECT * FROM comment WHERE id = :id');
        $sth->execute([
            'id' => $comment_id
        ]);

        $res = $sth->fetch(\PDO::FETCH_ASSOC);

            return (new Comment())
                ->setId($res['id'])
                ->setNewsId($res['news_id'])
                ->setUserId($res['user_id'])
                ->setBody($res['body'])
                ->setAllowShow($res['allow_show'])
                ->setLikes($res['likes'])
                ->setDislikes($res['dislikes'])
                ->setDate($res['date'])
            ;

    }


    public function findById($news_id)
    {
        $collection =[];
        $sth = $this->pdo->prepare('SELECT * FROM comment WHERE news_id = :newsId AND allow_show = 1 ORDER BY likes DESC');
        $sth->execute([
            'newsId' => $news_id
        ]);

        while ($res = $sth->fetch(\PDO::FETCH_ASSOC)){

            $comment =(new Comment())
                ->setId($res['id'])
                ->setNewsId($res['news_id'])
                ->setUserId($res['user_id'])
                ->setBody($res['body'])
                ->setAllowShow($res['allow_show'])
                ->setLikes($res['likes'])
                ->setDislikes($res['dislikes'])
                ->setDate($res['date'])
            ;
            $collection[]=$comment;
        }
        return $collection;
    }


    public function finbByUserID($user_id, array $options = [], $hydrationArray = false)
    {
        $limitSql = '';

        if (isset($options['current_page']) && isset($options['items_on_page'])) {
            $page = $options['current_page'] - 1;
            $count = $page * $options['items_on_page'];
            $limitSql = "limit {$count}, {$options['items_on_page']}";
        }

        $collection =[];
        $sth = $this->pdo->prepare('SELECT * FROM comment WHERE user_id = :userId AND allow_show = 1 ORDER BY likes DESC '. $limitSql .';');
        $sth->execute([
            'userId' => $user_id
        ]);

        while ($res = $sth->fetch(\PDO::FETCH_ASSOC)){

            $comment =(new Comment())
                ->setId($res['id'])
                ->setNewsId($res['news_id'])
                ->setUserId($res['user_id'])
                ->setBody($res['body'])
                ->setAllowShow($res['allow_show'])
                ->setLikes($res['likes'])
                ->setDislikes($res['dislikes'])
                ->setDate($res['date'])
            ;
            $collection[]=$comment;
        }
        return $collection;

    }





    public function getMark($user_id, $comment_id)
    {
        $sth = $this->pdo->prepare('SELECT sense FROM mark WHERE user_id= :userId  AND comment_id = :commentId LIMIT 1;');
        $sth->execute([
            'userId' => $user_id,
            'commentId' => $comment_id
        ]);
        $res = $sth->fetch(\PDO::FETCH_ASSOC);
        return $res['sense'];
    }



    public function insertMark($user_id,$comment_id,$mark)
    {
        $sth = $this->pdo->prepare('INSERT INTO mark (user_id, comment_id, sense) VALUES (:userId, :commentId, :mark);');
        $sth->execute([
            'userId' => $user_id,
            'commentId' => $comment_id,
            'mark' => $mark
        ]);
    }

    public function updateMark($user_id,$comment_id,$mark)
    {
        $sth = $this->pdo->prepare('UPDATE mark SET sense = :makr WHERE mark.user_id = :userId AND mark.comment_id = :commentId;');
        $sth->execute([
            'makr' => $mark,
            'userId' => $user_id,
            'commentId' => $comment_id
        ]);
    }

    public function updateLikes($comment_id,$likes_amount)
    {
        $sth = $this->pdo->prepare('UPDATE comment SET likes = :likesAmount WHERE comment.id = :commentId;');
        $sth->execute([
            'likesAmount' => $likes_amount,
            'commentId' => $comment_id,
        ]);
    }

    public function updateDislikes($comment_id,$dislikes_amount)
    {
        $sth = $this->pdo->prepare('UPDATE comment SET dislikes = :dislikesAmount WHERE comment.id = :commentId;');
        $sth->execute([
            'dislikesAmount' => $dislikes_amount,
            'commentId' => $comment_id,
        ]);
    }


    public function countUserComment($user_id)
    {
        $sth = $this->pdo->prepare('SELECT count(*) as count From comment WHERE user_id = :id');
        $sth->execute([
            'id' => $user_id
        ]);
        return $sth->fetchColumn();
    }



}