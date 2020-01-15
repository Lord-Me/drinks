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

/* myDrinks.twig */
class __TwigTemplate_237612792a019a5f05454229cc9c6552b356cd113c7ca1b4d73c54dbbc76a14a extends \Twig\Template
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
        $this->parent = $this->loadTemplate("bases/base.twig", "myDrinks.twig", 1);
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
                    <form method=\"get\" id=\"filterContainer\" class=\"filterForm\">
                        <div class=\"row\">
                            <div class=\"col-lg-6 order-lg-1 filterFormLeft\">
                                <div class=\"row filterFormSearch\">
                                    <label for=\"titleSearch\">Search title: </label>
                                    <input id=\"titleSearch\" type=\"text\" name=\"titleSearch\" placeholder=\"Search...\" class=\"filter\"><br>
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

    <section>
        <div class=\"container\">
            <div class=\"row align-items-center\">
                <div class=\"col-lg-12\">
                    <div class=\"p-5 text-center\">
                        <a class=\"newDrinkButton btn btn-primary btn-xl\" href=\"/drinks/user/myDrinks/newDrink\">Create New Drink</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    ";
        // line 54
        if ((twig_length_filter($this->env, ($context["pages"] ?? null)) == 0)) {
            // line 55
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
            // line 65
            echo "        <!-- GET CURRENT PAGE -->
        ";
            // line 66
            $context["page"] = (($__internal_f607aeef2c31a95a7bf963452dff024ffaeb6aafbe4603f9ca3bec57be8633f4 = ($context["pages"] ?? null)) && is_array($__internal_f607aeef2c31a95a7bf963452dff024ffaeb6aafbe4603f9ca3bec57be8633f4) || $__internal_f607aeef2c31a95a7bf963452dff024ffaeb6aafbe4603f9ca3bec57be8633f4 instanceof ArrayAccess ? ($__internal_f607aeef2c31a95a7bf963452dff024ffaeb6aafbe4603f9ca3bec57be8633f4[(($context["currentPagi"] ?? null) - 1)] ?? null) : null);
            // line 67
            echo "
        <section>
            <div class='mi-container-centered'>
                <div class='container'>
                    <div class='row align-items-center'>
                        <div class='col-lg-12'>
                            <div class='p-5'>
                                <table class='myDrinksTable'>
                                    ";
            // line 75
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(($context["page"] ?? null));
            foreach ($context['_seq'] as $context["_key"] => $context["drink"]) {
                // line 76
                echo "                                        ";
                if (((($context["sessionId"] ?? null) == 1) || (twig_get_attribute($this->env, $this->source, $context["drink"], "author_id", [], "any", false, false, false, 76) == ($context["sessionId"] ?? null)))) {
                    // line 77
                    echo "                                            ";
                    if ((twig_get_attribute($this->env, $this->source, $context["drink"], "view", [], "any", false, false, false, 77) == 1)) {
                        echo " <!-- Set as not deleted -->
                                                <tr>
                                                    <td><a href='/drinks/ourDrinks/drink/";
                        // line 79
                        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["drink"], "id", [], "any", false, false, false, 79), "html", null, true);
                        echo "' class='myDrinksButtons myDrinksButtonsTitle'>";
                        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["drink"], "title", [], "any", false, false, false, 79), "html", null, true);
                        echo "</a></td>
                                                    <td>";
                        // line 80
                        echo twig_escape_filter($this->env, twig_capitalize_string_filter($this->env, twig_get_attribute($this->env, $this->source, $context["drink"], "author_name", [], "any", false, false, false, 80)), "html", null, true);
                        echo "</td>
                                                    ";
                        // line 81
                        if ((twig_get_attribute($this->env, $this->source, $context["drink"], "category", [], "any", false, false, false, 81) == 1)) {
                            echo "<td>Professional</td>";
                        }
                        // line 82
                        echo "                                                    ";
                        if ((twig_get_attribute($this->env, $this->source, $context["drink"], "category", [], "any", false, false, false, 82) == 2)) {
                            echo "<td>Original</td>";
                        }
                        // line 83
                        echo "                                                    <td>";
                        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["drink"], "published_at", [], "any", false, false, false, 83), "html", null, true);
                        echo "</td>
                                                    <td><a href='/drinks/user/myDrinks/editDrink/";
                        // line 84
                        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["drink"], "id", [], "any", false, false, false, 84), "html", null, true);
                        echo "' class='myDrinksButtons myDrinksButtonsEdit'>Edit</a></td>
                                                    <td><a href='/drinks/user/myDrinks/toggleDeleteDrink/";
                        // line 85
                        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["drink"], "id", [], "any", false, false, false, 85), "html", null, true);
                        echo "' class='myDrinksButtons myDrinksButtonsDelete'>Delete</a></td>
                                                </tr>
                                            ";
                    } elseif ((twig_get_attribute($this->env, $this->source,                     // line 87
$context["drink"], "view", [], "any", false, false, false, 87) == 0)) {
                        echo " <!-- Set as deleted -->
                                                <tr>
                                                    <td><a href='/drinks/ourDrinks/drink/";
                        // line 89
                        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["drink"], "id", [], "any", false, false, false, 89), "html", null, true);
                        echo "' class='myDrinksButtonsRed myDrinksButtonsTitle'>";
                        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["drink"], "title", [], "any", false, false, false, 89), "html", null, true);
                        echo "</a></td>
                                                    <td class='myDrinksButtonsRed'>";
                        // line 90
                        echo twig_escape_filter($this->env, twig_capitalize_string_filter($this->env, twig_get_attribute($this->env, $this->source, $context["drink"], "author_name", [], "any", false, false, false, 90)), "html", null, true);
                        echo "</td>
                                                    ";
                        // line 91
                        if ((twig_get_attribute($this->env, $this->source, $context["drink"], "category", [], "any", false, false, false, 91) == 1)) {
                            echo "<td class='myDrinksButtonsRed'>Professional</td>";
                        }
                        // line 92
                        echo "                                                    ";
                        if ((twig_get_attribute($this->env, $this->source, $context["drink"], "category", [], "any", false, false, false, 92) == 2)) {
                            echo "<td class='myDrinksButtonsRed'>Original</td>";
                        }
                        // line 93
                        echo "                                                    <td class='myDrinksButtonsRed'>";
                        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["drink"], "published_at", [], "any", false, false, false, 93), "html", null, true);
                        echo "</td>
                                                    <td><a href='/drinks/user/myDrinks/editDrink/";
                        // line 94
                        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["drink"], "id", [], "any", false, false, false, 94), "html", null, true);
                        echo "' class='myDrinksButtons myDrinksButtonsEdit'>Edit</a></td>
                                                    <td><a href='/drinks/user/myDrinks/toggleDeleteDrink/";
                        // line 95
                        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["drink"], "id", [], "any", false, false, false, 95), "html", null, true);
                        echo "' class='myDrinksButtons myDrinksButtonsUndelete'>Undelete</a></td>
                                                </tr>
                                            ";
                    }
                    // line 98
                    echo "                                        ";
                }
                // line 99
                echo "                                    ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['drink'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 100
            echo "                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- PAGINATION -->
        ";
            // line 109
            if ((twig_length_filter($this->env, ($context["pages"] ?? null)) > 1)) {
                // line 110
                echo "            <section>
                <div class='mi-container-centered'>
                    <div class='container'>
                        <div class='row align-items-center'>
                            <div class='col-lg-12 text-center'>
                                ";
                // line 115
                if ((($context["currentPagi"] ?? null) != 1)) {
                    // line 116
                    echo "                                    <a href='/drinks/user/myDrinks/";
                    echo twig_escape_filter($this->env, (($context["currentPagi"] ?? null) - 1), "html", null, true);
                    echo twig_escape_filter($this->env, ($context["queryString"] ?? null), "html", null, true);
                    echo "' class='pagiButton pagiBack'><i class='fas fa-arrow-left'></i></a>
                                ";
                }
                // line 118
                echo "                                ";
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable(range(1, twig_length_filter($this->env, ($context["pages"] ?? null))));
                foreach ($context['_seq'] as $context["_key"] => $context["i"]) {
                    // line 119
                    echo "                                    <a href='/drinks/user/myDrinks/";
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
                // line 121
                echo "                                ";
                if ((($context["currentPagi"] ?? null) != twig_length_filter($this->env, ($context["pages"] ?? null)))) {
                    // line 122
                    echo "                                    <a href='/drinks/user/myDrinks/";
                    echo twig_escape_filter($this->env, (($context["currentPagi"] ?? null) + 1), "html", null, true);
                    echo twig_escape_filter($this->env, ($context["queryString"] ?? null), "html", null, true);
                    echo "' class='pagiButton pagiForward'><i class='fas fa-arrow-right'></i></a>
                                ";
                }
                // line 124
                echo "                            </div>
                        </div>
                    </div>
                </div>
            </section>
        ";
            }
            // line 130
            echo "    ";
        }
    }

    public function getTemplateName()
    {
        return "myDrinks.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  289 => 130,  281 => 124,  274 => 122,  271 => 121,  259 => 119,  254 => 118,  247 => 116,  245 => 115,  238 => 110,  236 => 109,  225 => 100,  219 => 99,  216 => 98,  210 => 95,  206 => 94,  201 => 93,  196 => 92,  192 => 91,  188 => 90,  182 => 89,  177 => 87,  172 => 85,  168 => 84,  163 => 83,  158 => 82,  154 => 81,  150 => 80,  144 => 79,  138 => 77,  135 => 76,  131 => 75,  121 => 67,  119 => 66,  116 => 65,  104 => 55,  102 => 54,  50 => 4,  46 => 3,  35 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "myDrinks.twig", "/opt/lampp/htdocs/drinks/templates/myDrinks.twig");
    }
}
