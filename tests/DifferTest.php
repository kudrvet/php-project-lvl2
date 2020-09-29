<?php

namespace Differ\Differ\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class DifferTest extends TestCase
{

    public $dataFormats = ['json', 'yaml'];
    public $formatterNames = ['plain', 'pretty', 'json'];
    /**
     * @dataProvider additionProvider
     */

    public function testGenDiff($dataFormat, $formatterName)
    {
        [$pathToFileBefore,$pathToFileAfter,$pathToResultFile] = $this->getFixturesPaths($dataFormat, $formatterName);
        $actual = genDiff($pathToFileBefore, $pathToFileAfter, $formatterName);
        $expected = file_get_contents($pathToResultFile);
        $this->assertEquals($expected, $actual);
    }

    public function getFixturesPaths($dataFormat, $formatterName)
    {
        $pathToFixtureFolder = './tests/fixtures';
        return ["{$pathToFixtureFolder}/before.{$dataFormat}","{$pathToFixtureFolder}/after.{$dataFormat}",
                    "{$pathToFixtureFolder}/{$formatterName}FormatterResult"];
    }

    public function additionProvider()
    {

        $dataFormatsCollection = collect($this->dataFormats);
        $formattersCollection = collect($this->formatterNames);

        $formatsWithFormattersPairs = $dataFormatsCollection->flatMap(function($dataFormat) use($formattersCollection) {
            return $formattersCollection->Map(function ($item) use ($dataFormat) {
                return [$dataFormat,$item]; });
            ;
        });

        return ($formatsWithFormattersPairs->all()) ;
        }
}
