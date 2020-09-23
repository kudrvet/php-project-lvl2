<?php

namespace Differ\Differ\Tests;

$autoloadPath1 = __DIR__ . '/../../../autoload.php';
$autoloadPath2 = __DIR__ . '/../vendor/autoload.php';
if (file_exists($autoloadPath1)) {
    require_once $autoloadPath1;
} else {
    require_once $autoloadPath2;
}

use mysql_xdevapi\Exception;
use PHPUnit\Framework\TestCase;
use function Differ\Differ\genDiff;

class DifferTest extends TestCase
{
    /**
     * @dataProvider additionProvider
     */

    public function testGenDiff($dataFormat,$formatter)
    {
        switch ($dataFormat){
            case 'json':
                $pathToFileBefore  = './tests/fixtures/before.json';
                $pathToFileAfter = './tests/fixtures/after.json';
                break;
            case 'yaml':
                $pathToFileBefore  = './tests/fixtures/before.yaml';
                $pathToFileAfter = './tests/fixtures/after.yaml';
                break;
            default:
                throw new \Exception ("Format {$dataFormat} is not supported! ");
        }

        switch ($formatter) {
            case 'pretty':
                $expected = file_get_contents('./tests/fixtures/prettyFormatterResult');
                break;
            case 'plain':
                $expected = file_get_contents('./tests/fixtures/plainFormatterResult');
                break;
            case 'json':
                $expected = file_get_contents('./tests/fixtures/jsonFormatterResult.json');
                break;
            default:
                throw new \Exception ("Formatter {$formatter} is not supported! ");
        }

        $actual = genDiff($pathToFileBefore, $pathToFileAfter,$formatter);
        $this->assertEquals($expected, $actual);
    }

    public function additionProvider() {

        $dataFormats  = [
            'json',
            'yaml',
            'json',
            'yaml',
            'json',
            'yaml'
        ];
        $formatters = [
            'pretty',
            'pretty',
            'plain',
            'plain',
            'json',
            'json'
        ];

        $res =[];
        foreach ($dataFormats as $key => $dataFormat) {
            $res[] = [$dataFormat, $formatters[$key]];
        }

        return $res;
    }

}





