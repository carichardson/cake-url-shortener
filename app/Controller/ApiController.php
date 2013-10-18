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
						$response = $this->_shorten_url($this->request->data);
					break;
					
					case 'retrieve_url':
						$response = $this->_retrieve_url($this->request->data);
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
	function _shorten_url($request) {
		$response = array('success' => false);
		
		if(!empty($request['url'])) {
			
			// extract url and make sure it is formatted with http
			$url = $request['url'];
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
	function _retrieve_url($request) {
		$response = array('success' => false);
		
		if(!empty($request['code'])) {

			// create new code and make sure url validates
			$short_url = $this->ShortUrl->findByCode($request['code']);
			
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
	
	// zeromq api endpoint for urls
	function zeromq_url() {
		$response = array('success' => false, 'error' => 'No data provided.');
	
		// check for post data
		if(!empty($this->request->data)) {
			
			// create ZMQ context
			$context = new ZMQContext();

			// socket to connect to zmq server
			$requester = new ZMQSocket($context, ZMQ::SOCKET_REQ);
			$requester->connect("tcp://localhost:5555");
			
			$requester->send(json_encode($this->request->data));
			$response = $requester->recv();
		}

		echo json_encode($response);
		exit;
	}
	
	// function to create zeromq listener
	function zeromq_server() {

		// create ZMQ context
		$context = new ZMQContext(1);

		// create socket to receive client requests
		$responder = new ZMQSocket($context, ZMQ::SOCKET_REP);
		$responder->bind("tcp://localhost:5555");

		while (true) {
			
		    //  Wait for next request from client
		    $request = $responder->recv();
		    $request = json_decode($request, true);

		    // set default response
			$response = array('success' => false);
			
			// check that action is set
			if(!empty($request['action'])) {
				
				switch ($request['action']) {
					
					case 'shorten_url':
						$response = $this->_shorten_url($request);
					break;
					
					case 'retrieve_url':
						$response = $this->_retrieve_url($request);
					break;
					
					default;
						$response['error'] = 'No api action provided.';
				}
			}
			
		    // send response back to client
		    $responder->send($response);
		}
	}
}