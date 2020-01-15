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

/* index.twig */
class __TwigTemplate_2057f9f4a8f80e0e1e95de244a3bad3eb25ef7792dabb92ebe43eabfe80d15a8 extends \Twig\Template
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
        $this->parent = $this->loadTemplate("bases/base.twig", "index.twig", 1);
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_content($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 4
        echo "    <header class=\"myBackgroundImage text-center text-white\">
        <div class=\"masthead-content\">
            <div class=\"container\">
                <h1 class=\"masthead-heading mb-0\">Cocktails</h1>
                <h2 class=\"masthead-subheading mb-0\">Aidan</h2>
                <a href=\"/drinks/ourDrinks/1\" class=\"btn btn-primary btn-xl rounded-pill mt-5\">See Our Cocktails</a>
            </div>
        </div>
    </header>  <section class=\"sectionMarginTop\">
    <div class=\"container\">
        <div class=\"row align-items-center\">
            <div class=\"col-lg-6 order-lg-2\">
                <div class=\"p-5\">
                    <img class=\"img-fluid rounded-circle\" src=\"img/professionalDrinks.jpg\" alt=\"\">
                </div>
            </div>
            <div class=\"col-lg-6 order-lg-1\">
                <div class=\"p-5\">
                    <h2 class=\"display-4\">Professional cocktails!</h2>
                    <p>Professional cocktails, just like you'd find at the bar, now at your fingertips, just like they're served in the most prestigious of cocktail bars.</p>
                </div>
            </div>
        </div>
    </div>
</section>

    <section class=\"sectionMarginTop\">
        <div class=\"container\">
            <div class=\"row align-items-center\">
                <div class=\"col-lg-6\">
                    <div class=\"p-5\">
                        <img class=\"img-fluid rounded-circle\" src=\"img/originalDrinks.jpg\" alt=\"\">
                    </div>
                </div>
                <div class=\"col-lg-6\">
                    <div class=\"p-5\">
                        <h2 class=\"display-4\">Original cocktails!</h2>
                        <p>Original cocktails, made and designed by Greg himself, all based off popular video games, series, movies and more!</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
";
    }

    public function getTemplateName()
    {
        return "index.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  50 => 4,  46 => 3,  35 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "index.twig", "/opt/lampp/htdocs/drinks/templates/index.twig");
    }
}
