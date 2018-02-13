<?php

namespace Controller\Admin;

use Framework\BaseController;
use Framework\Request;
use Model\Form\AddNewsForm;

class NewsController extends BaseController
{
    public function indexAction(Request $request)
    {
        return $this->render('index.html.twig');
    }

    public function addAction(Request $request)
    {
        $form = new AddNewsForm(
            $request->post('name'),
            $request->post('category'),
            $request->post('news_body'),
            $request->post('title_image'),
            $request->post('analitic')
        );
        $tags_str = $request->post('tags');
        $word_collection = explode(", ", $tags_str);

        if($request->isPost()){
            $analitic = (isset($_POST['analitic']))? 1 : 0;
            if($form->isValid() && $tags_str != ""){

                $tags_collection=[];
                foreach($word_collection as $word){

                    $tag = $this->getRepository('tag')->findByWord($word);
                    if ($tag){
                        $tags_collection[$tag->getId()] = $tag->getWord();
                    }
                    if(!$tag){
                        $id = $this->getRepository('tag')->getNewIdOfTag();
                        $this->getRepository('tag')->addTag($id,$word);
                        $tags_collection[$id] = $word;
                    }
                }

                $storage = new \Upload\Storage\FileSystem(NEWS_IMAGE_DIR);
                $file = new \Upload\File('titleimage', $storage);
                $new_filename = uniqid().time();
                $file->setName($new_filename);
                $file->addValidations(array(
                    new \Upload\Validation\Mimetype('image/jpeg'),
                    new \Upload\Validation\Size('15M')
                ));
                    $file->upload();

                $id = $this->getRepository('news')->getNewIdOfNews();

                $this->getRepository('news')->add(
                    $id,
                    $form->getName(),
                    $form->getCategory(),
                    $form->getNewsBody(),
                    $file->getNameWithExtension(),
                    $analitic
                );

                foreach($tags_collection as $key => $tag ){

                    $this->getRepository('news')->setTagForNews($id,$key);

                }





                return $this->getRouter()->redirect('admin_news_add');
            }

        }
        $categories= $this->getRepository('category')->findAll();

        return $this->render('add.html.twig',
            ['categories' => $categories,
            ]);
    }




}