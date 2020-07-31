<?php

namespace Differ\Parsers\YamlParser;

use Symfony\Component\Yaml\Yaml;

function toAsoc($yaml)
{
    return (array) Yaml::parse($yaml, Yaml::PARSE_OBJECT_FOR_MAP);
}
