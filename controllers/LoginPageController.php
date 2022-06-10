<?php 

class LoginPageController extends BaseGameTwigController{
    public $template = "loginPage.twig";


    public function post(array $context){
        // заводим переменные под правильный пароль
        // $valid_user = "user";
        // $valid_password = "12345";

    
        $user = '';
        $password='';

        
        // берем значения которые введет пользователь
        $user = isset($_POST['login']) ? $_POST['login'] : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';
        

        
        $query = $this->pdo->prepare("SELECT password FROM users_table WHERE (username = :user) AND (password = :password)");
        $query->bindValue("user", $user);
        $query->bindValue("password", $password);
        $query->execute();
        
        $valid_password = $query->fetch();
        // $context['valid_password'] = $valid_password;

        $_SESSION['is_logged']  =true;
        

        if (!$valid_password) {
            $_SESSION['is_logged']  =false;
            return $this ->get($context);
        }
        header("Location: /");
        exit;
    }
}