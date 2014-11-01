<?php 



//move it out 
class FileModifier extends IO{
	public function __construct($params){
		$this->file = $params['file'];	
	}
	
	
	
	public function readFile(){
		// Open the file to get existing content
		
		return trim(file_get_contents($this->file));
	}
	
	public function writeToFile($data){
		$file = $this->file;
		file_put_contents($file, $data, LOCK_EX);
	}
	
}