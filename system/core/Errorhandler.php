<?php

namespace System\Core;

class Errorhandler
{
    public static function handleException($exception = null)
    {
        switch (get_class($exception)) {
            case "System\Core\Exceptions\NotFoundException":
                header('HTTP/1.1 404 Not Found');
                if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                    echo file_get_contents(APP_ROOT.'/app/errorpages/404.php');
                } else {
                    self::render(file_get_contents(APP_ROOT.'/app/errorpages/404.php'));
                }
                break;
            default:
                header('HTTP/1.1 500 Internal Server Error');
                if (PRODUCTIONMODE) {
                    //logging
                    $logger = new \System\Utils\Logger(EXCEPTION_LOGFILE);
                    $logger->logException($exception);
                    //send mail
                    $mail = new \System\Utils\Emailer(MAIN_EMAIL, MAIN_EMAIL, 'Exception on '.MAIN_NAME, $exception);
                    $mail->sendHTMLMail();
                    if (self::isAjax()) {
                        exit(header('Location: '.WEB_ROOT.'/ajaxerror'));
                    } else {
                        exit(header('Location: '.WEB_ROOT.'/error'));
                    }
                } else {
                    //parse content
                    $exceptionContent = '<strong>message:</strong> '.$exception->getMessage().'<br />';
                    $exceptionContent .= '<strong>code:</strong> '.$exception->getCode().'<br />';
                    $exceptionContent .= '<strong>line:</strong> '.$exception->getLine().'<br />';
                    $exceptionContent .= '<strong>trace:</strong> '.$exception->getTraceAsString().'<br />';
                    //show content
                    if (strlen(ob_get_contents()) > 0) {
                        ob_end_clean();
                    }
                    if (self::isAjax()) {
                        echo '<div class="app-error"><h3>Exception</h3>'.$exceptionContent.'</div>';
                    } else {
                        self::render(print_r('<div style="border:3px solid red;padding:5px;"><h3>Exception</h3>'.$exceptionContent.'</div>', true));
                    }
                }
                break;
        }
    }

    public static function handleError($errno, $errstr, $errfile, $errline)
    {
        $errorTypes = [2 => 'WARNING', 8 => 'NOTICE', 256 => 'FATAL_ERROR', 512 => 'WARNING', 1024 => 'NOTICE'];
        $errorType = 'UNKNOWN ERROR';
        if (array_key_exists($errno, $errorTypes)) {
            $errorType = $errorTypes[$errno];
        }
        if (!(error_reporting() & $errno)) {
            return;
        } else {
            self::processError($errno.' ('.$errorType.')', $errstr, $errfile, $errline, $errno);
        }

        return true;
    }

    public static function handleFatal()
    {
        $error = error_get_last();
        if ($error && isset($error['type']) && $error['type'] == 1) {
            self::processError('FATAL ERROR', $error['message'], $error['file'], $error['line'], E_CORE_ERROR);
        }
    }

    private static function processError($errorTitle, $errorMessage, $errorFile, $errorLine, $errno)
    {
        $errorContent = '<strong>'.$errorTitle.'</strong> '.'<br />';
        $errorContent .= '<strong>Message:</strong> '.$errorMessage.'<br />';
        $errorContent .= '<strong>File:</strong> '.$errorFile.'<br />';
        $errorContent .= '<strong>Line:</strong> '.$errorLine.'<br /><br />';

        if (PRODUCTIONMODE) {
            //log error
            $logger = new \System\Utils\Logger(ERROR_LOGFILE);
            $logger->logError($errorContent);
            //send mail
            $mail = new \System\Utils\Emailer(MAIN_EMAIL, MAIN_EMAIL, 'Error on '.MAIN_NAME, $errorContent);
            $mail->sendHTMLMail();
            //fatal error - script should halt
            if ($errno == 256) {
                header('HTTP/1.1 500 Internal Server Error');
                if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                    exit(header('Location: '.WEB_ROOT.'/ajaxerror'));
                } else {
                    exit(header('Location: '.WEB_ROOT.'/error'));
                }
            }
        } else {
            if (self::isAjax()) {
                echo '<div class="app-error"><h3>Error</h3>'.$errorContent.'</div>';
            } else {
                if (strlen(ob_get_contents()) > 0) {
                    ob_end_clean();
                }
                self::render('<div style="border:3px solid red;padding:5px;"><h3>Error</h3>'.$errorContent.'</div>');
            }
        }
    }

    private static function render($content)
    {
        $layout = new \System\Core\Layout();
        $layout->content = $content;
        die($layout->render());
    }

    private static function isAjax()
    {
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            return true;
        }

        return false;
    }
}

set_exception_handler("System\Core\Errorhandler::handleException");
set_error_handler("System\Core\Errorhandler::handleError");
register_shutdown_function("System\Core\Errorhandler::handleFatal");
