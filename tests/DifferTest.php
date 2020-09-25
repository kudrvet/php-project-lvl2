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

    public function testGenDiff($pathToFileBefore,$pathToFileAfter,$formatterName, $pathToResultFile)
    {

        $actual = genDiff($pathToFileBefore, $pathToFileAfter,$formatterName);
        $expected = file_get_contents($pathToResultFile);

        $this->assertEquals($expected, $actual);
    }

    public function additionProvider()
    {
        $res = [];
        $pathToFixtureFolder = './tests/fixtures';
        foreach ($this->dataFormats as $dataFormat){
            foreach ($this->formatterNames as $formatterName) {
                $res[] = ["{$pathToFixtureFolder}/before.{$dataFormat}","{$pathToFixtureFolder}/after.{$dataFormat}",
                    $formatterName,"{$pathToFixtureFolder}/{$formatterName}FormatterResult"];
            }
        }
        return $res;
    }
}







