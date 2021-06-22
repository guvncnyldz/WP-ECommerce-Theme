<?php

function console_log_keys($object)
{
    console_log("Keys: ");
    foreach (array_keys((array)$object) as $keys)
    {
        console_log($keys);
    }
}

function console_log($output, $with_script_tags = true)
{
    $js_code = 'console.log(' . json_encode($output, JSON_HEX_TAG) .
        ');';
    if ($with_script_tags) {
        $js_code = '<script>' . $js_code . '</script>';
    }
    echo $js_code;
}

function alert($msg) {
    echo "<script type='text/javascript'>alert('$msg');</script>";
}

