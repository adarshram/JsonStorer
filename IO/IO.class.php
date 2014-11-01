<?php 

class IO{
	public function __construct($params=array()){
		$this->params = $params;
	}
	
	public function setParams($params){
		$this->params = $params;
	}
	
	public function getResults(){
		return $this->results;
	}
	public function setResults($v){
		$this->results = $v;
	}
}


class IOFactory{
	public static function getInstance($class_name,$params=array()){
		if(!class_exists($class_name)){
			global $base_path;
			$this_path = $base_path.'IO/';
			include_once($this_path.$class_name.".class.php");
		}
		//resolve dependencies
		$a = new IODependencies($class_name,$params);
		$params = $a->getDependencies();
		
		
		return new $class_name($params);
	}
	
	
	
}


//Dependency Container
class IODependencies{

	public function __construct($class_name,$params){
		$this->class_name = $class_name;
		$this->params = $params;
	}
	
	
	/*
	 * Should be seperated later but for now , what it does is it sees if dependencies are injected already by the caller
	 * if not it injects it via the configuration which is hardcoded inside the code   
	 * 
	 * Todo: Seperate out so the get doesnt have the logic 
	 * Seperate the depedencies either to a DB or a file 
	 */
	
	public function getDependencies(){
		global $base_path;
		$classes = array();
				
		if($this->class_name == 'File'){
			if(!isset($this->params['file_modifier']) || !$this->params['file_modifier'] instanceof FileModifier){
				
				unset($this->params['file_modifier']);
				$params = array('file'=>"$base_path/FileDb/data.store");
				$this->params['file_modifier'] = IOFactory::getInstance('FileModifier',$params);
			}			
		}
		
		if($this->class_name == 'UserDataLogger'){
		
			if(!isset($this->params['file']) || !$this->params['file'] instanceof File){
				unset($this->params['file']);
				$params = array('file'=>"$base_path/FileDb/userdata.store");
				$params['file_modifier'] = IOFactory::getInstance('FileModifier',$params);
				$this->params['file'] = IOFactory::getInstance('File',$params);
			}	
		}
		if($this->class_name == 'UserDataLogReader'){
		
			if(!isset($this->params['file']) || !$this->params['file'] instanceof File){
				unset($this->params['file']);
				$params = array('file'=>"$base_path/FileDb/userdata.store");
				$params['file_modifier'] = IOFactory::getInstance('FileModifier',$params);
				$this->params['file'] = IOFactory::getInstance('File',$params);
			}	
		}
		
		return $this->params;	
	
	}
	
	
	
	
}


class IODependenciesOld{

	public function __construct($class_name){
		$this->class_name = $class_name;
	}
	
	protected function getDependencies(){
		global $base_path;
		$classes = array();
		if($this->class_name == 'File'){
			$classes = array(
				'FileModifier'=>array(				
					'param'=>array('file'=>"$base_path/FileDb/data.store"),
					'parent_param'=>'file_modifier'
				)
			);
			
			
		}
		
		return $classes;
	}
	
	public function getInjectors(){
		$dependencies = $this->getDependencies();
		$parent_params = array();
		foreach($dependencies as $class_name =>$params){
			$dependent_class = IOFactory::getInstance($class_name,$params['param']);
			$parent_params[$params['parent_param']] = $dependent_class;
	 
		}
		
		return $parent_params;
		
	}
}
