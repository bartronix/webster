<?php

namespace System\Core;

use System\Core\Exceptions\NotFoundException;

class Bootstrap
{
    public function init()
    {
        //request
        $request = new Request();
        $request->uri = '/'.trim(str_replace(WEB_ROOT, '', $_SERVER['REQUEST_URI']), '/');
        //router
        $router = new Router();
        $router->route($request);
        if (class_exists($request->controller) && method_exists($request->controller, $request->action)) {
            //view
            $view = new View($request->view);
            $view->querystring = $request->querystring;
            $view->urlparams = $request->urlparams;
            //controller
            $controller = new $request->controller();
            //check for a pre-action to execute first
            if (method_exists($controller, 'preAction')) {
                $controller->preAction();
            }
            $controller->view = $view;
            $controller->querystring = $request->querystring;
            $controller->urlparams = $request->urlparams;
            $controller->language = $request->language;
            if (isset($_POST)) {
                $controller->data = $_POST;
            }
            //check if ajax
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                $controller->isAjax = true;
            }
            //execute controller method
            $action = $request->action;
            $controller->$action($request->paramvalue);
            //render layout
            $view->layout = $controller->layout;
            $view->layout->content = $view->render();
            $view->layout->request = $request;
            $view->layout->render();
        } else {
            throw new NotFoundException();
        }
    }
}
