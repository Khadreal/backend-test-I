<?php

require "vendor/autoload.php";
require_once('OauthPhirehose.php');

use Google\Spreadsheet\DefaultServiceRequest;
use Google\Spreadsheet\ServiceRequestFactory;

class ScrapTweet extends OauthPhirehose
{
  public function saveInPreadSheet($name, $followers)
  {
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

    $spreadsheetService = new Google\Spreadsheet\SpreadsheetService();
    $spreadsheetFeed = $spreadsheetService->getSpreadsheetFeed();
    $spreadsheet = $spreadsheetFeed->getByTitle('Twitter Bot');

    $worksheets = $spreadsheet->getWorksheetFeed()->getEntries();

    $worksheet = $worksheets[0];
    $listFeed = $worksheet->getListFeed();
    $cellFeed = $worksheet->getCellFeed();
    $cellFeed->editCell(1,1, "Name");
    $cellFeed->editCell(1,2, "Followers");


    $listFeed->insert([
      'name' => $name,
      'followers' => $followers
    ]);
  }
  
  public function enqueueStatus($status)
  {

    $data = json_decode($status, true);
    if (is_array($data) && isset($data['user']['screen_name'])) {
      $this->saveInPreadSheet($data['user']['screen_name'], $data['user']['followers_count']);
      print "Name of Profile: ".$data['user']['screen_name'] . '    Number of Followers: ' . $data['user']['followers_count'] . "\n";
      
    }
  }
}

// The OAuth credentials you received when registering your app at Twitter
define("TWITTER_CONSUMER_KEY", "5DRHOI34z4Eb2P5INeZnTn5dP");
define("TWITTER_CONSUMER_SECRET", "uH10YyynLJZIbMEQk4Zq42UHSJBmXvbhT98Kmr8yRaHBJON6AU");
define("OAUTH_TOKEN", "269931982-lvZn8v0Uf8hW9UKl2SqRFQesvWeqrb6knreHSgzw");
define("OAUTH_SECRET", "1UGNYBESwuTHxeLgCVU8QAqdWxLpYLLqRHO2EQZuIAPUK");



// Start streaming
$sc = new ScrapTweet(OAUTH_TOKEN, OAUTH_SECRET, Phirehose::METHOD_FILTER);
echo "Enter the hashtag below without the # sign :\n";
$var = fread(STDIN, 80); // Read up to 80 characters or a newline
$hashtag = "#". $var;

$sc->setTrack(array($hashtag));
$sc->consume();
