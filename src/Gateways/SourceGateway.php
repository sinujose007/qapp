<?php
/**
* Gateway class for the storage
*
*
*
* implement methods to return all records, return a specific question and add/update/delete a question.
* and we can extend with  extra method to check some info.
*
* 
* PHP 7.0
*
* Copyright 2021_?
*
* @location /src/Gateways/SourceGateway.php
* @created 2021-07-17
*/

namespace Src\Gateways;

class SourceGateway{

    private $db = null;
	private $base_source;

    public function __construct()
    {
        $this->base_source = "../database/questions.json";
    }
	
	/*
	** funtions to fetch all questions and options
	*/
    public function findAll()
    {
        //read data from source file
		$data = file_get_contents($this->base_source);
		$result = json_decode($data, true); 
		//return the records
        return $result;
    }
	
	/*
	** save the new questions in the source db file
	*/
    public function insert(Array $input)
    {
		// read the file if present
		$handle = @fopen($this->base_source, 'r+');
		// create the file if needed
		if ($handle == null) {
			$handle = fopen($filename, 'w+');
		}
		if ($handle) {    
			fseek($handle, 0, SEEK_END); // seek to the end
			if (ftell($handle) > 0) {				
				fseek($handle, -1, SEEK_END);// move back a byte        
				fwrite($handle, ',', 1);// add the trailing comma        
				fwrite($handle, json_encode($input) . ']');// add the new json string
			}else {        
				fwrite($handle, json_encode(array($input))); // write the first event inside an array
			}
			fclose($handle);
			return true;	
		}else {
			return false;	
		}
    }

    public function update($id, Array $input)
    {
        
    }

    public function delete($id)
    {
        
    }
}