<?php

class BuilderMock extends League\Monga\Query\Builder {}

class QueryBuilderTests extends PHPUnit_Framework_TestCase
{
	protected $builder;

	public function setUp()
	{
		$this->builder = new BuilderMock();
	}

	public function getProperty($property)
	{
		$reflection = new ReflectionObject($this->builder);
		$property = $property = $reflection->getProperty($property);
		$property->setAccessible(true);
		return $property->getValue($this->builder);
	}

	public function testSafe()
	{
		$this->builder->safe();
		$this->assertEquals(1, $this->getProperty('safe'));
		$this->builder->safe(0);
		$this->assertEquals(0, $this->getProperty('safe'));
	}

	public function testFsync()
	{
		$this->builder->fsync();
		$this->assertTrue($this->getProperty('fsync'));
		$this->builder->fsync(false);
		$this->assertFalse($this->getProperty('fsync'));
	}

	public function testTimeout()
	{
		$this->assertNull($this->getProperty('timeout'));
		$this->builder->timeout(100);
		$this->assertEquals(100, $this->getProperty('timeout'));
	}

	public function testSetOption()
	{
		$this->builder->setOptions(array(
			'fsync' => true,
			'safe' => 1,
		));

		$this->assertTrue($this->getProperty('fsync'));
		$this->assertEquals(1, $this->getProperty('safe'));

		$this->builder->setOptions(array(
			'fsync' => false,
			'safe' => 0,
		));

		$this->assertFalse($this->getProperty('fsync'));
		$this->assertEquals(0, $this->getProperty('safe'));
	}

	public function testGetOptions()
	{
		$result = $this->builder->getOptions();

		$this->assertEquals(array(
			'w' => 0,
			'fsync' => false,
			'timeout' => MongoCursor::$timeout
		), $result);

		$result = $this->builder->timeout(100)->getOptions();


		$this->assertEquals(array(
			'w' => 0,
			'fsync' => false,
			'timeout' => 100
		), $result);
	}
}