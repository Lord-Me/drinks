<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* bases/base.twig */
class __TwigTemplate_02224597ddfed3a277858d9ccb75601ff5f3710b27ab4d50a5efdd4148f326c7 extends \Twig\Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
            'content' => [$this, 'block_content'],
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 1
        echo "
<!DOCTYPE html>
<html lang=\"en\">

<head>

    <meta charset=\"utf-8\">
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1, shrink-to-fit=no\">
    <meta name=\"description\" content=\"\">
    <meta name=\"author\" content=\"\">

    <title>Cocktails - Aidan</title>

    <!-- Bootstrap core CSS -->
    <link href=\"/drinks/vendor/bootstrap/css/bootstrap.min.css\" rel=\"stylesheet\">

    <!-- Custom fonts for this template -->
    <link href=\"https://fonts.googleapis.com/css?family=Catamaran:100,200,300,400,500,600,700,800,900\" rel=\"stylesheet\">
    <link href=\"https://fonts.googleapis.com/css?family=Lato:100,100i,300,300i,400,400i,700,700i,900,900i\" rel=\"stylesheet\">

    <!-- Custom styles for this template -->
    <link href=\"/drinks/css/one-page-wonder.min.css\" rel=\"stylesheet\">
    <link href=\"/drinks/css/myStyles.css\" rel=\"stylesheet\">
    <link rel=\"stylesheet\" href=\"https://use.fontawesome.com/releases/v5.7.1/css/all.css\">

</head>
<body>
<!-- Navigation -->
 <nav class=\"navbar navbar-expand-lg navbar-dark navbar-custom fixed-top\">
    <div class=\"container\">
        <a class=\"navbar-brand\" href=\"/drinks/\">Home</a>
        <button class=\"navbar-toggler\" type=\"button\" data-toggle=\"collapse\" data-target=\"#navbarResponsive\" aria-controls=\"navbarResponsive\" aria-expanded=\"false\" aria-label=\"Toggle navigation\">
            <span class=\"navbar-toggler-icon\"></span>
        </button>
        <a class=\"navbar-brand\" href=\"/drinks/ourDrinks/1\">Our Drinks</a>
        <button class=\"navbar-toggler\" type=\"button\" data-toggle=\"collapse\" data-target=\"#navbarResponsive\" aria-controls=\"navbarResponsive\" aria-expanded=\"false\" aria-label=\"Toggle navigation\">
            <span class=\"navbar-toggler-icon\"></span>
        </button>
        ";
        // line 39
        if ((($context["isAdmin"] ?? null) == true)) {
            // line 40
            echo "            <a class=\"navbar-brand\" href = \"/drinks/user/admin/users/1\" >";
            echo gettext("User Management");
            echo "</a >
            <button class=\"navbar-toggler\" type = \"button\" data - toggle = \"collapse\" data - target = \"#navbarResponsive\" aria - controls = \"navbarResponsive\" aria - expanded = \"false\" aria - label = \"Toggle navigation\" >
                <span class=\"navbar-toggler-icon\" ></span >
            </button >
        ";
        }
        // line 45
        echo "        ";
        if ((($context["loggedIn"] ?? null) == false)) {
            // line 46
            echo "            <div class=\"collapse navbar-collapse\" id=\"navbarResponsive\">
                <ul class=\"navbar-nav ml-auto\">
                    <li class=\"nav-item\">
                        <a class=\"nav-link\" href=\"/drinks/register\">";
            // line 49
            echo gettext("Sign Up");
            echo "</a>
                    </li>
                    <li class=\"nav-item\">
                        <a class=\"nav-link\" href=\"/drinks/login\">";
            // line 52
            echo gettext("Log In");
            echo "</a>
                    </li>
                </ul>
            </div>
        ";
        } else {
            // line 57
            echo "            <a class=\"navbar-brand\" href=\"/drinks/user/myDrinks/1\">";
            echo gettext("My Drinks");
            echo "</a>
            <button class=\"navbar-toggler\" type=\"button\" data-toggle=\"collapse\" data-target=\"#navbarResponsive\" aria-controls=\"navbarResponsive\" aria-expanded=\"false\" aria-label=\"Toggle navigation\">
                <span class=\"navbar-toggler-icon\"></span>
            </button>
            <div class=\"collapse navbar-collapse\" id=\"navbarResponsive\">
                <ul class=\"navbar-nav ml-auto\">
                    <li class=\"nav-item\">
                        <a class=\"nav-link\" href=\"/drinks/user/profile\">";
            // line 64
            echo strtr(gettext("%username% profile"), array("%username%" => ($context["username"] ?? null), ));
            echo "</a>
                    </li>
                    <li class=\"nav-item\">
                        <a class=\"nav-link\" href=\"/drinks/user/logout\">";
            // line 67
            echo gettext("Log Out");
            echo "</a>
                    </li>
                </ul>
            </div>
        ";
        }
        // line 72
        echo "    </div>
</nav>
";
        // line 74
        $this->displayBlock('content', $context, $blocks);
        // line 75
        echo "
<!-- Footer -->
<footer class=\"py-5 bg-black\" style=\"    max-height: 100px;\">
    <div class=\"container\">
        <p class=\"m-0 text-center text-white small\">Copyright &copy; Cocktails - Aidan 2019</p>
    </div>
    <!-- /.container -->
</footer>

<!-- Bootstrap core JavaScript -->
<script src=\"../../vendor/jquery/jquery.min.js\"></script>
<script src=\"../../vendor/bootstrap/js/bootstrap.bundle.min.js\"></script>
</body>
</html>";
    }

    // line 74
    public function block_content($context, array $blocks = [])
    {
        $macros = $this->macros;
    }

    public function getTemplateName()
    {
        return "bases/base.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  159 => 74,  142 => 75,  140 => 74,  136 => 72,  128 => 67,  122 => 64,  111 => 57,  103 => 52,  97 => 49,  92 => 46,  89 => 45,  80 => 40,  78 => 39,  38 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "bases/base.twig", "/opt/lampp/htdocs/drinks/templates/bases/base.twig");
    }
}
