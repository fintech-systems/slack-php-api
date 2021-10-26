<?php

namespace FintechSystems\Slack;

use Illuminate\Support\ServiceProvider;

class SlackServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/slack.php'
            => config_path('slack.php'), 'slack-config'
        ]);
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/slack.php',
            'slack'
        );

        $this->app->bind('slack', function () {
            return new Slack([
                'bot_token'  => config('slack.bot_token'),
                'user_token' => config('slack.user_token'),
                'channel'    => config('slack.channel'),
            ]);
        });
    }
}
