<?php

namespace App\Broadcasting;

use App\Models\User;

class ResultLiveScoreChannel
{
    /**
     * Create a new channel instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Authenticate the user's access to the channel.
     */
    public function join($code, $codeClient): array|bool
    {
        //
        return $code === $codeClient;
    }
}
