<?php

namespace Controller\Admin;

use Framework\BaseController;
use Framework\Request;
use Framework\Session;
use Model\Form\AddCategoryForm;


class CategoryController extends BaseController
{


    public function addAction(Request $request)
    {
        $name = $request->post('name');

        $form = new AddCategoryForm($name);
        $answer = -1;


        if($request->isPost()){
            Session::setFlash('Введите назватие');
            $answer =0;
            if($form->isValid()){
                $this->getRepository('category')->add($form->getName());
                $answer =1;
                Session::setFlash('Категория добавлена');
                return $this->getRouter()->redirect('admin_catrgory_add');
            }
        }

        return $this->render('add.html.twig', ['answer' => $answer]);
    }




}