<?php 


class UserDataLogger{
	public function __construct($params){
		$this->params = $params;
		$this->file = $params['file'];
	}
	
	public function execute(){
		$this->saveThisUserData();
	}
	
	protected function saveThisUserData(){
		$data = array();
		$temp = array();
		$temp['first_name'] = $this->params['first_name'];
		$temp['last_name'] = $this->params['last_name'];
		$data[$this->params['id']] = $temp;
		$this->file->executeQuery();	
	}
	
	
}