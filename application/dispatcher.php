<?php 
class Dispatcher {

	public function getControllerView($controller, $action) {
		$getcontroller = APPLICATION_PATH.'/controllers/'.$controller.'/'.$action.'.php';
		$getview = APPLICATION_PATH.'/views/'.$controller.'/'.$action.'.php';
	}
}