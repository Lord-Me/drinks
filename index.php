<?php

use App\DBConnect;
use App\Model\DrinkModel;
use App\Model\UserModel;
use App\Entity\Filter;
use App\Core\Router;
use App\Core\Request;
use App\Exceptions\ExceptionPageNotFound;
use App\Exceptions\ExceptionInvalidData;
use App\Exceptions\ExceptionInvalidInput;
use App\Exceptions\ExceptionUsernameExists;

require __DIR__ . '/config/bootstrap.php';

session_start();

$di = new \App\Utils\DependencyInjector();

$db = new DBConnect();
$di->set('PDO', $db->getConnection());

// Carreguem l'entorn de Twig
$loader=new \Twig\Loader\FilesystemLoader(__DIR__.'/templates');
$twig = new \Twig\Environment($loader);
$twig->addExtension(new Twig_Extensions_Extension_I18n());
// Afegim una instància de Router a la plantilla.
// La utilitzarem en les plantilles per a generar URL.
$twig->addGlobal('router', new Router(new \App\Utils\DependencyInjector()));
//l'incloem al contenidor de serveis
$di->set('Twig', $twig);

if(isset($_SESSION['language'])){
    putenv('LANGUAGE='.$_SESSION['language']);
}else {
    putenv('LANGUAGE=uk');
}

// GETTEXT
bindtextdomain('main', 'locales');
bind_textdomain_codeset('main', 'UTF-8');
textdomain('main');


//REFRESH SESSION
$now  = new DateTime();

if(isset($_SESSION['sessionAge']) && date_diff($_SESSION['sessionAge'], $now)->i > 15){
    session_regenerate_id();
    $_SESSION['sessionAge'] = new DateTime();
}

$request = new Request();

//var_dump($request);
$route = new Router($di);
echo $route->route($request);
