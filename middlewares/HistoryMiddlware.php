<?php

class HistoryMiddleware extends BaseMiddleware{

    public function apply(BaseController $controller, array $context){
        if(!isset($_SESSION["history"])){
            $_SESSION["history"] = [];
        }
        $_SESSION["history"][] =$_SERVER['REQUEST_URI'];

    }

}