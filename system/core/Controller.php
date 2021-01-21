<?php

namespace System\Core;

class Controller
{
    public $view;
    public $layout;
    public $data;
    public $querystring;
    public $urlparams;
    public $language;
    public $isAjax = false;

    public function __construct()
    {
        $this->layout = new Layout();
    }

    public function disableLayout()
    {
        $this->layout->showLayout = false;
    }

    public function disableView()
    {
        $this->view->showView = false;
    }

    public function setView($filename)
    {
        $this->view->filename = $filename;
    }

    public function setLayout($filename)
    {
        $this->layout->filename = $filename;
    }

    public function prepend($filename)
    {
        $this->view->addPrepend($filename);
    }

    public function append($filename)
    {
        $this->view->addAppend($filename);
    }

    //type error, success or default
    public function setFlashMessage($message, $type = 'default', $key = 'default')
    {
        $_SESSION['flashmessage'][$key] = [$message, $type];
    }
}
