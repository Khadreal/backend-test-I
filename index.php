<?php

require "vendor/autoload.php";

use Abraham\TwitterOAuth\TwitterOAuth;
use Google\Spreadsheet\DefaultServiceRequest;
use Google\Spreadsheet\ServiceRequestFactory;
$response = new \League\CLImate\CLImate;

 	$aDiceValues = array();
	$consumerKey = "5DRHOI34z4Eb2P5INeZnTn5dP";
	$consumerSecret = "uH10YyynLJZIbMEQk4Zq42UHSJBmXvbhT98Kmr8yRaHBJON6AU";
	$accessToken = "269931982-lvZn8v0Uf8hW9UKl2SqRFQesvWeqrb6knreHSgzw";
	$accessTokenSecret = "1UGNYBESwuTHxeLgCVU8QAqdWxLpYLLqRHO2EQZuIAPUK" ;


	$connection =  new TwitterOAuth($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);

	putenv('GOOGLE_APPLICATION_CREDENTIALS=' . __DIR__ . '/client_secret.json');
	$client = new Google_Client;
	$client->useApplicationDefaultCredentials();
	 
	$client->setApplicationName("Devcenter Tweet Bot");
	$client->setScopes(['https://www.googleapis.com/auth/drive','https://spreadsheets.google.com/feeds']);
	 
	if ($client->isAccessTokenExpired()) {
	    $client->refreshTokenWithAssertion();
	}
	 
	$accessToken = $client->fetchAccessTokenWithAssertion()["access_token"];
	ServiceRequestFactory::setInstance(
	    new DefaultServiceRequest($accessToken)
	);
	

	
		echo "Enter the hashtag below without the # sign :\n";
	$var = fread(STDIN, 80); // Read up to 80 characters or a newline
	$hashtag = "%23". $var;


	$spreadsheetService = new Google\Spreadsheet\SpreadsheetService();
	$spreadsheetFeed = $spreadsheetService->getSpreadsheetFeed();


	$spreadsheet = $spreadsheetFeed->getByTitle('Twitter Bot');

	$worksheets = $spreadsheet->getWorksheetFeed()->getEntries();

	$worksheet = $worksheets[0];
	$listFeed = $worksheet->getListFeed();
	$cellFeed = $worksheet->getCellFeed();
	$cellFeed->editCell(1,1, "Name");
	$cellFeed->editCell(1,2, "Followers");

	

	$data = $connection->get("search/tweets", ["q" => $hashtag, "count" => 2]);
	

	$object = $data->statuses;

	$items = array();
	
	foreach ($object as $user)
	{
		$listFeed->insert([
	        'name' => $user->user->name,
	        'followers'	=> $user->user->followers_count
	    ]);
		$items[] = array(
			'Name of Profile'	=>	$user->user->name,
			'Numbers of Followers' => number_format($user->user->followers_count, 0)
		);
		
		

		
	} 

	$response->table($items);
	

?>



