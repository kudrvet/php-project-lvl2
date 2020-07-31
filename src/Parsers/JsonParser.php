<?php

namespace Differ\Parsers\JsonParser;

function toAsoc($json)
{
    return json_decode($json, true);
}
