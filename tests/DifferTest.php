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
    public function testGenDiffWithFlatJsonAndPrettyFormatter()
    {
        $diff = genDiff('./tests/fixtures/flatJsonBefore.json','./tests/fixtures/flatJsonAfter.json');
        $this->assertEquals(file_get_contents('./tests/fixtures/flatInputWithPrettyFormatterResult'),  $diff);
    }

    public function testGenDiffWithFlatYamlAndPrettyFormatter()
    {
        $diff = genDiff('./tests/fixtures/flatYmlBefore.yaml','./tests/fixtures/flatYmlAfter.yaml');
        $this->assertEquals(file_get_contents('./tests/fixtures/flatInputWithPrettyFormatterResult'),  $diff);
    }

    public function testGenDiffWithFlatJsonAndPlainFormatter()
    {
        $diff = genDiff('./tests/fixtures/flatJsonBefore.json','./tests/fixtures/flatJsonAfter.json','plain');
        $this->assertEquals(file_get_contents('./tests/fixtures/flatInputWithPlainFormatterResult'),  $diff);
    }

    public function testGenDiffWithFlatYamlAndPlainFormatter()
    {
        $diff = genDiff('./tests/fixtures/flatYmlBefore.yaml','./tests/fixtures/flatYmlAfter.yaml','plain');
        $this->assertEquals(file_get_contents('./tests/fixtures/flatInputWithPlainFormatterResult'),  $diff);
    }

    public function testGenDiffWithFlatJsonAndJsonFormatter()
    {
        $diff = genDiff('./tests/fixtures/flatJsonBefore.json','./tests/fixtures/flatJsonAfter.json','json');
        $this->assertEquals(file_get_contents('./tests/fixtures/flatInputWithJsonFormatterResult.json'),  $diff);
    }

    public function testGenDiffWithFlatYamlAndJsonFormatter()
    {
        $diff = genDiff('./tests/fixtures/flatYmlBefore.yaml','./tests/fixtures/flatYmlAfter.yaml','json');
        $this->assertEquals(file_get_contents('./tests/fixtures/flatInputWithJsonFormatterResult.json'),  $diff);
    }

    public function testGenDiffWithRecursiveJsonAndPrettyFormatter()
    {
        $diff = genDiff('./tests/fixtures/recursiveJsonBefore.json',
            './tests/fixtures/recursiveJsonAfter.json');
        $expected = file_get_contents('./tests/fixtures/recursiveInputWithPrettyFormatterResult');
        $this->assertEquals($expected, $diff);
    }

    public function testGenDiffWithRecursiveYmlAndPrettyFormatter()
    {
        $diff = genDiff('./tests/fixtures/recursiveYmlBefore.yaml',
            './tests/fixtures/recursiveYmlAfter.yaml');
        $expected = file_get_contents('./tests/fixtures/recursiveInputWithPrettyFormatterResult');
        $this->assertEquals($expected, $diff);
    }

    public function testGenDiffRecursiveJsonWithPlainFormatter()
    {
        $diff =  $diff = genDiff('./tests/fixtures/recursiveJsonBefore.json',
            './tests/fixtures/recursiveJsonAfter.json','plain');
        $expected = file_get_contents('./tests/fixtures/recursiveInputWithPlainFormatterResult');
        $this->assertEquals($expected, $diff);
    }

    public function testGenDiffRecursiveYmlWithPlainFormatter()
    {
        $diff =  $diff = genDiff('./tests/fixtures/recursiveYmlBefore.yaml',
            './tests/fixtures/recursiveYmlAfter.yaml','plain');
        $expected = file_get_contents('./tests/fixtures/recursiveInputWithPlainFormatterResult');
        $this->assertEquals($expected, $diff);
    }

    public function testGenDiffRecursiveJsonWithJsonFormatter()
    {
        $diff =  $diff = genDiff('./tests/fixtures/recursiveJsonBefore.json',
            './tests/fixtures/recursiveJsonAfter.json','json');
        $expected = file_get_contents('./tests/fixtures/recursiveInputWithJsonFormatterResult.json');
        $this->assertEquals($expected, $diff);
    }

    public function testGenDiffRecursiveYmlWithJsonFormatter()
    {
        $diff =  $diff = genDiff('./tests/fixtures/recursiveYmlBefore.yaml',
            './tests/fixtures/recursiveYmlAfter.yaml','json');
        $expected = file_get_contents('./tests/fixtures/recursiveInputWithJsonFormatterResult.json');
        $this->assertEquals($expected, $diff);
    }
}





