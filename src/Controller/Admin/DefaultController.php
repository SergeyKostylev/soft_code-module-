<?php

namespace Controller\Admin;

use Framework\BaseController;
use Framework\Request;
use Model\Service\ZipService;
use Model\Service\MailService;

class DefaultController extends BaseController
{
    public function indexAction(Request $request)
    {
        return $this->render('index.html.twig');
    }


}