<?php

require "vendor/autoload.php";

use Abraham\TwitterOAuth\TwitterOAuth;
use Google\Spreadsheet\DefaultServiceRequest;
use Google\Spreadsheet\ServiceRequestFactory;


 	$aDiceValues = array();
	$consumerKey = "5DRHOI34z4Eb2P5INeZnTn5dP";
	$consumerSecret = "uH10YyynLJZIbMEQk4Zq42UHSJBmXvbhT98Kmr8yRaHBJON6AU";
	$accessToken = "269931982-lvZn8v0Uf8hW9UKl2SqRFQesvWeqrb6knreHSgzw";
	$accessTokenSecret = "1UGNYBESwuTHxeLgCVU8QAqdWxLpYLLqRHO2EQZuIAPUK" ;


	$connection =  new TwitterOAuth($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);

	putenv('GOOGLE_APPLICATION_CREDENTIALS=' . __DIR__ . '/client_secret.json');
	$client = new Google_Client;
	$client->useApplicationDefaultCredentials();
	 
	$client->setApplicationName("Something to do with my representatives");
	$client->setScopes(['https://www.googleapis.com/auth/drive','https://spreadsheets.google.com/feeds']);
	 
	if ($client->isAccessTokenExpired()) {
	    $client->refreshTokenWithAssertion();
	}
	 
	$accessToken = $client->fetchAccessTokenWithAssertion()["access_token"];
	ServiceRequestFactory::setInstance(
	    new DefaultServiceRequest($accessToken)
	);


	$spreadsheetService = new Google\Spreadsheet\SpreadsheetService();
	$spreadsheetFeed = $spreadsheetService->getSpreadsheetFeed();


	$spreadsheet = $spreadsheetFeed->getByTitle('Twitter Bot');

	$worksheets = $spreadsheet->getWorksheetFeed()->getEntries();

	$worksheet = $worksheets[1];
	$listFeed = $worksheet->getListFeed();
	$cellFeed = $worksheet->getCellFeed();
	$cellFeed->editCell(1,1, "Name");
	$cellFeed->editCell(1,2, "Followers");

	// Search for users with tweet that contains a certain hashtag
	$data = $connection->get("users/search", ["q" => "%23Arsenal", "page" => 1, "count" => 20]);

?>

<html>
	<head>
		<title>Twitter Bot</title>
		<link rel="stylesheet" href="style.css">
	</head>
	<body>
		<div class="container">
			<table>
				<thead>
					<tr>
						<td>SN</td>
						<td>Profile Name</td>
						<td>Number of Followers</td>
					</tr>
				</thead>
				<tbody>
					<?php 
						foreach ($data as $user)
						{
							
							$listFeed->insert([
						        'name' => $user->name,
						        'followers'	=> $user->followers_count
						    ]);

							echo "<tr>";
							echo "<td>".$user->name . " </td>" ;
							echo "<td>" .number_format($user->followers_count, 0) ."</td>" ;
							echo "</tr>";
							
						} 
					?>
				</tbody>
			</table>
		</div>
</body>
</html>


