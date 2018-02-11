<?php
namespace Model\Form;

class TagForm
{
    public $tags;

    /**
     * TagForm constructor.
     * @param $tags
     */
    public function __construct($tags)
    {
        $this->tags = $tags;
    }


    public function isValid()
    {
        return !empty($this->tags);
    }

}