<?php

function getMethodName()
{
    /** Get Method Name as Page Title */

    $router = service('router');
    $method = $router->methodName();
    return ucwords(implode(' ', preg_split('/(?=[A-Z])/', $method)));
}
