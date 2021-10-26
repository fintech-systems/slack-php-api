<?php

use FintechSystems\Slack\Tests\Config;
use FintechSystems\LaravelApiHelpers\Api;
use FintechSystems\Slack\Facades\Slack as SlackFacade;

$config = new Config();

it('can test', function () {
    expect(true)->toBeTrue();
});

it('can post a message to a Slack Channel that uses an App using the vanilla API', function () {    
    require 'vendor/autoload.php';

    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/..");
    $dotenv->load();

    $header = [
        "Content-type: application/json;charset=utf-8",
        "Authorization: Bearer " . $_ENV['SLACK_BOT_TOKEN'],
    ];

    $data = [        
        "channel"  => $_ENV['SLACK_CHANNEL'],
        "text"     => "Test 1",
        "username" => "tester",
    ];

    $api = new Api();

    $result = $api->post(
        'https://slack.com/api/chat.postMessage',
        json_encode($data),
        $header
    );
    
    expect(json_decode($result)->ok)->toEqual(true);    
});

it('can use a Laravel Facade to send a Slack message', function () {
    $payload = [        
        "channel"  => $_ENV['SLACK_CHANNEL'],
        "text"     => "Test 2",
        "username" => "tester",
    ];

    $result = SlackFacade::postMessage($payload);

    expect(json_decode($result)->ok)->toEqual(true);    
});

it('can send a massive block template payload', function () {
    $data = '{
        "channel": "#whatsapp-support",
        "text": "New Paid Time Off request from Fred Enriquez",
        "blocks": [
            {
                "type": "section",
                "text": {
                    "type": "mrkdwn",
                    "text": "You have a new request:\n*<fakeLink.toEmployeeProfile.com|Fred Enriquez - New device request>*"
                }
            },
            {
                "type": "section",
                "fields": [
                    {
                        "type": "mrkdwn",
                        "text": "*Type:*\nComputer (laptop)"
                    },
                    {
                        "type": "mrkdwn",
                        "text": "*When:*\nSubmitted Aut 10"
                    },
                    {
                        "type": "mrkdwn",
                        "text": "*Last Update:*\nMar 10, 2015 (3 years, 5 months)"
                    },
                    {
                        "type": "mrkdwn",
                        "text": "*Reason:*\nAll vowel keys aren\t working."
                    },
                    {
                        "type": "mrkdwn",
                        "text": "*Specs:*\n\"Cheetah Pro 15\" - Fast, really fast\""
                    }
                ]
            },
            {
                "type": "actions",                
                "elements": [
                    {
                        "type": "button",                        
                        "text": {
                            "type": "plain_text",
                            "emoji": true,
                            "text": "Log Ticket"                            
                        },
                        "style": "primary",
                        "value": "click_me_123"
                    },
                    {
                        "type": "button",
                        "text": {
                            "type": "plain_text",
                            "emoji": true,
                            "text": "Send Invoice"
                        },
                        "style": "danger",
                        "value": "click_me_123"
                    }
                ]
            }
        ]
      }';
       
    $result = SlackFacade::postMessage(
        json_decode($data, true)
    );

    expect(json_decode($result)->ok)->toEqual(true);        
});

it('can make a Slack image public', function () {
    
    $result = SlackFacade::makeImagePublic("F02KP2GS1FA");

    $jsonResult = json_decode($result, true);
                
    expect($jsonResult)->toHaveKeys(['ok']);

});

it('can convert an image with a filename with a space to an url to a publically accessible', function() {    
    $filesZero['permalink_public'] = 'https://slack-files.com/TF7LTHRFV-F02JZE5BDMZ-73b42cafe7';
    
    $filesZero['title'] = 'the image.png';

    $result = SlackFacade::reconstructImageUrl($filesZero);

    expect($result)->toBe('https://files.slack.com/files-pri/TF7LTHRFV-F02JZE5BDMZ/the_image.png?pub_secret=73b42cafe7');
});

it('can display an interactive message on Slack about playing a game', function() {
    $data = '
    {
        "channel": "#whatsapp-support",
        "text": "Would you like to play a game?",
        "attachments": [
            {
                "text": "Choose a game to play",
                "fallback": "You are unable to choose a game",
                "callback_id": "log-ticket-callback",
                "color": "#3AA3E3",
                "attachment_type": "default",
                "actions": [
                    {
                        "name": "game",
                        "text": "Chess",
                        "type": "button",
                        "value": "chess"
                    },
                    {
                        "name": "game",
                        "text": "Falken\'s Maze",
                        "type": "button",
                        "value": "maze"
                    },
                    {
                        "name": "game",
                        "text": "Thermonuclear War",
                        "style": "danger",
                        "type": "button",
                        "value": "war",
                        "confirm": {
                            "title": "Are you sure?",
                            "text": "Wouldn\'t you prefer a good game of chess?",
                            "ok_text": "Yes",
                            "dismiss_text": "No"
                        }
                    }
                ]
            }
        ]
    }';

    $result = SlackFacade::postMessage(
        json_decode($data, true)
    );
    
    expect(json_decode($result)->ok)->toEqual(true);

});
