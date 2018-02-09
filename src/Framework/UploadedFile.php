<?php


namespace Framework;

class UploadedFile
{
    private $name;
    private $type;
    private $tmp_name;
    private $size;

    public function __construct($name, $type, $tmp_name, $size)
    {
        $this->name = $this->generateFilename($name);
        $this->type = $type;
        $this->tmp_name = $tmp_name;
        $this->size = $size;
    }

    public function isImage()
    {
        if ($this->type == 'image/jpeg' or
            $this->type == 'image/png' or
            $this->type == 'image/bmp')
        {
            return $this->type;
        }
        else

            return null;
    }

    public function generateFilename($file_name)
    {

        $str = $file_name;
        preg_match('/\.[a-zA-Z0-9]+$/', $str,$maches);
        $extension = $maches[0];
        return  md5(uniqid()) . "{$extension}" ;


    }

    public function upload()
    {


        move_uploaded_file($this->tmp_name, './imeges/' . $this->name);



    }
}