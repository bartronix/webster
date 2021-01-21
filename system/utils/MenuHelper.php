<?php

//Usage: $menuItems["photography"] = array("photography", true);  params: menu name / url / target blank or not

namespace System\Utils;

class MenuHelper
{
    public $menuItems = [];

    public function __construct($menuItems = [])
    {
        $this->menuItems = $menuItems;
    }

    public function generate($currentPage, $langPrefix = false)
    {
        echo '<ul class="menulist">';
        foreach ($this->menuItems as $page => $item) {
            $target = '_self';
            $url = $item;
            if (gettype($item) === 'array') {
                $url = $item[0];
                if (isset($item[1]) && $item[1]) {
                    $target = '_blank';
                }
            }
            $currentPageParts = explode('/', $currentPage);
            array_shift($currentPageParts);
            if (sizeof($currentPageParts) > 1) {
                $currentPage = strtolower('/'.$currentPageParts[0]);
                if ($langPrefix) {
                    $currentPage = strtolower('/'.$currentPageParts[0].'/'.$currentPageParts[1]);
                }
            } elseif (sizeof($currentPageParts) == 1) {
                $currentPage = strtolower('/'.$currentPageParts[0]);
            }
            if ($currentPage === strtolower($url)) {
                echo '<li><a target="'.$target.'" href="'.$url.'" class="selected">'.$page.'</a></li>';
            } else {
                echo '<li><a target="'.$target.'" href="'.$url.'">'.$page.'</a></li>';
            }
        }
        echo '</ul>';
    }
}
