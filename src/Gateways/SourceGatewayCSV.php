<?php
/**
* Gateway class for the storage CSV
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
* @location /src/Gateways/SourceGatewayCSV.php
* @created 2021-07-17
*/

namespace Src\Gateways;

class SourceGatewayCSV{

    private $db = null;
	private $base_source;

    public function __construct()
    {
        $this->base_source = "../database/questions.csv";
    }
	
	/*
	** funtions to fetch all questions and options
	*/    
    public function findAll()
    {
        $data = array_map('str_getcsv', file($this->base_source));
		unset($data[0]);
		$res = [];
		foreach ($data as $k=>$v) {
			$result = [];
			$result['text'] = $v[0];
			$result['createdAt'] = $v[1];
			$result['choices'] = array(array('text' => $v[2]), array('text' => $v[3]), array('text' => $v[4]));
			$res[] = $result;
		}
        return $res;
    }
	
	/*
	** save the new questions in the source db file
	*/
    public function insert(Array $input)
    {
		$insert = [];
		$insert[] = $input["text"];
		$insert[] = $input["createdAt"];
		foreach($input['choices'] as $k=>$v){
			array_push($insert, $v['text'] );
		}
		// read the file if present
		$handle = fopen($this->base_source, 'a');
		if ($handle) {    
			fputcsv($handle, $insert); 
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

    