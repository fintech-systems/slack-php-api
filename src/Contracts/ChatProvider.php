<?php

namespace FintechSystems\Slack\Contracts;

interface ChatProvider
{
    public function postMessage(String $postData);
}
