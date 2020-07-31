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
    public function testGenDiffWithEqualsJsonStrings()
    {
        $diff = $diff = genDiff('./tests/fixtures/jsonBefore1.json','./tests/fixtures/jsonAfter1.json');
        $this->assertEquals(file_get_contents('./tests/fixtures/trueResult1'),  $diff);

    }
    public function testGenDiffWithJson()
    {
        $diff = genDiff('./tests/fixtures/jsonBefore2.json','./tests/fixtures/jsonAfter2.json');
        $this->assertEquals(file_get_contents('./tests/fixtures/trueResult2'),  $diff);
    }

    public function testGenDiffWithEqualsYamlStrings()
    {
        $diff = $diff = genDiff('./tests/fixtures/ymlBefore1.yaml', './tests/fixtures/ymlAfter1.yaml');
        $this->assertEquals(file_get_contents('./tests/fixtures/trueResult1'), $diff);

    }
    public function testGenDiffWithYaml()
    {
        $diff = genDiff('./tests/fixtures/ymlBefore2.yaml','./tests/fixtures/ymlAfter2.yaml');
        $this->assertEquals(file_get_contents('./tests/fixtures/trueResult2'),  $diff);
    }
}

