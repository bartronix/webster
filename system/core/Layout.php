<?php

namespace System\Core;

class Layout
{
    public $content;
    public $filename = 'default';
    public $showLayout = true;
    public $request = '';
    public $cssFiles = [];
    public $jsFiles = [];
    public $pageTitle = '';
    public $metaTags = [];

    public function addCss($url)
    {
        $this->cssFiles[] = $url;
    }

    public function addJs($url)
    {
        $this->jsFiles[] = $url;
    }

    public function setPageTitle($title)
    {
        $this->pageTitle = $title;
    }

    public function addMetaTag($tag)
    {
        $this->metaTags[] = $tag;
    }

    public function render()
    {
        if ($this->showLayout) {
            $filename = APP_ROOT.'/app/layout/'.$this->filename.'.php';
            ob_start();
            require $filename;
            $rendered = ob_get_contents();
            ob_end_clean();
            echo $rendered;
        } else {
            echo $this->content;
        }
    }
}
