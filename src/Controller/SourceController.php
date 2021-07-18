<?php
/**
* Controller class for the storage
*
*
*
* implement methods fetch and create records.
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
namespace Src\Controller;

use Src\Gateways\SourceGateway;
use Src\Gateways\SourceGatewayCSV;
use Stichoza\GoogleTranslate\GoogleTranslate;

class SourceController {

    private $requestMethod;
    private $questionId;
	private $lang;
	private $db = null;

    private $sourceGateway;

    public function __construct($requestMethod, $questionId, $lang)
    {
        $this->requestMethod = $requestMethod;
        $this->questionId = $questionId;
		$this->lang = $lang;
		switch (CONFIG_SOURCE) {
            case 'JSON':
                $this->sourceGateway = new SourceGateway();
                break;
            case 'CSV':
               $this->sourceGateway = new SourceGatewayCSV();
                break;
            case 'MYSQL':
                $this->sourceGateway = new SourceGatewayMySql($this->db);
                break;
            default:
                $this->sourceGateway = new SourceGateway();
                break;
        }        
    }
	
	/*
	** Method to process the requests
	*/
    public function processRequest()
    {
        switch ($this->requestMethod) {
            case 'GET':
                if ($this->questionId) {
                    $response = $this->getQuestion($this->questionId, $this->lang);
                } else {
                    $response = $this->getAllQuestions($this->lang);
                };
                break;
            case 'POST':
                $response = $this->createQuestionFromRequest();
                break;
            case 'PUT':
                $response = $this->updateQuestionFromRequest($this->userId);
                break;
            case 'DELETE':
                $response = $this->deleteQuestion($this->userId);
                break;
            default:
                $response = $this->notFoundResponse();
                break;
        }
        header($response['status_code_header']);
        if ($response['body']) {
            echo $response['body'];
        }
    }
	
	/*
	** Method to read all question records from the source 
	*/
    private function getAllQuestions($lang)
    {
        if (! $this->validateQuestion($lang)) {
            return $this->unprocessableEntityResponse();
        }		
		$lists = $this->sourceGateway->findAll();
		$result["description"] = "List of translated questions and associated choices";
		if ($lang != 'en'){
			if (!empty($lists)) {
				foreach ($lists as $k=>$v) {
					$v['text'] = $this->translateText($v['text'], $lang);
					foreach ($v['choices'] as $k1=>$v1) {
						$v['choices'][$k1]['text'] = $this->translateText($v1['text'] , $lang);
					}
					$lists[$k] = $v;
				}
			}			
		}
		$result["QuestionList"]["data"] = $lists;		
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }
	
	/*
	** functions to create questions.
	** Receive the parameters and insert in to source DB.
	*/	    
    private function createQuestionFromRequest()
    {
        $result = [];
		$input = (array) json_decode(file_get_contents('php://input'), TRUE);
        if (! $this->validatePostQuestion($input)) {
            return $this->unprocessableEntityResponse();
        }
        if ($this->sourceGateway->insert($input) ){
			$response['status_code_header'] = 'HTTP/1.1 200 Created';
			$result["description"] = "Question and associated choices.";
			$result["Question"] = $input;	
			$response['body'] = json_encode($result);
		}else {
			$response['status_code_header'] = 'HTTP/1.1 407 Unprocessable Action';
			$response['body'] = json_encode([
				'error' => 'Method failed, Unable to insert'
			]);
		}
		return $response;
    }
	
	/*
	** validate input parameters received through get.
	*/	
    private function validateQuestion($input)
    {
        if ($input == null) {
            return false;
        }else if (!preg_match('/^[a-z]*$/', $input) || strlen($input) != 2) {
			return false;
		}			
        return true;
    }
	
	/*
	** validate input parameters posted.
	** Can extend this functionality with different error codes and messages in future.
	*/	
	private function validatePostQuestion($input)
    {
        if (! isset($input['text'])) {
            return false;
        }
		if (! isset($input['createdAt'])) {
            return false;
        }
		if (! isset($input['choices'])) {
            return false;
        }		
		//the number of associated choices must be exactly equal to 3
		if( count($input['choices']) != 3 ){
			return false;
		}
        return true;
    }
	
	/*
	** Return error code
	** We can extend this function with different error codes and methods in future.
	*/
    private function unprocessableEntityResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 422 Unprocessable Entity';
        $response['body'] = json_encode([
            'error' => 'Invalid input'
        ]);
        return $response;
    }
	
	/*
	** Return error code 404, if method not found.
	*/
    private function notFoundResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
        $response['body'] = null;
        return $response;
    }
	
	/*
	** Functions to translate input text to requested language.
	** Using Stichoza\GoogleTranslate
	*/
	private function translateText($text, $lang)
    {
        $translate = new GoogleTranslate(); // Translates to '$lang' from auto-detected language by default
		$translate->setSource('en'); // Translate from English
		$translate->setSource(); // Detect language automatically
		$translate->setTarget($lang); // Translate to Spanish
		$result = $translate->translate($text);
		return $result;
    }
	
	/*
	** function to get individual questions from id, can extend for future use.
	*/
	private function getQuestion($id, $lang)
    {
        
    }
	
	/*
	** function to update questions, can extend for future use.
	*/
    private function updateQuestionFromRequest($id)
    {
        
    }
	
	/*
	** function to delete questions, can extend for future use.
	*/
    private function deleteQuestion($id)
    {
       
    }
	
}