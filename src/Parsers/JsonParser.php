<?php

namespace Differ\Parsers\JsonParser;

function parseJsonToData($json)
{
    return json_decode($json, true);
}
