<?php

class Route {
    public string $route_regexp; // тут получается шаблона url
    public $controller; // а это класс контроллера
    public array $middlewareList = []; // добавил массив под middleware


    public function middleware(BaseMiddleware $m) : Route {
        array_push($this->middlewareList, $m);
        return $this;
    }


    // ну и просто конструктор
    public function __construct($route_regexp, $controller)
    {
        $this->route_regexp = $route_regexp;
        $this->controller = $controller;
    }
}

class Router {
    /**
     * @var Route[]
     */
    protected $routes = []; // создаем поле -- список под маршруты и привязанные к ним контроллеры

    protected $twig; // переменные под twig и pdo
    protected $pdo;
    

    // конструктор
    public function __construct($twig, $pdo)
    {
        $this->twig = $twig;
        $this->pdo = $pdo;
    }

    // функция с помощью которой добавляем маршрут
    public function add($route_regexp, $controller) {
        // создаем экземпляр маршрута
        $route = new Route("#^$route_regexp$#", $controller);
        array_push($this->routes, $route);
        
        // возвращаем как результат функции
        return $route;
    }

    // функция которая должна по url найти маршрут и вызывать его функцию get
    // если маршрут не найден, то будет использоваться контроллер по умолчанию
    public function get_or_default($default_controller) {
        
        session_set_cookie_params(60*60*10);
        session_start();

        $url = $_SERVER["REQUEST_URI"]; // получили url
        $path = parse_url($url,PHP_URL_PATH);


        // фиксируем в контроллер $default_controller
        $controller = $default_controller;
        $newRoute = null; // добавили переменную под маршрут

        $matches = [];

        foreach($this->routes as $route) {
            // проверяем подходит ли маршрут под шаблон
            if (preg_match($route->route_regexp,$path,$matches)) {
                // если подходит, то фиксируем привязанные к шаблону контроллер 
                $controller = $route->controller;
                $newRoute = $route; // загоняем соответствующий url маршрут в переменную
               // и выходим из цикла
                break;
            }
        }

        $controllerInstance = new $controller();
        $controllerInstance->setPDO($this->pdo);
        $controllerInstance->setParams($matches); // передаем параметров
    
        if ($controllerInstance instanceof TwigBaseController) {
            $controllerInstance->setTwig($this->twig);
        }

        if ($newRoute) {
            foreach ($newRoute->middlewareList as $m) {
                $m->apply($controllerInstance, []);
            }
        }
        
        return $controllerInstance->process_response(); // теперь тут process_response вместо get
    }
}