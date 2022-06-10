<?php

class BaseRestController extends BaseController{
    function list(){}
    function retrieve(){}
    function create(){}
    function update(){}
    function delete(){}

    function process_response()
    {
        $method = $_SERVER["REQUEST_METHOD"];
        if($method == "GET"){
            if(isset($this->params['id']))
                $this->retrieve();
            else
                $this->list();
        }else if($method == "POST"){
            if(isset($this->params['id'])){
                $this->update();
            }else{
                $this->create();
            }
        }else if ($method =='DELETE'){
            $this ->delete();
        }
    }
}