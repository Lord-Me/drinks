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

/* drink.twig */
class __TwigTemplate_60dcd8d40474c8a3991e01e2c025c59ab67ea2b54a9cea5c81061f5a1c60b8fd extends \Twig\Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->blocks = [
            'content' => [$this, 'block_content'],
        ];
    }

    protected function doGetParent(array $context)
    {
        // line 1
        return "bases/base.twig";
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        $this->parent = $this->loadTemplate("bases/base.twig", "drink.twig", 1);
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_content($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 4
        echo "    <div style='height: 75px;'></div>
    <section>
        <div class='container .mi-container-center'>
            <div class='row align-items-center'>
                <div class='col-lg-6 order-lg-2'>
                    <div class='p-5'>
                        <img class='img-fluid rounded-circle' src='/drinks/img/";
        // line 10
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["drink"] ?? null), "image", [], "any", false, false, false, 10), "html", null, true);
        echo "' alt='";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["drink"] ?? null), "image", [], "any", false, false, false, 10), "html", null, true);
        echo "'>
                    </div>
                </div>
                <div class='col-lg-6 order-lg-1'>
                    <div class='p-5'>
                        <h1 class='display-4'>";
        // line 15
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["drink"] ?? null), "title", [], "any", false, false, false, 15), "html", null, true);
        echo "</h1>
                    </div>
                </div>
            </div>
            <div class='row align-items-center'>
                <div class='col-lg-6'>
                    <table>
                        <tr>
                            <a href='/drinks/ourDrinks/1?author=";
        // line 23
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["drink"] ?? null), "author_id", [], "any", false, false, false, 23), "html", null, true);
        echo "'>
                                Author: ";
        // line 24
        echo twig_escape_filter($this->env, twig_capitalize_string_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["drink"] ?? null), "author_name", [], "any", false, false, false, 24)), "html", null, true);
        echo " <img src='/drinks/img/avatars/";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["drink"] ?? null), "author_avatar", [], "any", false, false, false, 24), "html", null, true);
        echo "' alt='userAvatar' height='30' width='30' class='rounded-circle'>
                            </a>";
        // line 25
        if (((twig_get_attribute($this->env, $this->source, ($context["drink"] ?? null), "author_id", [], "any", false, false, false, 25) == ($context["sessionId"] ?? null)) || (($context["sessionId"] ?? null) == 1))) {
            echo " - <a href='/drinks/user/myDrinks/editDrink/";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["drink"] ?? null), "id", [], "any", false, false, false, 25), "html", null, true);
            echo "' class='myDrinksButtons myDrinksButtonsEdit'>Edit</a>";
        }
        // line 26
        echo "                        </tr>
                        <tr>
                            <td>Posted at: ";
        // line 28
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["drink"] ?? null), "published_at", [], "any", false, false, false, 28), "html", null, true);
        echo "</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class='row align-items-center'>
                <div class='col-lg-6'>
                    <h3>Ingredients</h3>
                    <p>";
        // line 36
        echo ($context["ingredients"] ?? null);
        echo "</p>
                </div>
            </div>
            <div class='row align-items-center'>
                <div class='col-lg-6'>
                    <h3>Steps</h3>
                    <p>";
        // line 42
        echo ($context["steps"] ?? null);
        echo "</p>
                </div>
            </div>
            ";
        // line 45
        if ((($context["loggedIn"] ?? null) == true)) {
            // line 46
            echo "                <div class=\"row align-items-center\">
                    <a href=\"#\"><img src=\"/drinks/img/like.png\" height=\"40\" width=\"40\"></a>
                </div>
            ";
        }
        // line 50
        echo "        </div>
    </section>
";
    }

    public function getTemplateName()
    {
        return "drink.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  133 => 50,  127 => 46,  125 => 45,  119 => 42,  110 => 36,  99 => 28,  95 => 26,  89 => 25,  83 => 24,  79 => 23,  68 => 15,  58 => 10,  50 => 4,  46 => 3,  35 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "drink.twig", "/opt/lampp/htdocs/drinks/templates/drink.twig");
    }
}
