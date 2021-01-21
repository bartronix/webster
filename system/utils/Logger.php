<?php

namespace System\Utils;

class Logger
{
    private $logfile = '';

    public function __construct($logfile)
    {
        $this->logfile = $logfile;
    }

    public function logException($exception)
    {
        $message = $exception->getMessage();
        $code = $exception->getCode();
        $file = $exception->getFile();
        $line = $exception->getLine();
        $trace = $exception->getTraceAsString();
        $date = $this->getDate();
        $logMessage = "<div>
					   <h3>Exception information:</h3>
					   <strong>Date:</strong> {$date}
					   <br>
					   <strong>Message:</strong> {$message}
					   <br>
					   <strong>Code:</strong> {$code}
					   <br>
					   <strong>File:</strong> {$file}
					   <br>
					   <strong>Line:</strong> {$line}
					   <h3>Stack trace:</h3>
					   <pre>{$trace}</pre>
					   </div>";
        $this->writeToFile(trim($logMessage));
    }

    public function logError($errorMessage)
    {
        $date = $this->getDate();
        $logMessage = "<div>
					   <h3>Error information:</h3>
					   <strong>Date:</strong> {$date}
					   <strong>Message:</strong>".$errorMessage.'</div>';
        $this->writeToFile(trim($logMessage));
    }

    public function logInfo($message)
    {
        $this->writeToFile($message);
    }

    private function getDate()
    {
        return date('Y-m-d H:i:s');
    }

    private function writeToFile($message)
    {
        if (!file_exists(dirname($this->logfile))) {
            mkdir(dirname($this->logfile), 0644, true);
        }
        chmod(dirname($this->logfile), 0777);
        if (!is_file($this->logfile)) {
            file_put_contents($this->logfile, '');
        }
        $content = file_get_contents($this->logfile);
        file_put_contents($this->logfile, $message.$content);
        chmod(dirname($this->logfile), 0644);
    }
}
