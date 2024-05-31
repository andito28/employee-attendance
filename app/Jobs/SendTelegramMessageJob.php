<?php

namespace App\Jobs;

use App\ThirdParty\Telegram\Telegram;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendTelegramMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    /**
     * @param string $bootToken
     * @param string $roomChatId
     * @param string $message
     *
     * @return void
     */
    public function __construct(public string $bootToken, public string $roomChatId, public string $message)
    {
    }


    public function handle()
    {
        $telegram = new Telegram($this->bootToken, $this->roomChatId);
        $telegram->send($this->message);
    }
}
