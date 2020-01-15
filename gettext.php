<?php
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
require('config/bootstrap.php');
$tplDir = dirname(__FILE__).'/templates';
$tmpDir = dirname(__FILE__).'/templates_php';
$loader = new FilesystemLoader($tplDir);
// force auto-reload to always have the latest version of the template
$twig = new Environment($loader, array(
    'cache' => $tmpDir,
    'auto_reload' => true
));
$twig->addExtension(new Twig_Extensions_Extension_I18n());
$twig->addExtension(new Twig_Extensions_Extension_Intl());
// configure Twig the way you want
// iterate over all your templates
foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($tplDir), RecursiveIteratorIterator::LEAVES_ONLY) as $file)
{
    // force compilation
    if ($file->isFile()) {
        $twig->loadTemplate(str_replace($tplDir.'/', '', $file));
    }
}
