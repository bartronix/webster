<?php

namespace System\Core;

use Exception;

class View
{
    public $data = [];
    public $showView = true;
    public $filename = '';
    protected $prepend = [];
    protected $append = [];
    protected $message = '';
    public $layout;
    public $querystring;
    public $urlparams;

    public function __construct($filename = null)
    {
        $this->filename = $filename;
    }

    public function __set($key, $value)
    {
        $this->data[$key] = $value;
    }

    public function __get($key)
    {
        return (isset($this->data[$key])) ? $this->data[$key] : null;
    }

    public function __isset($key)
    {
        return (isset($this->data[$key])) ? $this->data[$key] : null;
    }

    public function addPrepend($element)
    {
        $this->prepend[] = $element;
    }

    public function addAppend($element)
    {
        $this->append[] = $element;
    }

    public function addCss($url)
    {
        $this->layout->addCss($url);
    }

    public function addJs($url)
    {
        $this->layout->addJs($url);
    }

    public function render($data = false)
    {
        $rendered = '';
        if (!$this->showView) {
            return;
        }
        //prepend
        foreach ($this->prepend as $element) {
            $filename = APP_ROOT.'/app/views/'.$element.'.php';
            ob_start();
            require $filename;
            $rendered = ob_get_contents();
            ob_end_clean();
        }
        //main render
        if ($data) {
            if (!is_array($data)) {
                throw new Exception('Only an array is allowed.');
            }
            $this->data = array_merge($this->data, $data);
        }

        $filename = APP_ROOT.'/app/views/'.$this->filename.'.php';
        if (!file_exists($filename)) {
            throw new Exception('View not found. Check if a corresponding viewfile exists.<br />Expected view: /app/views/'.$this->filename.'.php');
        }
        ob_start();
        require $filename;
        $rendered .= ob_get_contents();
        ob_end_clean();
        //append
        foreach ($this->append as $element) {
            $filename = APP_ROOT.'/app/views/'.$element.'.php';
            ob_start();
            require $filename;
            $rendered .= ob_get_contents();
            ob_end_clean();
        }

        return $rendered;
    }

    public function showFlashMessage($key = 'default')
    {
        if (isset($_SESSION['flashmessage'][$key])) {
            switch (strtolower($_SESSION['flashmessage'][$key][1])) {
                case 'error':
                    echo '<div class="flash flash-error">'.$_SESSION['flashmessage'][$key][0].'</div>';
                    break;
                case 'success':
                    echo '<div class="flash flash-success">'.$_SESSION['flashmessage'][$key][0].'</div>';
                    break;
                default:
                    echo '<div class="flash flash-default">'.$_SESSION['flashmessage'][$key][0].'</div>';
            }
            unset($_SESSION['flashmessage'][$key]);
        }
    }
}
