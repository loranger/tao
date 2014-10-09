<?php

function €($expression)
{
    return tao($expression);
}

function iterate($item, $key, $pack)
{
    call_user_func_array(array($item, $pack["method"]), $pack["args"]);
}

