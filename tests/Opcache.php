<?php

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
		$this->assertContains("status", $data);
	}
}