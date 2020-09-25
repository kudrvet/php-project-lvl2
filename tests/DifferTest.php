<?php

namespace Differ\Differ\Tests;

$autoloadPath1 = __DIR__ . '/../../../autoload.php';
$autoloadPath2 = __DIR__ . '/../vendor/autoload.php';
if (file_exists($autoloadPath1)) {
    require_once $autoloadPath1;
} else {
    require_once $autoloadPath2;
}

use PHPUnit\Framework\TestCase;
use function Differ\Differ\genDiff;

class DifferTest extends TestCase
{

    public $dataFormats = ['json', 'yaml'];
    public $formatterNames = ['plain', 'pretty', 'json'];
    /**
     * @dataProvider additionProvider
     */

    public function testGenDiff($pathsToCompareFiles,$formatterName, $pathToResultFile)

    {
        [$pathToFileBefore, $pathToFileAfter] = $pathsToCompareFiles;
        $actual = genDiff($pathToFileBefore, $pathToFileAfter,$formatterName);
        $expected = file_get_contents($pathToResultFile);

        $this->assertEquals($expected, $actual);

    }

    public function additionProvider()

    {
        $res = [];
        foreach ($this->dataFormats as $dataFormat){
            foreach ($this->formatterNames as $formatterName) {
                $res[] = [$this->getPathToCompareFiles($dataFormat),$formatterName,$this->getPathToResultFile($formatterName)];
            }
        }
        return $res;
    }

    function getPathToCompareFiles($dataFormat)
       {
           switch ($dataFormat) {
               case 'json':
                   return ['./tests/fixtures/before.json','./tests/fixtures/after.json'];
               case 'yaml':
                   return ['./tests/fixtures/before.yaml','./tests/fixtures/after.yaml'];
               default:
                   throw new \Exception("Format {$dataFormat} is not supported");
           }
       }
   function getPathToResultFile($formatterName)
   {
       switch ($formatterName) {
           case 'pretty':
               return './tests/fixtures/prettyFormatterResult';
           case 'plain':
               return './tests/fixtures/plainFormatterResult';
           case 'json':
               return './tests/fixtures/jsonFormatterResult.json';
           default:
               throw new \Exception("Formatter {$formatterName} is not supported");
       }
   }
}







