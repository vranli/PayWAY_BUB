<?php

function load_priv_key($path)
{
    $handle = fopen($path, "r");
    $key = "";
    while (($line = fgets($handle)) !== false) {
        $key .= $line;
    }
    return $key;
}

function load_pub_key($path)
{
    $handle = fopen($path, "r");
    $key = "";
    while (($line = fgets($handle)) !== false) {
        $key .= $line;
    }
    return $key;
}

/**
 * remove more spaces
 * @param   String  $text   text ro format
 * @return  String  return formated text
 * 
 */
function delete_more_spaces($text)
{
    return preg_replace('/\s+/', ' ', $text);
}
