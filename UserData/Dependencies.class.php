<?php 


class UserData{
	public function __construct($params=array()){
		$this->params = $params;
	}
	
	public function setParams($params){
		$this->params = $params;
	}
	
	public function getResults(){
		return $this->results;
	}
	
}


class UserDataFactory{
	public static function getInstance($class_name,$params=array()){
		if(!class_exists($class_name)){
			global $base_path;
			$this_path = $base_path.'UserData/';
			include_once($this_path.$class_name.".class.php");
		}
		//resolve dependencies
		$a = new UserDataDependencies($class_name);
		$inject_params = $a->getInjectors();
		$params = $params+$inject_params;
		
		return new $class_name($params);
	}
	
	
	
}


class UserDataDependencies{

	public function __construct($class_name){
		$this->class_name = $class_name;
	}
	
	protected function getDependencies(){
		global $base_path;
		$classes = array();
		if($this->class_name == 'UserDataLogger'){
			include_once($base_path.'IO/IO.class.php');
			//first we need to resolve the file modifier dependency
			$file = IOFactory::getInstance('File',array());
		
			$classes = array(
				'File'=>array(				
					'param'=>array('file'=>$file),
					'parent_param'=>'file'
				)
			);
			
			
		}		
		return $classes;
	}
	
	public function getInjectors(){
		$dependencies = $this->getDependencies();
		$parent_params = array();
		foreach($dependencies as $class_name =>$params){
			$dependent_class = UserDataFactory::getInstance($class_name,$params['param']);
			$parent_params[$params['parent_param']] = $dependent_class;
	 
		}
		
		return $parent_params;
		
	}
}
