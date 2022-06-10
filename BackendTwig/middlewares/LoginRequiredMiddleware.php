<?php

class LoginRequiredMiddleware extends BaseMiddleware{
    public function apply(BaseController $controller, array $context){
        // заводим переменные под правильный пароль
        // $valid_user = "user";
        // $valid_password = "12345";

    
        $user = '';
        $password='';

       

        // берем значения которые введет пользователь
        $user = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] : '';
        $password = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] : '';
        

        
        $query = $controller->pdo->prepare("SELECT password FROM users_table WHERE (username = :user) AND (password = :password)");
        $query->bindValue("user", $user);
        $query->bindValue("password", $password);
        $query->execute();
        
        $valid_password = $query->fetch();
        // $context['valid_password'] = $valid_password;


        if (!$valid_password) {

            header('WWW-Authenticate: Basic realm="Games objects"');
            http_response_code(401);
            exit;
        }
    }
}