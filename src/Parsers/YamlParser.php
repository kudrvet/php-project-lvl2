<?php

namespace Differ\Parsers\YamlParser;

use Symfony\Component\Yaml\Yaml;

function parseYamlToData($yaml)
{
    return Yaml::parse($yaml);
}
