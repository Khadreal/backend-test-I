## Back-end Developer Test

### Devcenter Backend Developer Test I

The purpose of this test is not only to quickly gauge an applicant's abilities with writing codes, but also their approach to development.

Applicants may use whatever language they want to achieve the outcome.

## Task

This bot extracts the following from people’s Twitter bio (on public/open accounts), into a Google spreadsheet:

* Twitter profile name 
* Number of followers

Target accounts using this criteria:
* Based on hashtags used

## Show your working

If you choose to use build tools to compile your CSS and Javascript (such as SASS of Coffescript) please include the original files as well. You may update this README file outlining the details of what tools you have used.
This was built on PHP using abraham twiiter oauth and google client api 

## Development language
PHP 7.0.9

## Getting Started

* You need to set up the following to run the application successfully   

## Twitter Account / App Setup
1. Go to https://apps.twitter.com, click on `Create New App` Button
2. Fill out the form correctly, click `Create your Twitter Application`
3. Replace the consumer key, consumer secret, access token and access token secret values with the ones you generate.

## Google Spreadsheet
1. Go to Google API console https://console.cloud.google.com/apis/
2. Create a new project
3. Click Enable API. Search for and enable the Google Drive API.
4. Create credentials for a service account to access Application data.
5. Obtain OAuth2 credentials from Google Developers Console for google spreadsheet api and drive api
6. Save the file as client_secret.json in same directory as project.

## Install dependencies 
* Run 'Composer install'


### Run Application
* Enter your twitter api credentials in index.php
* Get your google api credentials in client_secret.json
* Run `php index.php` from the terminal