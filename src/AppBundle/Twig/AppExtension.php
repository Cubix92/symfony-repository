<?php

namespace AppBundle\Twig;

use AppBundle\Twig\Sort_TokenParser;

class AppExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('resize', array($this, 'resizeFilter')),
            new \Twig_SimpleFilter('querystring', array($this, 'querystringFilter')),
            new \Twig_SimpleFilter('ceil', array($this, 'ceilFilter')),
        );
    }

    public function resizeFilter($text)
    {
        $count = substr_count($text, "\n") + 1;
        return $count;
    }

    public function querystringFilter($queryParams, $customParams = array()) {
        $querystring = "?";

        $params = array_merge($queryParams, $customParams);

        if($params) {
            foreach($params as $key => $value) {
                $querystring .= $key ."=". $value . "&";
            }
        }

        return substr($querystring, 0, -1);
    }

    public function ceilFilter($value) {
        return ceil($value);
    }

    public function getName()
    {
        return 'app_extension';
    }
}