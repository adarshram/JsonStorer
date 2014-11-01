<?php 

//{"1708":{"first_name":"Adarshram","last_name":"Anantharaman"},"1709":{"first_name":"Pranoti P ","last_name":"Pawar"}}
class UserDataLogReader extends IO{
	public function __construct($params){
		$this->params = $params;
		$this->file = $params['file'];
	}
	
	public function execute(){
		$this->readUserData();
	}
	
	protected function readUserData(){
		$params = array();
		$params['type'] = 'read';
		
		if(is_numeric($this->params['id'])){
			$params['id'] = $this->params['id'];
		}
			
		$this->file->setParams($params);
		$this->file->executeQuery();
		$this->setResults($this->file->getResults());	
	}
	
	
}