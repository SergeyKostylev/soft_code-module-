<?php

namespace Controller;

use Framework\BaseController;
use Framework\Request;
use GuzzleHttp\Client;

class APIController extends BaseController
{
    public function indexAction(Request $request)
    {
        header('Content-type: application/json');
        return json_encode(['a' => 1]);
    }

    public function showAmount()
    {
        
    }

}