<?php
class ApiController extends AppController {
	var $name = 'Api';
	var $uses = array('ShortUrl');

	// api endpoint for urls
	function url() {
		$response = array('success' => false, 'error' => 'No data provided.');
	
		// check for post data
		if(!empty($this->request->data)) {
			
			// check that action is set
			if(!empty($this->request->data['action'])) {
				
				switch ($this->request->data['action']) {
					
					case 'shorten_url':
						$response = $this->_shorten_url();
					break;
					
					case 'retrieve_url':
						$response = $this->_retrieve_url();
					break;
					
					default;
						$response['error'] = 'No api action provided.';
				}
			}
		}

		echo json_encode($response);
		exit;
	}
	
	// logic to handle shorten_url api action for url endpoints
	function _shorten_url() {
		$response = array('success' => false);
		
		if(!empty($this->request->data['url'])) {
			
			// extract url and make sure it is formatted with http
			$url = $this->request->data['url'];
			if( !(strpos($url, 'https://') === 0 OR strpos($url, 'http://') === 0) ) {
				$url = 'http://' . $url;
			}

			// get new code
			$code = $this->ShortUrl->generateCode();

			// create new code and make sure url validates
			$this->ShortUrl->create(array('code' => $code, 'url' => $url));
			if($this->ShortUrl->validates()) {

				// save new short url and 
				$this->ShortUrl->save();
				$response = array('success' => true, 'url' => Configure::read('Server.base_url').'/'.$code);

			// url improperly formatted
			} else {
				$response['error'] = 'Please provide a valid url.';
			}
		} else {
			$response['error'] = 'No url provided to shorten.';
		}
		
		return $response;
	}
	
	// logic to handle retrieve_url api action for url endpoints
	function _retrieve_url() {
		$response = array('success' => false);
		
		if(!empty($this->request->data['code'])) {

			// create new code and make sure url validates
			$short_url = $this->ShortUrl->findByCode($this->request->data['code']);
			
			if(!empty($short_url)) {

				$response = array('success' => true, 'url' => $short_url['ShortUrl']['url']);

			// url improperly formatted
			} else {
				$response['error'] = 'Code not found.';
			}
		} else {
			$response['error'] = 'No short code provided.';
		}
		
		return $response;
	}
	
	function test() {

		$context = new ZMQContext(1);

		//  Socket to talk to clients
		$responder = new ZMQSocket($context, ZMQ::SOCKET_REP);
		$responder->bind("tcp://*:5555");

		while (true) {
		    //  Wait for next request from client
		    $request = $responder->recv();
		    printf ("Received request: [%s]\n", $request);

		    //  Do some 'work'
		    sleep (1);

		    //  Send reply back to client
		    $responder->send("World");
		}
		exit;
	}
}