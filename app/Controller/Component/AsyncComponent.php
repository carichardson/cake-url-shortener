<?php

class AsyncComponent extends Component {
	
	const CRON_DISPATCHER_LOCATION = "webroot/cron_dispatcher.php";
	
	static function run($path, array $params = null) {

		// add the function parameters to the path, if supplied
		if(!empty($params)) {
			foreach($params as $param) {
				$path .= "/" . $param;
			}
		}

		$exeString = AsyncComponent::getExecString($path, false);
		exec($exeString);
		
		return $exeString;
	}
	
	static function executeInThread($command) {
		$exeString = AsyncComponent::getExecString($command);
		return exec($exeString);
	}
	
	static function executeOutOfThread($command) {
		$exeString = AsyncComponent::getExecString($command, false);
		return exec($exeString);
	}
	
	static function getExecString($command, $executeInThread=true) {
		$es = PATH_TO_PHP_CLI . " " . APP . AsyncComponent::CRON_DISPATCHER_LOCATION . " " . $command;
		if (!$executeInThread) $es .= " > /dev/null 2>&1 &";
		return $es;
	}
}