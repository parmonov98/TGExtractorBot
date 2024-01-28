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

  $FROM_ID = $request['message']['from']['id'];
	
  $CHAT_ID = $request['message']['chat']['id'];

  if (isset($request['message']['text'])) {
    $command = $request['message']['text'];

    $text = "/start - Start bot \n/myid - To get current chat's id";
    if ($command === '/start') {
      $text = "Welcome to TG ID(Telegram ID extractor bot)! You can send commands below: \n1. /start\n2. /myid";  
    }

    if ($command === '/myid' || str_contains($command, '\/myid')) {
      $text = "USER ID: <pre>$FROM_ID</pre>\nCHAT ID: <pre>$CHAT_ID</pre>\n";  
    }

    print_r($CHAT_ID);
    echo "\n";
    print_r($FROM_ID);
    if ($CHAT_ID === $FROM_ID){
        $data = [
            'chat_id' => $CHAT_ID,
            'text' => $text,
            'parse_mode' => 'HTML'
        ];

        return sendMessage($data, 'sendMessage');
    }
  }

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
