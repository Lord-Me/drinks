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

/* users.twig */
class __TwigTemplate_5b1e3f246272efddd7496c85155d0f82f884c951bdf6c80a1f2903ec8ffcfb81 extends \Twig\Template
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
        $this->parent = $this->loadTemplate("bases/base.twig", "users.twig", 1);
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
            <div class=\"row align-items-center\">
                <div class=\"col-lg-12\">
                    <div class=\"p-5 text-center\">
                        <form method=\"get\">
                            <div class=\"\">
                                <input type=\"radio\" name=\"userFilter\" value=\"all\" id=\"all\" checked=\"checked\">
                                <label for=\"all\">Show All Users</label><br>
                                <input type=\"radio\" name=\"userFilter\" value=\"admin\" id=\"admin\">
                                <label for=\"admin\">Show Administrators</label><br>
                                <input type=\"radio\" name=\"userFilter\" value=\"user\" id=\"user\">
                                <label for=\"user\">Show Basic Users</label><br>
                            </div>
                            <input type=\"submit\" name=\"userSubmit\" value=\"Filter\" class=\"btn btn-primary my-btn-xl mt-5\">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- GET CURRENT PAGE -->
    ";
        // line 28
        $context["page"] = (($__internal_f607aeef2c31a95a7bf963452dff024ffaeb6aafbe4603f9ca3bec57be8633f4 = ($context["pages"] ?? null)) && is_array($__internal_f607aeef2c31a95a7bf963452dff024ffaeb6aafbe4603f9ca3bec57be8633f4) || $__internal_f607aeef2c31a95a7bf963452dff024ffaeb6aafbe4603f9ca3bec57be8633f4 instanceof ArrayAccess ? ($__internal_f607aeef2c31a95a7bf963452dff024ffaeb6aafbe4603f9ca3bec57be8633f4[(($context["currentPagi"] ?? null) - 1)] ?? null) : null);
        // line 29
        echo "
    <section>
        <div class='mi-container-centered'>
            <div class='container'>
                <div class='row align-items-center'>
                    <div class='col-lg-12'>
                        <div class='p-5'>
                            <table class='usersTable'>
                                ";
        // line 37
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["page"] ?? null));
        foreach ($context['_seq'] as $context["_key"] => $context["user"]) {
            // line 38
            echo "                                    <tr>
                                        <td>";
            // line 39
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["user"], "username", [], "any", false, false, false, 39), "html", null, true);
            echo "</td>
                                        <td>";
            // line 40
            echo twig_escape_filter($this->env, twig_capitalize_string_filter($this->env, twig_get_attribute($this->env, $this->source, $context["user"], "firstName", [], "any", false, false, false, 40)), "html", null, true);
            echo "</td>
                                        <td>";
            // line 41
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["user"], "lastName", [], "any", false, false, false, 41), "html", null, true);
            echo "</td>
                                        ";
            // line 42
            if ((twig_get_attribute($this->env, $this->source, $context["user"], "role", [], "any", false, false, false, 42) == 1)) {
                echo "<td>Administrator</td>";
            }
            // line 43
            echo "                                        ";
            if ((twig_get_attribute($this->env, $this->source, $context["user"], "role", [], "any", false, false, false, 43) == 2)) {
                echo "<td>User</td>";
            }
            // line 44
            echo "                                        ";
            if ((twig_get_attribute($this->env, $this->source, $context["user"], "id", [], "any", false, false, false, 44) == ($context["sessionId"] ?? null))) {
                // line 45
                echo "                                            <td class=\"myDrinksButtonsDelete\" colspan=\"2\">No se puede modificar el mismo usuario</td>
                                        ";
            } else {
                // line 47
                echo "                                            <td><a href='/drinks/user/admin/users/changeRole/";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["user"], "id", [], "any", false, false, false, 47), "html", null, true);
                echo "' class='myDrinksButtons myDrinksButtonsEdit'>Change Role</a></td>
                                            <td><a href='/drinks/user/admin/users/deleteUser/";
                // line 48
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["user"], "id", [], "any", false, false, false, 48), "html", null, true);
                echo "' class='myDrinksButtons myDrinksButtonsDelete'>Delete User</a></td>
                                        ";
            }
            // line 50
            echo "                                    </tr>
                                ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['user'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 52
        echo "                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- PAGINATION -->
    ";
        // line 61
        if ((twig_length_filter($this->env, ($context["pages"] ?? null)) > 1)) {
            // line 62
            echo "        <section>
            <div class='mi-container-centered'>
                <div class='container'>
                    <div class='row align-items-center'>
                        <div class='col-lg-12 text-center'>
                            ";
            // line 67
            if ((($context["currentPagi"] ?? null) != 1)) {
                // line 68
                echo "                                <a href='/drinks/user/admin/users/";
                echo twig_escape_filter($this->env, (($context["currentPagi"] ?? null) - 1), "html", null, true);
                echo twig_escape_filter($this->env, ($context["queryString"] ?? null), "html", null, true);
                echo "' class='pagiButton pagiBack'><i class='fas fa-arrow-left'></i></a>
                            ";
            }
            // line 70
            echo "                            ";
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(range(1, twig_length_filter($this->env, ($context["pages"] ?? null))));
            foreach ($context['_seq'] as $context["_key"] => $context["i"]) {
                // line 71
                echo "                                <a href='/drinks/user/admin/users/";
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
            // line 73
            echo "                            ";
            if ((($context["currentPagi"] ?? null) != twig_length_filter($this->env, ($context["pages"] ?? null)))) {
                // line 74
                echo "                                <a href='/drinks/user/admin/users/";
                echo twig_escape_filter($this->env, (($context["currentPagi"] ?? null) + 1), "html", null, true);
                echo twig_escape_filter($this->env, ($context["queryString"] ?? null), "html", null, true);
                echo "' class='pagiButton pagiForward'><i class='fas fa-arrow-right'></i></a>
                            ";
            }
            // line 76
            echo "                        </div>
                    </div>
                </div>
            </div>
        </section>
    ";
        }
    }

    public function getTemplateName()
    {
        return "users.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  196 => 76,  189 => 74,  186 => 73,  174 => 71,  169 => 70,  162 => 68,  160 => 67,  153 => 62,  151 => 61,  140 => 52,  133 => 50,  128 => 48,  123 => 47,  119 => 45,  116 => 44,  111 => 43,  107 => 42,  103 => 41,  99 => 40,  95 => 39,  92 => 38,  88 => 37,  78 => 29,  76 => 28,  50 => 4,  46 => 3,  35 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "users.twig", "/opt/lampp/htdocs/drinks/templates/users.twig");
    }
}
