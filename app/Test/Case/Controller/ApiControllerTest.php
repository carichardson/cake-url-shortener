<?php

class ApiControllerTest extends ControllerTestCase {

	public function testShortenUrl() {
		
		// api request data that should succeed
		$test_data = array(
			'action' => 'shorten_url',
			'url' => 'http://supertesturl.com',
			'timestamp' => (string)time()
		);
		
		$response = $this->_apiCall('url', $test_data);
		$this->assertTrue(!empty($response['success']));
		
		// api request data that should fail
		$test_data = array(
			'action' => 'shorten_url',
			'url' => 'asdfadsfasdfadfsdfsa',
			'timestamp' => (string)time()
		);
		
		$response = $this->_apiCall('url', $test_data);
		$this->assertTrue(empty($response['success']));
	}
	
	function testRetrieveUrl() {
		
		// api request data that should succeed
		$test_data = array(
			'action' => 'retrieve_url',
			'code' => 'beer',
			'timestamp' => (string)time()
		);
		
		$response = $this->_apiCall('url', $test_data);
		$this->assertTrue(!empty($response['success']));
		
		// api request data that should fail
		$test_data = array(
			'action' => 'retrieve_url',
			'code' => '123421341234',
			'timestamp' => (string)time()
		);
		
		$response = $this->_apiCall('url', $test_data);
		$this->assertTrue(empty($response['success']));
	}
	
	function _apiCall($endpoint, $data) {
		
		$ch = curl_init('http://'.Configure::read('Server.base_url').'/api/'.$endpoint);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$response = json_decode(curl_exec($ch), true);
		curl_close($ch);
		
		return $response;
	}
}