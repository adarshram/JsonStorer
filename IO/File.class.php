<?php 
//dependent on file modifier beeing created first
class File extends IO{
	public function __construct($params){
		parent::__construct($params);
		global $base_path;
		//move out the dependency
		$this->file_modifier = $params['file_modifier'];
		$this->file_data = false;
	}
	public function executeQuery(){
		$this->readFromFile();
		if($this->params['type'] == 'read'){
			$this->executeReadQuery();
		}
		if($this->params['type'] == 'write'){
			$this->executeWriteQuery();
		}
		if($this->params['type'] == 'update'){
			$this->executeUpdateQuery();
		}
		
	}
	
	protected function executeReadQuery(){
		if(isset($this->params['id'])){
			$this->results = $this->fetchResultById($this->params['id']);
			return;
		}
		else{
			$this->results = $this->file_data;
			return;
		}
	}
	
	protected function fetchResultById($id){
		$data = $this->readFromFile();
		if($data[$id]){
			return $data[$id];	
		}
		return false;
		
		
	}
	
	
	protected function executeUpdateQuery(){
		$data = $this->readFromFile();
		$id = $this->params['id'];
		
		$data[$id] = $this->params['data'];
		
		$str = json_encode($data);
		$this->writeToFile($str);		
		$this->results = $id;
	}
	
	protected function executeWriteQuery(){
		if(!isset($this->params['id'])){
			$last_id = $this->readIdsFromFile();
			$id = $last_id+1;
			$this->params['id'] = $id;
		}
		$this->executeUpdateQuery();		
	}
	
	
	/*
	 * returns the resolved json array
	 */
	protected function readFromFile(){
		if($this->file_data){
			return $this->file_data;
		}
		
		$result  = $this->readFile();
		
		if($result == ''){
			return array();		
		}
		
		$results = json_decode($result,true);
		$this->file_data = $results;
		return $this->file_data;
	}
	
	
	protected function readIdsFromFile(){
		$result = $this->readFromFile();
		$last_id = 0;
		//var_dump($result);exit;
		
		foreach($result as $k=>$v){			
			$last_id = $k;
		}
		return $last_id;
	}
	
	
	
	
	protected function readFile(){
		// Open the file to get existing content
		
		return $this->file_modifier->readFile();
		
		
	}
	
	private function writeToFile($data){
		$this->file_modifier->writeToFile($data);
		$this->file_data = false;
	
	}
	
}