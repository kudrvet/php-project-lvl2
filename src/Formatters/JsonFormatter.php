<?php

namespace Differ\Formatters\JsonFormatter;

function toJsonFormat($diffTree)
{
    return json_encode($diffTree, JSON_PRETTY_PRINT, JSON_UNESCAPED_UNICODE);
}
