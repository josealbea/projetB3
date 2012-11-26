<?php 
class Bootstrap {

	protected function _initDoctype() {
		$this->bootstrap('view');
		$view = $this->getRessource('view');
		$view->doctype('html');
	}
}