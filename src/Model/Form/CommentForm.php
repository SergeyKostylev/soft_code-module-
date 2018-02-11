<?php
namespace Model\Form;

class CommentForm
{
    public $coment_body;




    public function __construct($coment_body)
    {
        $this->coment_body = $coment_body;


    }

    public function isValid()
    {
        return !empty($this->coment_body);

    }

    /**
     * @return mixed
     */
    public function getComentBody()
    {
        return $this->coment_body;
    }

    /**
     * @param mixed $coment_body
     */
    public function setComentBody($coment_body)
    {
        $this->coment_body = $coment_body;
    }

}