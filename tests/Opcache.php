<?php

class OpcacheTest extends PHPUnit_Framework_TestCase {
	public function testOpcache() {
		$loaded = extension_loaded("Zend OPcache");
		$this->assertTrue($loaded);
	}
}