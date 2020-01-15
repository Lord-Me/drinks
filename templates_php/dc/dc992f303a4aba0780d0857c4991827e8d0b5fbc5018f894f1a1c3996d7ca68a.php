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

/* drinks.twig */
class __TwigTemplate_b3b049fc7aad7dc7cf42bf828fd5810deb0450f3315d3766436c1adaa44a3792 extends \Twig\Template
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
        $this->parent = $this->loadTemplate("bases/base.twig", "drinks.twig", 1);
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_content($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 4
        echo "    <div style='height: 75px;'></div>
    <section>
        <div class=\"container\">
            <div  class=\"row align-items-center text-center\">
                <div class='col-lg-12'>
                    <form action=\"/drinks/ourDrinks/1\" method=\"get\" id=\"filterContainer\" class=\"filterForm\">
                        <input type=\"hidden\" name=\"author\" value=\"";
        // line 10
        echo twig_escape_filter($this->env, ($context["author_id"] ?? null), "html", null, true);
        echo "\">
                        <div class=\"row\">
                            <div class=\"col-lg-6 order-lg-1 filterFormLeft\">
                                <div class=\"row filterFormSearch\">
                                    <label for=\"generalSearch\">Search: </label>
                                    <input id=\"generalSearch\" type=\"text\" name=\"search\" placeholder=\"Search...\"><br>
                                </div>
                                <div class=\"row filterFormRadio\">
                                    <input id=\"filterAll\" type=\"radio\" name=\"categoryFilter\" value=\"all\" checked=\"checked\" class=\"filter\">
                                    <label for=\"filterAll\">All Drinks</label>

                                    <input id=\"filterProf\" type=\"radio\" name=\"categoryFilter\" value=\"pro\" class=\"filter\">
                                    <label for=\"filterProf\">Professional Drinks</label>

                                    <input id=\"filterOri\" type=\"radio\" name=\"categoryFilter\" value=\"ori\" class=\"filter\">
                                    <label for=\"filterOri\">Original Drinks</label>
                                </div>
                            </div>
                            <div class=\"col-lg-6 order-lg-2 filterFormRight\">
                                <p>Search between dates</p>
                                <input class=\"filterFormDate\" type=\"date\" name=\"time1\" value=\"\">
                                <input class=\"filterFormDate\" type=\"date\" name=\"time2\" value=\"\">
                            </div>
                        </div>
                        <div class=\"row filterFormSubmit\">
                            <input class=\"btn btn-primary my-btn-xl mt-5\" type=\"submit\" name=\"filterFormSubmit\" value=\"Filter\">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    ";
        // line 42
        if ((twig_length_filter($this->env, ($context["pages"] ?? null)) == 0)) {
            // line 43
            echo "        <section>
            <div class='mi-container-centered'>
                <div class='container'>
                    <div class='row align-items-center'>
                        <h1>No Search Results!</h1>
                    </div>
                </div>
            </div>
        </section>
    ";
        } else {
            // line 53
            echo "        ";
            $context["sideCounter"] = 0;
            // line 54
            echo "
        <!-- GET CURRENT PAGE -->
        ";
            // line 56
            $context["page"] = (($__internal_f607aeef2c31a95a7bf963452dff024ffaeb6aafbe4603f9ca3bec57be8633f4 = ($context["pages"] ?? null)) && is_array($__internal_f607aeef2c31a95a7bf963452dff024ffaeb6aafbe4603f9ca3bec57be8633f4) || $__internal_f607aeef2c31a95a7bf963452dff024ffaeb6aafbe4603f9ca3bec57be8633f4 instanceof ArrayAccess ? ($__internal_f607aeef2c31a95a7bf963452dff024ffaeb6aafbe4603f9ca3bec57be8633f4[(($context["currentPagi"] ?? null) - 1)] ?? null) : null);
            // line 57
            echo "
        ";
            // line 58
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(($context["page"] ?? null));
            foreach ($context['_seq'] as $context["_key"] => $context["drink"]) {
                // line 59
                echo "            ";
                $context["side1"] = "";
                // line 60
                echo "            ";
                $context["side2"] = "";
                // line 61
                echo "
            ";
                // line 62
                if ((($context["sideCounter"] ?? null) % 2 == 0)) {
                    // line 63
                    echo "                ";
                    $context["side1"] = "order-lg-2";
                    // line 64
                    echo "                ";
                    $context["side2"] = "order-lg-1";
                    // line 65
                    echo "            ";
                }
                // line 66
                echo "            <section>
                <div class='mi-container-centered'>
                    <div class='container'>
                        <div class='row align-items-center'>
                            <div class='col-lg-5 ";
                // line 70
                echo twig_escape_filter($this->env, ($context["side1"] ?? null), "html", null, true);
                echo " '>
                                <div class='p-5'>
                                    <img class='img-fluid rounded-circle' src='/drinks/img/";
                // line 72
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["drink"], "image", [], "any", false, false, false, 72), "html", null, true);
                echo "' alt='";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["drink"], "image", [], "any", false, false, false, 72), "html", null, true);
                echo "'>
                                </div>
                            </div>
                            <div class='col-lg-7 ";
                // line 75
                echo twig_escape_filter($this->env, ($context["side2"] ?? null), "html", null, true);
                echo " '>
                                <div class='p-5'>
                                    <h2 class='display-4'> ";
                // line 77
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["drink"], "title", [], "any", false, false, false, 77), "html", null, true);
                echo "</h2>
                                    <p>Author: ";
                // line 78
                echo twig_escape_filter($this->env, twig_capitalize_string_filter($this->env, twig_get_attribute($this->env, $this->source, $context["drink"], "author_name", [], "any", false, false, false, 78)), "html", null, true);
                echo " ";
                if (((twig_get_attribute($this->env, $this->source, $context["drink"], "author_id", [], "any", false, false, false, 78) == ($context["sessionId"] ?? null)) || (($context["sessionId"] ?? null) == 1))) {
                    echo " - <a href='/drinks/user/myDrinks/editDrink/";
                    echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["drink"], "id", [], "any", false, false, false, 78), "html", null, true);
                    echo "' class='myDrinksButtons myDrinksButtonsEdit'>Edit</a>";
                }
                echo "</p>
                                    <a href='/drinks/ourDrinks/drink/";
                // line 79
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["drink"], "id", [], "any", false, false, false, 79), "html", null, true);
                echo "' class='btn btn-primary btn-xl rounded-pill mt-5'>Make This Drink</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            ";
                // line 86
                $context["sideCounter"] = (($context["sideCounter"] ?? null) + 1);
                // line 87
                echo "        ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['drink'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 88
            echo "
        <!-- PAGINATION -->
        ";
            // line 90
            if ((twig_length_filter($this->env, ($context["pages"] ?? null)) > 1)) {
                // line 91
                echo "            <section>
                <div class='mi-container-centered'>
                    <div class='container'>
                        <div class='row align-items-center'>
                            <div class='col-lg-12 text-center'>
                                ";
                // line 96
                if ((($context["currentPagi"] ?? null) != 1)) {
                    // line 97
                    echo "                                    <a href='/drinks/ourDrinks/";
                    echo twig_escape_filter($this->env, (($context["currentPagi"] ?? null) - 1), "html", null, true);
                    echo twig_escape_filter($this->env, ($context["queryString"] ?? null), "html", null, true);
                    echo "' class='pagiButton pagiBack'><i class='fas fa-arrow-left'></i></a>
                                ";
                }
                // line 99
                echo "                                ";
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable(range(1, twig_length_filter($this->env, ($context["pages"] ?? null))));
                foreach ($context['_seq'] as $context["_key"] => $context["i"]) {
                    // line 100
                    echo "                                    <a href='/drinks/ourDrinks/";
                    echo twig_escape_filter($this->env, $context["i"], "html", null, true);
                    echo twig_escape_filter($this->env, ($context["queryString"] ?? null), "html", null, true);
                    echo "' class='pagiButton'>";
                    echo twig_escape_filter($this->env, $context["i"], "html", null, true);
                    echo "</a>
                                ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['i'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 102
                echo "                                ";
                if ((($context["currentPagi"] ?? null) != twig_length_filter($this->env, ($context["pages"] ?? null)))) {
                    // line 103
                    echo "                                    <a href='/drinks/ourDrinks/";
                    echo twig_escape_filter($this->env, (($context["currentPagi"] ?? null) + 1), "html", null, true);
                    echo twig_escape_filter($this->env, ($context["queryString"] ?? null), "html", null, true);
                    echo "' class='pagiButton pagiForward'><i class='fas fa-arrow-right'></i></a>
                                ";
                }
                // line 105
                echo "                            </div>
                        </div>
                    </div>
                </div>
            </section>
        ";
            }
            // line 111
            echo "    ";
        }
    }

    public function getTemplateName()
    {
        return "drinks.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  256 => 111,  248 => 105,  241 => 103,  238 => 102,  226 => 100,  221 => 99,  214 => 97,  212 => 96,  205 => 91,  203 => 90,  199 => 88,  193 => 87,  191 => 86,  181 => 79,  171 => 78,  167 => 77,  162 => 75,  154 => 72,  149 => 70,  143 => 66,  140 => 65,  137 => 64,  134 => 63,  132 => 62,  129 => 61,  126 => 60,  123 => 59,  119 => 58,  116 => 57,  114 => 56,  110 => 54,  107 => 53,  95 => 43,  93 => 42,  58 => 10,  50 => 4,  46 => 3,  35 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "drinks.twig", "/opt/lampp/htdocs/drinks/templates/drinks.twig");
    }
}
