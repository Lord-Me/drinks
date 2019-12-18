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
// Afegim una instància de Router a la plantilla.
// La utilitzarem en les plantilles per a generar URL.
$twig->addGlobal('router', new Router(new \App\Utils\DependencyInjector()));
//l'incloem al contenidor de serveis
$di->set('Twig', $twig);

$request = new Request();

//var_dump($request);
$route = new Router($di);
echo $route->route($request);

$action = $_GET['action'] ?? "indexARR";

//echo $_SERVER['REQUEST_URI'];
//echo $_SERVER['QUERY_STRING'];



$page = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_STRING)??"index";
switch ($page) {
    /*
     * INDEX
     */
    case "index":
        break;

    /*
     * DRINKS
     */
    case "drinks":
        break;

    /*
     * DRINK
     */
    case "drink":
        break;

    /*
     * GESTION DE USUARIOS
     */
    case "users":
        break;

    /*
     * LOGIN
     */
    case "login":
        break;

    /*
     * LOGOUT
     */
    case "logout":
        break;

    /*
     * REGISTER
     */
    case "register":
        break;

    /*
     * SUCCESSFUL REGISTER
     */
    case "successfulRegister":
        break;


    /*
     * USER PAGE
     */
    case "user":
        break;

    /*
     * CHANGE PASSWORD
     */
    case "changePassword":
        break;

    /*
     * successfulPasswordChange
     */
    case "successfulPasswordChange":
        break;

    /*
     * Change Avatar
     */
    case "changeAvatar":
        break;

    /*
     * My Drinks
     */
    case "myDrinks":
        break;


    /*
     * ADD NEW DRINK
     */
    case "newDrink":
        break;

    case "editDrink":
        break;

    case "deleteDrink": case "undeleteDrink":
        break;

    /*
     * DEFAULT 404
     */
    default:
          require("views/error.view.php");
          break;

}
