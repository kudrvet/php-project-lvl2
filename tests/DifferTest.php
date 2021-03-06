<?php

namespace Differ\Differ\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;



class DifferTest extends TestCase
{
    public $formatsAndFormattersPairs = [
        ['json','pretty'],
        ['yaml', 'plain'],
        ['yaml', 'json']
    ];


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
        return realpath(implode(DIRECTORY_SEPARATOR,[__DIR__,'fixtures',$fileName]));
    }

    public function additionProvider()
    {
        return $this->formatsAndFormattersPairs;
    }
}
