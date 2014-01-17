<?php 
/**
 * @uri /task
 * @uri /task/:id
 */
class TaskResource extends CrudResource {

	function before($action) {
		$this->request->params['resource'] = "task";
		parent::before($action);
	}
	
}
?>