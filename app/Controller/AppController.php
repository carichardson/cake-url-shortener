<?php
App::uses('Controller', 'Controller');

class AppController extends Controller {

	function beforeRender() {
		//404 ERROR
	    if($this->name == 'CakeError') {
	    	$this->set('inversenav',true);
	        $this->layout = 'default';
	    }
	}
}