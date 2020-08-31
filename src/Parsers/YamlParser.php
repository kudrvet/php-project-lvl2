<?php

namespace Differ\Parsers\YamlParser;

use Symfony\Component\Yaml\Yaml;

function toAsoc($yaml)
{
    return Yaml::parse($yaml);
}
