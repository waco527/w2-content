<?php

/* @galleries/helpers/attachment.twig */
class __TwigTemplate_43566e27ea59b84ddc207c738250872bc8f9e13b08f23bc679d20a7ebca7de2c extends Twig_SupTwgSgg_Template
{
    public function __construct(Twig_SupTwgSgg_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
    }

    // line 1
    public function getimage($__attachmentId__ = null, $__settings__ = null, ...$__varargs__)
    {
        $context = $this->env->mergeGlobals(array(
            "attachmentId" => $__attachmentId__,
            "settings" => $__settings__,
            "varargs" => $__varargs__,
        ));

        $blocks = array();

        ob_start();
        try {
            // line 2
            list($context["width"], $context["height"], $context["crop"]) =             array(0, 0, 0);
            // line 4
            if (($this->getAttribute($this->getAttribute(($context["settings"] ?? null), "area", array()), "photo_width_unit", array()) == 0)) {
                // line 5
                $context["width"] = $this->getAttribute($this->getAttribute(($context["settings"] ?? null), "area", array()), "photo_width", array());
            }
            // line 8
            if (($this->getAttribute($this->getAttribute(($context["settings"] ?? null), "area", array()), "photo_height_unit", array()) == 0)) {
                // line 9
                $context["height"] = $this->getAttribute($this->getAttribute(($context["settings"] ?? null), "area", array()), "photo_height", array());
            }
            // line 12
            if ((($this->getAttribute($this->getAttribute(($context["settings"] ?? null), "area", array()), "grid", array()) == 0) || ($this->getAttribute($this->getAttribute(($context["settings"] ?? null), "area", array()), "grid", array()) == 3))) {
                // line 13
                $context["crop"] = 1;
            } else {
                // line 15
                $context["height"] = null;
            }
            // line 18
            echo Twig_SupTwgSgg_escape_filter($this->env, call_user_func_array($this->env->getFunction('get_attachment')->getCallable(), array(($context["attachmentId"] ?? null), ($context["width"] ?? null), ($context["height"] ?? null), ($context["crop"] ?? null))), "html", null, true);
        } catch (Exception $e) {
            ob_end_clean();

            throw $e;
        } catch (Throwable $e) {
            ob_end_clean();

            throw $e;
        }

        return ('' === $tmp = ob_get_clean()) ? '' : new Twig_SupTwgSgg_Markup($tmp, $this->env->getCharset());
    }

    public function getTemplateName()
    {
        return "@galleries/helpers/attachment.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  54 => 18,  51 => 15,  48 => 13,  46 => 12,  43 => 9,  41 => 8,  38 => 5,  36 => 4,  34 => 2,  21 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        //@trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_SupTwgSgg_Source("", "@galleries/helpers/attachment.twig", "C:\\xampp\\htdocs\\w2\\wp-content\\plugins\\gallery-by-supsystic\\src\\GridGallery\\Galleries\\views\\helpers\\attachment.twig");
    }
}
