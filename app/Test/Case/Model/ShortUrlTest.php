<?php
App::uses('ShortUrl', 'Model');

class ShortUrlTest extends CakeTestCase {

	public function setUp() {
		$this->ShortUrl = new ShortUrl();
	}

	public function testGenerateCode() {
		$code = $this->ShortUrl->generateCode();
		$this->assertTrue(!empty($code));
		
		$code = $this->ShortUrl->generateCode('testcode');
		$this->assertTrue(!empty($code));
	}
	
	function testAlphaNumericDashUnderscore() {
		$test_data['code'] = '#&*$^%#*^&%';
		
		$result = $this->ShortUrl->alphaNumericDashUnderscore($test_data);
		$this->assertFalse(!empty($result));
		
		$test_data['code'] = 'valid121';
		$result = $this->ShortUrl->alphaNumericDashUnderscore($test_data);
		$this->assertTrue(!empty($result));
	}
	
	function testReservedCodes() {
		
		$test_data['code'] = 'api';
		$result = $this->ShortUrl->reservedCodes($test_data);
		$this->assertFalse(!empty($result));
		
		$test_data['code'] = 'chris';
		$result = $this->ShortUrl->reservedCodes($test_data);
		$this->assertTrue(!empty($result));
	}
	
	function testSaveValidData() {
		
		$test_data = array(
			'url'	=> 'asdfasdfasdf',
			'code'	=> 'chris'
		);
		
		// should fail url validation
		if($this->ShortUrl->save($test_data)) {
			$this->assertTrue(false);
		} else {
			$this->assertTrue(true);
		}
		
		$test_data = array(
			'url'	=> 'http://lovegoodbeer.com',
			'code'	=> '12345678901'
		);
		
		// should fail code validation
		if($this->ShortUrl->save($test_data)) {
			$this->assertTrue(false);
		} else {
			$this->assertTrue(true);
		}
		
		$test_data = array(
			'url'	=> 'http://lovegoodbeer.com',
			'code'	=> 'lgb'
		);
		
		// should pass validation
		if($this->ShortUrl->save($test_data)) {
			$this->assertTrue(true);
		} else {
			$this->assertTrue(false);
		}
	}
}