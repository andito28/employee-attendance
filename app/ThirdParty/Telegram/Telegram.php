<?php

namespace App\ThirdParty\Telegram;

use App\Jobs\SendTelegramMessageJob;
use App\Services\Constant\Global\QueueType;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use function config;

class Telegram
{
    const BASE_URL = 'https://api.telegram.org/';

    const BOOT_TOKENS = [
        'DEVELOPMENT' => '1285522820:AAEzeBi2iz-Z0_1ua55G9jHg8EMKE8nXcSk'
    ];

    const ROOM_CHAT_IDS = [
        'DEVELOPMENT' => '-485588353'
    ];


    /**
     * @param string|null $bootToken
     * @param string|null $roomChatId
     */
    public function __construct(public string|null $bootToken = null, public string|null $roomChatId = null)
    {
        if (!$this->bootToken || !$this->roomChatId) {
            $this->fromDevelopment();
        }
    }


    /**
     * Set the boot and room chat from development for testing
     *
     * @return $this
     */
    public function fromDevelopment()
    {
        $this->bootToken = self::BOOT_TOKENS['DEVELOPMENT'];
        $this->roomChatId = self::ROOM_CHAT_IDS['DEVELOPMENT'];

        return $this;
    }

    /**
     * @param string $messages
     *
     * @return false|void
     */
    public function send(string $messages)
    {
        if (strlen($messages) <= 0) {
            return false;
        }

        $limit = 3000;
        $messageStrReplace = str_replace("\r\n", "<>", $messages);
        $messageParser = explode("<=>", wordwrap($messageStrReplace, $limit, "<=>"));
        $messageContents = str_replace("<>", "\n", $messageParser);

        if (count($messageContents) == 1) {
            $this->sendContentTelegram($messageContents[0]);
        } else {
            foreach ($messageContents as $key => $messageContent) {
                $this->sendContentTelegram($messageContents[$key]);
            }
        }
    }

    /**
     * @param string $message
     * @param bool $now
     *
     * @return void
     */
    public function queue(string $message, bool $now = true)
    {
        if ($now) {
            SendTelegramMessageJob::dispatchAfterResponse($this->bootToken, $this->roomChatId, $message);
        } else {
            SendTelegramMessageJob::dispatch($this->bootToken, $this->roomChatId, $message)
                ->onConnection(config('queue.default'))
                ->onQueue(QueueType::NOTIFICATION);
        }
    }


    /** --- FUNCTIONS --- */

    private function sendContentTelegram(string $message)
    {
        try {
            $url = self::BASE_URL . "bot$this->bootToken/sendMessage";

            $client = new Client();
            $response = $client->request("POST", $url, [
                "form_params" => [
                    'chat_id' => $this->roomChatId,
                    'text' => $message,
                    'parse_mode' => 'Markdown'
                ],
                "timeout" => 10
            ]);

            if ($response->getStatusCode() == Response::HTTP_OK) {
                return json_decode($response->getBody(), true);
            } else {
                Log::debug("Third: Telegram {$response->getStatusCode()}");
            }

            return false;
        } catch (\Exception $exception) {
            Log::error($exception);
            return false;
        }
    }

}
