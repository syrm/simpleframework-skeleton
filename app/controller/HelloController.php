<?php

class HelloController extends simpleframework\Controller
{


    public function index($params)
    {

        $this->view['name'] = $params['name'];

    }

}