<?php

namespace Differ\Parsers\JsonParser;

function parseJson($json)
{
    return json_decode($json, true);
}
