#  OneSignal Push Notifications for Laravel 5

## Introduction

This is a simple OneSignal wrapper library for Laravel. It simplifies the basic notification flow with the defined methods. You can send a message to all users or you can notify a single user. 
Before you start installing this service, please complete your OneSignal setup at https://onesignal.com and finish all the steps that is necessary to obtain an application id and REST API Keys.


## Installation

First, you'll need to require the package with Composer:

```sh
composer require liliom/laravel-onesignal
```


Update your `config/app.php` by adding the following service provider.

```php
'providers' => [
	// ...
	// ...
	Liliom\OneSignal\OneSignalServiceProvider::class,
	// ...
];
```


Then, register class alias by adding an entry in aliases section

```php
'aliases' => [
	// ...
	'OneSignal' => Liliom\OneSignal\OneSignalFacade::class
];
```


Finally, publish the config file by running:

```
php artisan vendor:publish --tag=config
``` 
 
The command above shall publish a configuration file named `onesignal.php` which includes your OneSignal authorization keys.


## Configuration

Please fill the file `config/onesignal.php`.
`app_id` is your *OneSignal App ID* and `rest_api_key` is your *REST API Key*, where `user_auth_key` is optional.
 
Or alternatively you can fill your settings in `.env` file as the following:
```
ONE_SIGNAL_APP_ID=
ONE_SIGNAL_REST_API_KEY=
```

## Usage

### Sending a Notification To All Users

You can easily send a message to all registered users with the command

    OneSignal::sendNotificationToAll("Some Message", $url = null, $data = null, $buttons = null, $schedule = null);
    
`$url` , `$data` , `$buttons` and `$schedule` fields are exceptional. If you provide a `$url` parameter, users will be redirecting to that url.
    

### Sending a Notification based on Tags/Filters

You can send a message based on a set of tags with the command

    OneSignal::sendNotificationUsingTags("Some Message", array("key" => "device_uuid", "relation" => "=", "value" => 123e4567-e89b-12d3-a456-426655440000), $url = null, $data = null, $buttons = null, $schedule = null);


### Sending a Notification To A Specific User

After storing a user's tokens in a table, you can simply send a message with

    OneSignal::sendNotificationToUser("Some Message", $userId, $url = null, $data = null, $buttons = null, $schedule = null);
    
`$userId` is the user's unique id where he/she is registered for notifications. Read https://documentation.onesignal.com/docs/web-push-tagging-guide for additional details.
`$url` , `$data` , `$buttons` and `$schedule` fields are exceptional. If you provide a `$url` parameter, users will be redirecting to that url.

### Sending a Notification To A Specific User via Email Address

If you are using the option to set the userId as email address of the user then use the following function

    OneSignal::sendNotificationToUserByEmail("Some Message", $email, $filters = [], $segment = ['All'], $url = null, $data = null, $buttons = null, $schedule = null, $smallIcon = null, $LargeIcon = null, $bigPicture = null, $androidAccentCircleColor = null, $androidAccentLedColor = null, $sound = null )


### Sending a Notification To Segment

You can simply send a notification to a specific segment with

    OneSignal::sendNotificationToSegment("Some Message", $segment, $url = null, $data = null, $buttons = null, $schedule = null);
    
`$url` , `$data` , `$buttons` and `$schedule` fields are exceptional. If you provide a `$url` parameter, users will be redirecting to that url.

### Sending a Custom Notification

You can send a custom message with 

    OneSignal::sendNotificationCustom($parameters);
    
    ### Sending a Custom Notification
### Sending a async Custom Notification
You can send a async custom message with 

    OneSignal::async()->sendNotificationCustom($parameters);
    
Please refer to https://documentation.onesignal.com/reference for all customizable parameters.

