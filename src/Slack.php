<?php

namespace FintechSystems\Slack;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use FintechSystems\Slack\Contracts\ChatProvider;

class Slack implements ChatProvider
{
    private string $bot_token;
    private string $user_token;
    private string $channel;

    private object $log;

    public function __construct($server)
    {
        $this->bot_token  = $server['bot_token'];
        $this->user_token = $server['user_token'];
        $this->channel    = $server['channel'];
        // create a log channel
        $this->log = new Logger('name');
        $this->log->pushHandler(new StreamHandler(__DIR__ . '/../storage/logs/api.log', Logger::WARNING));
    }
    
    public function postMessage(String $postFields)
    {       
        $header = [
            "Content-Type: application/json;charset=utf-8",
            "Authorization: Bearer $this->bot_token",
        ];
                
        $api = new \FintechSystems\LaravelApiHelpers\Api;
        
        $result= $api->post(
            'https://slack.com/api/chat.postMessage',
            $postFields,
            $header,
        );
        
        if (json_decode($result)->ok != true) {
            $message = "Slack API error: " . $result;
            ray("Slack API error", json_decode($result));
            $this->log->error($message);
        }

        return $result;
    }

    public function makeImagePublic($id) {
        $header = [
            'Content-Type: application/json;charset=utf-8',
            "Authorization: Bearer $this->user_token",            
        ];

        $postFields = json_encode([
            "file"   => $id,
        ]);

        $api = new \FintechSystems\LaravelApiHelpers\Api;
        
        return $api->post(
            'https://slack.com/api/files.sharedPublicURL',
            $postFields,
            $header,
        );        
    }

    /**
     * Take a 0 based index files[0] array and extract permalink_public and the title
     * to reconstruct a new URL that will be publically accessible. The problem
     * with the permalink_public is that it's a website not a URL
     */
    public function reconstructImageUrl($filesZero) {        
        preg_match_all("/\/.+\/(.+)-(.+)-(.+)/", $filesZero['permalink_public'], $matches);

        $teamId    = $matches[1][0];
        $fileId    = $matches[2][0];
        $pubSecret = $matches[3][0];        
        $fileTitle = str_replace(' ', '_', $filesZero['title']);

        return "https://files.slack.com/files-pri/$teamId-$fileId/$fileTitle?pub_secret=$pubSecret";
    }
    
}
