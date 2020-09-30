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
        $nameOfFileBefore = "before.{$dataFormat}";
        $nameOfFileAfter = "after.{$dataFormat}";
        $nameOfResultFile = "{$formatterName}FormatterResult";

        $actual = genDiff($this->getFixturePath($nameOfFileBefore), $this->getFixturePath($nameOfFileAfter), $formatterName);
        $expected = file_get_contents($this->getFixturePath($nameOfResultFile));

        $this->assertEquals($expected, $actual);
    }

    public function getFixturePath($fileName)
    {
        $pathToFixtureFolder = './tests/fixtures';

        return "{$pathToFixtureFolder}/$fileName";
    }

    public function additionProvider()
    {
        return [
            ['json','pretty'],
            ['yaml', 'plain'],
            ['yaml', 'json']
        ];
    }
}
