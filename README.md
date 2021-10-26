# Slack API
![GitHub release (latest by date)](https://img.shields.io/github/v/release/fintech-systems/slack-php-api) [![Build Status](https://app.travis-ci.com/fintech-systems/packagist-boilerplate.svg?branch=main)](https://app.travis-ci.com/fintech-systems/slack-php-api) ![GitHub](https://img.shields.io/github/license/fintech-systems/slack-php-api)

A Slack API designed to run standalone or as part of a Laravel Application

Requirements:

- PHP 8.0
- A Slack App

# Usage

## References

- https://www.tyntec.com/docs/whatsapp-business-api-integration-slack
-- Description on setting up the Slack Bot Token
- https://api.slack.com/methods/chat.postMessage
-- Description on how to set up the Slack User Token
- Difference bot and user tokens?
-- https://api.slack.com/authentication/token-types#user
--- xoxp = user token, xoxb = bot token

## Framework Agnostic PHP

```php
<?php

use FintechSystems\Slack\Slack;

require 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$server = [
    'bot_token'  => $_ENV['SLACK_BOT_TOKEN'],
    'user_token' => $_ENV['SLACK_USER_TOKEN'],
    'channel'    => $_ENV['SLACK_CHANNEL'],
];

$api = new Slack($server);

```

## Laravel Installation

You can publish the config file with:
```bash
php artisan vendor:publish --provider="FintechSystems\Slack\SlackServiceProvider" --tag="slack-config"
```

This is the contents of the published config file:

```php
return [
    'bot_token'  => $_ENV['SLACK_BOT_TOKEN'],
    'user_token' => $_ENV['SLACK_USER_TOKEN'],
    'channel'    => $_ENV['SLACK_CHANNEL'],
];
```

## Usage

### Example

```php

use FintechSystems\LaravelApiHelpers\Api;

$api = new Api();

$postData = [
  'channel'   => 'C02G5QS8ANA',
  'text'      => '*Joe Smith*/27823096710: how are you',
  'thread_ts' => '1635100445.007500',
];

$result = $api->postMessage($postData);
```

### Methods

```php
public function postMessage(String $postFields)
public function makeImagePublic($id)
public function reconstructImageUrl($filesZero)
```

## Testing

```bash
vendor/bin/pest  
```

### Local Development

If you are debugging from another package on localhost, then add this to `composer.json`:

```json
"repositories" : [
        {
            "type": "path",
            "url": "../slack-php-api"
        }
    ]
```

Then in `require` section:

```json
"fintech-systems/slack-php-api": "dev-main",
```
## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Feel free to log an issue or create a pull request.

## Credits

- [:author_name](https://github.com/:author_username)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.