<?php
namespace Model\Form;

class AddCategoryForm
{
    public $name;

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function isValid()
    {
        return !empty($this->name);

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
    }


}