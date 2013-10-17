<?php
App::uses('ShortUrl', 'Model');

class ShortUrlTest extends CakeTestCase {

	public function setUp() {
		$this->ShortUrl = new ShortUrl();
	}

	public function testGenerateCode() {
		
		// test that a code is generated when no code provided
		$code = $this->ShortUrl->generateCode();
		$this->assertTrue(!empty($code));
		
		// test that a code is generated when a code is provided
		$code = $this->ShortUrl->generateCode('testcode');
		$this->assertTrue(!empty($code));
	}
	
	function testAlphaNumericDashUnderscore() {
		
		// test that non alphanumeric codes fail
		$test_data['code'] = '#&*$^%#*^&%';
		$result = $this->ShortUrl->alphaNumericDashUnderscore($test_data);
		$this->assertFalse(!empty($result));
		
		// test that alphanumeric code succeeds
		$test_data['code'] = 'valid121';
		$result = $this->ShortUrl->alphaNumericDashUnderscore($test_data);
		$this->assertTrue(!empty($result));
	}
	
	function testReservedCodes() {
		
		// test that reserved code not allowed
		$test_data['code'] = 'api';
		$result = $this->ShortUrl->reservedCodes($test_data);
		$this->assertFalse(!empty($result));
		
		// test that non-reserved code passes
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