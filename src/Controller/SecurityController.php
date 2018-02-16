<?php

namespace Controller;

use Framework\BaseController;
use Framework\Request;
use Model\Form\LoginForm;
use Framework\Session;
use Model\Form\RegistrationForm;



class SecurityController extends BaseController
{



    public function loginAction(Request $request)
    {
        $form = new LoginForm(
            $request->post('email'),
            $request->post('password')
        );
        if ($request->isPost()) {
            if ($form->isValid()) {

                $user = $this
                    ->getRepository('User')
                    ->findByEmail($form->email);

                if (!$user) {
                  $this->reloadPageWithFlash('Неверное имя пользователя', 'homepage');
                }

                if (password_verify($form->password, $user->getPassword())) {
                    Session::set('user', $user->getEmail());

//                    if ($user->getRole() == 'admin');{
//                        $this->getRouter()->redirect('admin_home');
//                    }

                    if (Session::get('user') == 'admin@admin.com'){
                        Session::set('admin','ok');

                        $this->getRouter()->redirect('admin_home');
                    }



                    if (Session::get('user')){
                        $this->getRouter()->redirect('homepage');
                    }

                    $this
                        ->getRouter()
                        ->redirect('homepage')
                    ;
                }

                $this->reloadPageWithFlash('Неверный пароль', 'homepage');
            }
        }

        return $this->render('login.html.twig', [
            'form' => $form
        ]
    );
    }
    public function logoutAction(Request $request)
    {
        Session::remove('user');
        Session::remove('admin');
        $this->getRouter()->redirect('homepage');
    }
    public function registrationAction(Request $request)
    {
        $form =new RegistrationForm (   $request->post('email'),
                                        $request->post('password'),
                                        $request->post('repeat_password')
                                    );

        if($request->isPost()){
            if($form->isValid()){

                if (!$form->samePasswords()){
                    $this->reloadPageWithFlash('Пароли не совпадают', 'registration');
                }
                    $rez = $this
                        ->getRepository('User')
                        ->searchByEmail($form->email);
                if (!$rez){
                    $this
                        ->getRepository('User')
                        ->userAdd($form->email , $form->password);

                    $this->getRouter()->redirect('homepage');
                }

            }
        }

        return $this->render('registration.html.twig', [
                                                            'form' => $form
                                                            ])
                                                            ;


    }

    private function reloadPageWithFlash($flash, $go_to)
    {
        Session::setFlash($flash);
        $this
            ->getRouter()
            ->redirect($go_to)
        ;
    }
}