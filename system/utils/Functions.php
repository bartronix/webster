<?php

namespace System\Utils;

class Functions
{
    public static function validateEmail($email)
    {
        $email = htmlentities($email);
        $pattern = "^[A-Za-z0-9_\-\.]+\@[A-Za-z0-9_\-]+\.[A-Za-z0-9]+$";
        if (preg_match("/{$pattern}/", $email)) {
            return true;
        }

        return false;
    }

    public static function getCurrentDate()
    {
        date_default_timezone_set('Europe/Brussels');

        return date('Y-m-d H:i:s');
    }

    public static function formatDate($date)
    {
        return date('d-m-Y', strtotime($date));
    }

    public static function createSlug($str, $replace = [], $delimiter = '-')
    {
        setlocale(LC_ALL, 'en_US.UTF8');
        if (!empty($replace)) {
            $str = str_replace((array) $replace, ' ', $str);
        }
        $unwanted_array = ['' => 'S', '' => 's', '' => 'Z', '' => 'z', 'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'A', 'Ç' => 'C', 'È' => 'E', 'É' => 'E',
                                'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ø' => 'O', 'Ù' => 'U',
                                'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ý' => 'Y', 'Þ' => 'B', 'ß' => 'Ss', 'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'a', 'ç' => 'c',
                                'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i', 'ð' => 'o', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o',
                                'ö' => 'o', 'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ý' => 'y', 'ý' => 'y', 'þ' => 'b', 'ÿ' => 'y', ];
        $clean = strtr($str, $unwanted_array);
        $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
        $clean = strtolower(trim($clean, '-'));
        $clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);
        //strip extra slashes
        $clean = str_replace('/', '-', $clean);

        return $clean;
    }

    public static function removeDirectoryAndContents($dirname)
    {
        // Sanity check
        if (!file_exists($dirname)) {
            return false;
        }

        // Simple delete for a file
        if (is_file($dirname) || is_link($dirname)) {
            return unlink($dirname);
        }
        // Loop through the folder
        $dir = dir($dirname);
        while (false !== $entry = $dir->read()) {
            // Skip pointers
            if ($entry == '.' || $entry == '..') {
                continue;
            }
            // Recurse
            self::removeDirectoryAndContents($dirname.DIRECTORY_SEPARATOR.$entry);
        }

        // Clean up
        $dir->close();

        return rmdir($dirname);
    }

    public static function parseUrl()
    {
        $urlPieces = explode('?', str_replace(WEB_ROOT, '', $_SERVER['REQUEST_URI']));
        $urlParts = explode('/', trim($urlPieces[0], '/'));

        return $urlParts;
    }

    public static function shortenText($text, $length)
    {
        return $text;
    }
}
