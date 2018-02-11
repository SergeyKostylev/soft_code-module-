<?php

namespace Model\Entity;

class Tag
{
    private $id;
    private $word;

    /**
     * Tag constructor.
     * @param $id
     * @param $word
     */
    public function __construct($id, $word)
    {
        $this->id = $id;
        $this->word = $word;
    }
    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getWord()
    {
        return $this->word;
    }

    /**
     * @param mixed $word
     */
    public function setWord($word)
    {
        $this->word = $word;
    }



}
