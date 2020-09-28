<?php

namespace Differ\Parsers\YamlParser;

use Symfony\Component\Yaml\Yaml;

function parseYaml($yaml)
{
    return Yaml::parse($yaml);
}
