<?php
/**
 * @return bool
 */
function is_get()
{
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        return true;
    }

    return false;
}

/**
 * @return bool
 */
function is_post()
{
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        return true;
    }

    return false;
}

/**
 * @return bool
 */
function is_ajax()
{
    if (isset($_SERVER["HTTP_X_REQUESTED_WITH"]) &&
        !empty($_SERVER["HTTP_X_REQUESTED_WITH"]) &&
        strtolower($_SERVER["HTTP_X_REQUESTED_WITH"]) == "xmlhttprequest") {
        return true;
    } else {
        return false;
    }
}

/**
 * @param $var
 * @return string
 */
function clear_str($var)
{
    return trim(strip_tags($var));
}

/**
 * @param $var
 * @return int
 */
function clear_int($var)
{
    return (int)$var;
}

/**
 * @param $key
 * @param $domains
 * @return false|int|string
 */
function check_domain($key, $domains)
{
    return array_search($key, $domains);
}