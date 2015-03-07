<?php

require 'vendor/autoload.php';

class MockClient {
	public function gauge($k, $v) {
		return true;
	}
}

class OpcacheTest extends PHPUnit_Framework_TestCase {
	public function testOpcache() {
		$loaded = extension_loaded("Zend OPcache");
		$this->assertTrue($loaded);
	}

	public function testCanInitialize() {
		$opcache = new Opcache\Status;
		$this->assertInstanceOf('Opcache\Status', $opcache);
	}

	public function testInitializeStatsdOptions() {

	}

	public function testInitializeStatsdBlock() {

	}

	public function testStatusNoScriptsNoStatsd() {
		$opcache = new Opcache\Status;
		$result = $opcache->status();

		$data = json_decode($result);

		$this->assertEquals(JSON_ERROR_NONE, json_last_error());

		$this->assertInstanceOf('stdClass', $data);
		$this->assertObjectHasAttribute("status", $data);

		// Check for all top level keys
		$this->assertObjectHasAttribute("opcache_enabled",        $data->status);
		$this->assertObjectHasAttribute("cache_full",             $data->status);
		$this->assertObjectHasAttribute("restart_pending",        $data->status);
		$this->assertObjectHasAttribute("restart_in_progress",    $data->status);
		$this->assertObjectHasAttribute("memory_usage",           $data->status);
		$this->assertObjectHasAttribute("opcache_statistics",     $data->status);

		$this->assertFalse(isset($data->scripts));
	}

	public function testStatusScriptsNoStatsd() {
		$opcache = new Opcache\Status;
		$result = $opcache->status(true);

		$data = json_decode($result);

		$this->assertEquals(JSON_ERROR_NONE, json_last_error());

		$this->assertInstanceOf('stdClass', $data);
		$this->assertObjectHasAttribute("status", $data);

		// Check for all top level keys
		$this->assertObjectHasAttribute("opcache_enabled",        $data->status);
		$this->assertObjectHasAttribute("cache_full",             $data->status);
		$this->assertObjectHasAttribute("restart_pending",        $data->status);
		$this->assertObjectHasAttribute("restart_in_progress",    $data->status);
		$this->assertObjectHasAttribute("memory_usage",           $data->status);
		$this->assertObjectHasAttribute("opcache_statistics",     $data->status);

		$this->assertTrue(isset($data->scripts));

		$this->assertArrayHasKey(0, $data->scripts);

		// Check for all script level keys
		$this->assertObjectHasAttribute("full_path",           $data->scripts[0]);
		$this->assertObjectHasAttribute("hits",                $data->scripts[0]);
		$this->assertObjectHasAttribute("memory_consumption",  $data->scripts[0]);
		$this->assertObjectHasAttribute("last_used",           $data->scripts[0]);
		$this->assertObjectHasAttribute("last_used_timestamp", $data->scripts[0]);
		$this->assertObjectHasAttribute("timestamp",           $data->scripts[0]);
	}

	public function testScriptsSortedByMemory() {
		$opcache = new Opcache\Status;
		$result = $opcache->status(true);

		$data = json_decode($result);

		$this->assertEquals(JSON_ERROR_NONE, json_last_error());
		$this->assertTrue(isset($data->scripts));

		$last_memory = 99999999999;

		foreach($data->scripts as $script) {
			$this->assertGreaterThanOrEqual($script->memory_consumption, $last_memory);
			$last_memory = $script->memory_consumption;
		}

	}

	private function getStatsdMock() {
		$statsd = $this->getMockBuilder('MockClient')->getMock();
		$statsd->expects($this->atLeastOnce())->method('gauge')->will($this->returnValue(true));

		return $statsd;
	}

	public function testStatsdBlock() {
		$statsd = $this->getStatsdMock();

		$opcache = new Opcache\Status(function() use ($statsd) {
			return $statsd;
		});

		$result = $opcache->status();
		$data = json_decode($result);
	}

	public function testStatsdBlockWithScripts() {
		$statsd = $this->getStatsdMock();

		$opcache = new Opcache\Status(function() use ($statsd) {
			return $statsd;
		});

		$result = $opcache->status(true);
		$data = json_decode($result);
	}
}
