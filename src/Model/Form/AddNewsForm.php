<?php
namespace Model\Form;

class AddNewsForm
{
    public $name;
    public $category;
    public $news_body;
    public $title_image;
    public $analitic;



    public function __construct($name, $category, $news_body, $title_image,$analitic)
    {
        $this->name = $name;
        $this->category = $category;
        $this->title_image = $title_image;
        $this->news_body = $news_body;
        $this->analitic = $analitic;

    }

    public function isValid()
    {
        return !empty($this->name) &&
               !empty($this->category) &&
               !empty($this->news_body) &&
            !empty($_FILES['titleimage']['name'])
            ;

    }

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
        $this->analitic = $analitic;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getNewsBody()
    {
        return $this->news_body;
    }

    /**
     * @param mixed $news_body
     */
    public function setNewsBody($news_body)
    {
        $this->news_body = $news_body;
    }


    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param mixed $category
     */
    public function setCategory($category)
    {
        $this->category_id = $category;
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
    }



}