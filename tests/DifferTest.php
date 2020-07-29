<?php

namespace Differ\Differ\Tests;

//$autoloadPath1 = __DIR__ . '/../../../autoload.php';
//$autoloadPath2 = __DIR__ . '/../vendor/autoload.php';
//if (file_exists($autoloadPath1)) {
//    require_once $autoloadPath1;
//} else {
//    require_once $autoloadPath2;
//}

use PHPUnit\Framework\TestCase;
use function Differ\Differ\genDiff;

class DifferTest extends TestCase
{
    public function testGenDiffWithEqualsStrings()
    {
        $diff = $diff = genDiff('./tests/fixtures/before1.json','./tests/fixtures/after1.json');
        $this->assertEquals(file_get_contents('./tests/fixtures/trueResult1'),  $diff);

    }
    public function testGenDiff()
    {
        $diff = genDiff('./tests/fixtures/before.json','./tests/fixtures/after.json');
        $this->assertEquals(file_get_contents('./tests/fixtures/trueResult'),  $diff);
    }
}

