<?php
ini_set('display_errors', '1');
error_reporting(E_ALL);
// token of your bot
define("TOKEN", '1574704334:AAHDfAS9RJVLOZ2dsoM_ap31K-ixqVhhm6Y'); 
define('MAIN_URL', 'https://api.telegram.org/bot'.TOKEN.'/');


$request = json_decode(file_get_contents("php://input"), 1);

file_put_contents('update.json', json_encode($request));
file_put_contents('updates/' . $request['update_id'] . '.json', json_encode($request));
// print_r($request);

if (isset($request['message']) && isset($request['message']['from'])) {

  $TG_ID = $request['message']['from']['id'];
  
  if (isset($request['message']['text'])) {
    $text = "You can send commands below: \n1. /start\n2. /myid";  
    if ($request['message']['text'] == '/start') {
      $text = "Welcome to TG ID(Telegram ID extractor bot)! You can send commands below: \n1. /start\n2. /myid";  
    }
    
    
    if ($request['message']['text'] == '/myid') {
      $text = "Your TG ID: <pre>$TG_ID</pre>";  
    }    
  }else{
    $text = 'You can send only text messages!';
  }


  $data = [
    'chat_id' => $request['message']['from']['id'],
    'text' => $text,
    'parse_mode' => 'HTML'
  ];

  sendMessage($data, 'sendMessage');
  echo 'tg_id';
  die;
}




function sendMessage($content, $method) {

	$curl = curl_init(); 


	curl_setopt($curl, CURLOPT_URL, MAIN_URL.$method); 


	//return the transfer as a string 
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 
	
	curl_setopt($curl, CURLOPT_POSTFIELDS, $content);
	// $output contains the output string 
	$output = curl_exec($curl); 

	curl_close($curl);      

	file_put_contents("return_sent.txt", $output);
	return $output;

}
