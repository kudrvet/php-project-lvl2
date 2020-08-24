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

    public function testGenDiffWithJson()
    {

        $diff = genDiff('./tests/fixtures/jsonBefore2.json','./tests/fixtures/jsonAfter2.json');
        $this->assertEquals(file_get_contents('./tests/fixtures/trueResult2'),  $diff);

    }

    public function testGenDiffWithYaml()
    {
        $diff = genDiff('./tests/fixtures/ymlBefore2.yaml','./tests/fixtures/ymlAfter2.yaml');
        $this->assertEquals(file_get_contents('./tests/fixtures/trueResult2'),  $diff);
    }

      public function testGenDiffWithRecursiveJson ()
      {
          $diff = genDiff('./tests/fixtures/recursiveJsonBefore.json',
              './tests/fixtures/recursiveJsonAfter.json');
          $expected = file_get_contents('./tests/fixtures/trueResultRecursive');
          $this->assertEquals($expected,$diff);
      }

    public function testGenDiffWithRecursiveYml ()
    {
        $diff = genDiff('./tests/fixtures/recursiveYmlBefore.yaml',
            './tests/fixtures/recursiveYmlAfter.yaml');
        $expected = file_get_contents('./tests/fixtures/trueResultRecursiveYml');
        $this->assertEquals($expected,$diff);
    }

}





