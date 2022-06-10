<?php 

require_once "../vendor/autoload.php";
require_once "../framework/autoload.php"; 


require_once "../controllers/MainController.php"; 
require_once "../controllers/RPGController.php"; 

require_once "../controllers/LoginPageController.php"; 
require_once "../controllers/LogOutController.php"; 

require_once "../controllers/ShooterController.php"; 

require_once "../controllers/ObjectController.php";
require_once "../controllers/SearchController.php";

require_once "../controllers/GameObjectDeleteController.php";
require_once "../controllers/GamesObjectCreateController.php";
require_once "../controllers/GameObjectUpdateController.php";

require_once "../controllers/GamesTypesCreateController.php";

require_once "../middlewares/LoginRequiredMiddleware.php";

require_once "../controllers/GamesObjectRestController.php";

require_once "../middlewares/HistoryMiddlware.php";

require_once "../controllers/SetWelcomeController.php";

require_once "../controllers/Controller404.php";

// создаем загрузчик шаблонов, и указываем папку с шаблонами
// \Twig\Loader\FilesystemLoader -- это типа как в C# писать Twig.Loader.FilesystemLoader, 
// только слеш вместо точек

$url = $_SERVER["REQUEST_URI"];

$loader = new \Twig\Loader\FilesystemLoader('../views');
$twig = new \Twig\Environment($loader, [
    "debug" => true 
]);
$twig->addExtension(new \Twig\Extension\DebugExtension()); // и активируем расширение


$title = "";
$template = "";


$context = []; // словарь



$controller = null; // создаем переменную под контроллер
$controller = new Controller404($twig);

$pdo = new PDO("mysql:host=localhost;dbname=outer_space;charset=utf8", "root", "");

$pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );

$router = new Router($twig, $pdo);
$router->add("/", MainController::class);

$router->add("/loginPage", LoginPageController::class);
$router->add("/logout", LogOutController::class);

$router->add("/RPG", RPGController::class);
$router->add("/Shooting", ShooterController::class);

$router->add("/search", SearchController::class)
        ->middleware(new HistoryMiddleware());

$router->add("/games_objects/create_type", GamesTypesCreateController::class)
        ->middleware(new LoginRequiredMiddleware());
$router->add("/games_objects/create", GamesObjectCreateController::class)
        ->middleware(new LoginRequiredMiddleware());




$router->add("/games_object/(?P<id>\d+)/delete", GameObjectDeleteController::class)
        ->middleware(new LoginRequiredMiddleware());
$router->add("/games_objects/(?P<id>\d+)/edit", GameObjectUpdateController::class)
        ->middleware(new LoginRequiredMiddleware());


$router->add("/api/games_objects/(?P<id>\d+)?", GamesObjectRestController::class);

$router->add("/games_objects/(?P<id>\d+)", ObjectController::class)
        ->middleware(new HistoryMiddleware());; 
$router->add("/games_types/(?P<id>\d+)", ObjectController::class)
        ->middleware(new HistoryMiddleware());;

$router->add("/set-welcome/", SetWelcomeController::class);



$router->get_or_default(Controller404::class);

// if ($url == "/") {
//     $controller = new MainController($twig); // создаем экземпляр контроллера для главной страницы

// } 
// elseif (preg_match("#/RPG#", $url)) {

//     $controller = new RPGController($twig);

//     if (preg_match("#/RPG/image#",$url)){
//         $controller = new RPGImageConroller($twig); 
//     }
//     elseif (preg_match("#/RPG/info#",$url)){
//         $controller = new RPGInfoController($twig);
    
//     }

// } elseif (preg_match("#/Shooting#", $url)) {
//     $controller = new ShooterController($twig);

//     if (preg_match("#/Shooting/image#",$url)){
//         $controller = new ShooterImageConroller($twig); 
//     }
//     elseif (preg_match("#/Shooting/info#",$url)){
//         $controller = new ShooterInfoController($twig); 
//     }
    
// }


// if ($controller) {
//     $controller->setPDO($pdo); // а тут передаем PDO в контроллер
//     $controller->get();
// }

