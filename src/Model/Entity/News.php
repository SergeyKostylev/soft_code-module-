<?php

namespace Model\Entity;

class News
{
    private $id;
    private $name;
    private $category_id;
    private $news_body;
    private $title_image;
    private $crete_date;
    private $show_amount;
    private $analitic;

    /**
     * @return mixed
     */
    public function getAnalitic()
    {
        return $this->analitic;
    }

    /**
     * @param mixed $analitic
     */
    public function setAnalitic($analitic)
    {
        $this->analitic = (bool)$analitic;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNewsBody()
    {
        return $this->news_body;
    }

    /**
     * @param mixed $new_body
     */
    public function setNewsBody($news_body)
    {
        $this->news_body = $news_body;
        return $this;
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
        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCategoryId()
    {
        return $this->category_id;
    }

    /**
     * @param mixed $category_id
     */
    public function setCategoryId($category_id)
    {
        $this->category_id = $category_id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTitleImage()
    {
        return $this->title_image;
    }

    /**
     * @param mixed $title_image
     */
    public function setTitleImage($title_image)
    {
        $this->title_image = $title_image;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCreteDate()
    {
        return $this->crete_date;
    }

    /**
     * @param mixed $crete_date
     */
    public function setCreteDate($crete_date)
    {
        $this->crete_date = $crete_date;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getShowAmount()
    {
        return $this->show_amount;
    }

    /**
     * @param mixed $show_amount
     */
    public function setShowAmount($show_amount)
    {
        $this->show_amount = $show_amount;
        return $this;
    }

}
