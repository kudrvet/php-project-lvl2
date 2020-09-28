<?php

namespace Differ\Parsers;

use Symfony\Component\Yaml\Yaml;

function parse($content, $format)
{
    switch ($format) {
        case "yaml":
            $data = Yaml::parse($content);
            break;
        case "json":
            $data = json_decode($content, true);
            break;
        default:
            throw new \Exception("Format {$format} is not supported! ");
    }

    return $data;
}
