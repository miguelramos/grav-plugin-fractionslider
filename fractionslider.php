<?php
namespace Grav\Plugin;

use \Grav\Common\Plugin;
use \Grav\Common\Grav;
use \Grav\Common\Page\Page;

class FractionsliderPlugin extends Plugin
{
    /**
     * @return array
     */
    public static function getSubscribedEvents() {
        return [
            'onPageInitialized'   => ['onPageInitialized', 0],
            'onTwigTemplatePaths' => ['onTwigTemplatePaths', 0],
            'onTwigSiteVariables' => ['onTwigSiteVariables', 0],
            'onFractionInit'      => ['onFractionInit', 0]
        ];
    }

    /**
     * Initialize configuration
     */
    public function onPageInitialized()
    {
        $defaults = (array) $this->config->get('plugins.fractionslider');

        $this->grav->fireEvent('onFractionInit');

        /** @var Page $page */
        $page = $this->grav['page'];

        if (isset($page->header()->fractionslider)) {
            $page->header()->fractionslider = array_merge($defaults, $page->header()->fractionslider);
        } else {
            $page->header()->fractionslider = $defaults;
        }
    }

    /**
     *
     */
    public function onFractionInit()
    {
        $page = $this->grav['page'];
        $path = $page->path().'/_fraction/';
        $file = 'fraction.html.twig';

        if (file_exists($path.$file)) {
            $twig = $this->grav['twig'];

            $twig->loader()->addPath($path);
            $page->header()->fractionslider['content'] = $twig->twig()->render($file, array());
        }
    }

    /**
     * Add current directory to twig lookup paths.
     */
    public function onTwigTemplatePaths()
    {
        $this->grav['twig']->twig_paths[] = __DIR__ . '/templates';
    }

    /**
     * Set needed variables to display cart.
     */
    public function onTwigSiteVariables()
    {
        if ($this->config->get('plugins.fractionslider.built_in_css')) {
            $this->grav['assets']
                ->add('plugin://fractionslider/css/fractionslider.css')
                ->add('plugin://fractionslider/css/fractionslider.custom.css')
                ->add('plugin://fractionslider/js/jquery.fractionslider.min.js', 80);
        }
    }
}