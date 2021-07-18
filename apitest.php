<?php

  
   
    $url = 'http://127.0.0.1/qapp/public/questions?lang=fr';
    $time = date('Y-m-d H:i:s');
    $data_array =  array(
		"text"       => 'What is your name ?',
		"createdAt"  => $time,
		"choices"    => array(array("text" => 'NAME A'),array("text" => 'NAME B'),array("text" => 'NAME C'))
	);
	$data = json_encode($data_array);
	
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_POST, 1);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    // EXECUTE:
    $result = curl_exec($curl);
    if(!$result){die("Connection Failure");}
    curl_close($curl);
    print_r($result);
	 
   ?>