<?php
/**
 * Created by PhpStorm.
 * User: Sergey
 * Date: 15.12.2017
 * Time: 16:28
 */

namespace Model\Repository;


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
    }

}