<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class DifferTest extends TestCase
{
    private string $path = __DIR__ . "/fixtures/";
    private array $expected = [];

    private function getFilePath($name): string
    {
        return $this->path . $name;
    }

    protected function setUp(): void
    {
        $data = file_get_contents($this->getFilePath('expected.txt'));
        $this->expected = explode("\n\n\n", trim($data));
    }

    public function testFlatJSONfiles()
    {
        $pathToFile1 = $this->getFilePath("file1-flat.json");
        $pathToFile2 = $this->getFilePath("file2-flat.json");

        $expected = trim($this->expected[0]);
        $actual = genDiff($pathToFile1, $pathToFile2);

        $this->assertEquals($expected, $actual);
    }

    public function testFlatYMLfiles()
    {
        $pathToFile1 = $this->getFilePath("file1-flat.yml");
        $pathToFile2 = $this->getFilePath("file2-flat.yml");

        $expected = trim($this->expected[0]);
        $actual = genDiff($pathToFile1, $pathToFile2);

        $this->assertEquals($expected, $actual);
    }

    //Nested Stylish
    public function testNestedJSONfilesStylishFormat()
    {
        $pathToFile1 = $this->getFilePath("file1-nested.json");
        $pathToFile2 = $this->getFilePath("file2-nested.json");

        $expected = trim($this->expected[1]);
        $actual = genDiff($pathToFile1, $pathToFile2);

        $this->assertEquals($expected, $actual);
    }

    public function testNestedYMLfilesStylishFormat()
    {
        $pathToFile1 = $this->getFilePath("file1-nested.yml");
        $pathToFile2 = $this->getFilePath("file2-nested.yml");

        $expected = trim($this->expected[1]);
        $actual = genDiff($pathToFile1, $pathToFile2);

        $this->assertEquals($expected, $actual);
    }

    //Nested Flat
    public function testNestedJSONfilesFlatFormat()
    {
        $pathToFile1 = $this->getFilePath("file1-nested.json");
        $pathToFile2 = $this->getFilePath("file2-nested.json");

        $expected = trim($this->expected[2]);
        $actual = genDiff($pathToFile1, $pathToFile2, 'plain');

        $this->assertEquals($expected, $actual);
    }

    public function testNestedYMLfilesFlatFormat()
    {
        $pathToFile1 = $this->getFilePath("file1-nested.yml");
        $pathToFile2 = $this->getFilePath("file2-nested.yml");

        $expected = trim($this->expected[2]);
        $actual = genDiff($pathToFile1, $pathToFile2, 'plain');

        $this->assertEquals($expected, $actual);
    }

    //Nested JSON
    public function testNestedJSONfilesToJSONFormat()
    {
        $pathToFile1 = $this->getFilePath("file1-nested.json");
        $pathToFile2 = $this->getFilePath("file2-nested.json");

        $expected = trim($this->expected[3]);
        $actual = genDiff($pathToFile1, $pathToFile2, 'json');

        $this->assertEquals($expected, $actual);
    }

    public function testNestedYMLfilesToJSONFormat()
    {
        $pathToFile1 = $this->getFilePath("file1-nested.yml");
        $pathToFile2 = $this->getFilePath("file2-nested.yml");

        $expected = trim($this->expected[3]);
        $actual = genDiff($pathToFile1, $pathToFile2, 'json');

        $this->assertEquals($expected, $actual);
    }
}
