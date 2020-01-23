<?php
//TODO my drinks page shows pagination even when the user has no drinks
//TODO session regenerate every 15 min

//$2y$12$K1lggcRKGKv6txa5GfokG.3VydiMlj56tuRp6MOP5CFaBpl8qou7u
//$2y$10$Dx1ISCFDvOsmtc7EKjVV.uU15iPKFmXKU.ZlYs6cFuG/zHeXmTeOC

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
if(isset($_SESSION['sessionAge']) && $_SESSION['sessionAge'] > $_SESSION['sessionAge']->modify('+1 minute')){
    session_regenerate_id();
}
echo session_id();

$request = new Request();

//var_dump($request);
$route = new Router($di);
echo $route->route($request);
