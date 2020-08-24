<?php

namespace Differ\Parsers\YamlParser;

use Symfony\Component\Yaml\Yaml;

function toAsoc($yaml)
{
//    return (array) Yaml::parse($yaml, Yaml::PARSE_OBJECT_FOR_MAP);
//    $arrayWithStdInside = (array) Yaml::parse($yaml, Yaml::PARSE_OBJECT_FOR_MAP);
    return Yaml::parse($yaml);
}

//function iter($arrayWithStdInside)
//{
//    $array = array_map(function($elem) {
//        if(is_object($elem)) {
////            return iter($elem(array_values($elem)));
//            $arrayElem = (array) $elem;
//            iter($arrayElem);
//        } elseif (is_array($elem)) {
//            return $elem;
//        }
//
//    },$arrayWithStdInside);
//
//    return $array;
//}
