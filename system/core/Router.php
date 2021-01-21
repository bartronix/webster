<?php

namespace System\Core;

use Exception;
use System\Utils\Functions as coreFunctions;

class Router
{
    public function route($request)
    {
        global $prefixes;
        $actionPrefix = '';
        //page
        $urlparts = coreFunctions::parseUrl();
        //check for language
        $language = $this->getLanguage($urlparts);
        //check for prefix
        $this->checkPrefixes($urlparts, $actionPrefix);
        //set values
        $page = $this->parsePageName($urlparts);
        $action = (!empty($urlparts[1]) ? $urlparts[1] : 'index');
        $paramvalue = (!empty($urlparts[2]) ? $urlparts[2] : null);
        //check for custom router rules located in /system/core/ConfigRoutes.php
        $this->checkCustomRoutes($page, $action, $paramvalue, $language, $request);
        //put querystring parameters in an array
        parse_str($_SERVER['QUERY_STRING'], $querystring);
        //set values
        $request->page = $page;
        $request->controller = $page.'Controller';
        $request->action = $actionPrefix.$this->filterAction($action);
        $request->paramvalue = $paramvalue;
        $request->querystring = $querystring;
        $request->urlparams = $urlparts;
        $request->language = $language;
        $request->view = $page.'/'.strtolower($request->action);
    }

    public function checkPrefixes(&$urlparts, &$actionPrefix)
    {
        global $prefixes;
        foreach ($prefixes as $prefix) {
            if (isset($urlparts[0]) && $urlparts[0] === $prefix) {
                array_shift($urlparts);
                $actionPrefix = $prefix.'_';
                break;
            }
        }
    }

    public function filterAction($action)
    {
        $values = explode('-', $action);
        $action = '';
        foreach ($values as $value) {
            $action .= ucfirst($value);
        }

        return $action;
    }

    public function checkCustomRoutes(&$page, &$action, &$paramvalue, &$language, $request)
    {
        global $routes;
        global $languages;
        if (isset($routes) && sizeof($routes) > 0) {
            foreach ($routes as $entry) {
                if (empty($entry[0]) || empty($entry[1])) {
                    throw new Exception('Error in routing config. Please provide a valid controller and url to match.');
                }
                $pattern = '/^'.str_replace('/', '\/', $entry[1]).'$/';
                if (preg_match($pattern, $request->uri, $params)) {
                    $page = ucfirst($entry[0]);
                    if (!empty($entry[2])) {
                        $action = strtolower($entry[2]);
                    }
                    if (!empty($entry[3])) {
                        if (in_array(strtolower($entry[3]), $languages)) {
                            $language = $entry[3];
                        }
                    }
                    break;
                }
            }
        }
    }

    public function getLanguage(&$urlparts)
    {
        global $languages;
        if (isset($urlparts[0]) && in_array(strtolower($urlparts[0]), $languages)) {
            $language = array_shift($urlparts);

            return $language;
        }

        return MAINLANGUAGE;
    }

    public function parsePageName($urlparts)
    {
        $page = (!empty($urlparts[0]) ? ucfirst($urlparts[0]) : 'Home');
        $pageStr = str_replace('_', ' ', str_replace('-', ' ', $page));
        $pageParts = explode(' ', $pageStr);
        $page = '';
        foreach ($pageParts as $part) {
            $page .= ucfirst($part);
        }

        return $page;
    }
}
