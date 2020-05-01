<?php

function is_dir_empty($dir)
{
    $handle = opendir($dir);
    while (false !== ($entry = readdir($handle))) {
        if ($entry != "." && $entry != "..") {
            closedir($handle);
            return FALSE;
        }
    }
    closedir($handle);
    return TRUE;
}

function isWin()
{
    return !!substr(php_uname(), 0, 7) == "Windows";
}

function rrmdir($path)
{
    if (isWin()) {
        exec(sprintf("rd /s /q %s", escapeshellarg($path)));
    } else {
        exec(sprintf("rm -rf %s", escapeshellarg($path)));
    }
}


function execInBackground($cmd)
{
    if (isWin()) {
        pclose(popen("start /B " . $cmd, "r"));
    } else {
        exec($cmd . " > /dev/null &");
    }
}


function eko($str = '')
{
    echo $str . PHP_EOL;
}

function ds()
{
    //  return '/';
    return DIRECTORY_SEPARATOR;
}


function unparse_url($parsed_url)
{
    $scheme = isset($parsed_url['scheme']) ? $parsed_url['scheme'] . '://' : '';
    $host = isset($parsed_url['host']) ? $parsed_url['host'] : '';
    $port = isset($parsed_url['port']) ? ':' . $parsed_url['port'] : '';
    $user = isset($parsed_url['user']) ? $parsed_url['user'] : '';
    $pass = isset($parsed_url['pass']) ? ':' . urlencode($parsed_url['pass']) : '';
    $pass = ($user || $pass) ? "$pass@" : '';
    $path = isset($parsed_url['path']) ? $parsed_url['path'] : '';
    $query = isset($parsed_url['query']) ? '?' . $parsed_url['query'] : '';
    $fragment = isset($parsed_url['fragment']) ? '#' . $parsed_url['fragment'] : '';
    return "$scheme$user$pass$host$port$path$query$fragment";
}
