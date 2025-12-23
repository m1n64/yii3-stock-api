<?php
declare(strict_types=1);

namespace App\Shared\Logger;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Level;
use Monolog\LogRecord;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class OpenObserveMonologHandler extends AbstractProcessingHandler
{
    /**
     * @var \Socket
     */
    private \Socket $socket;

    /**
     * @param string $host
     * @param int $port
     * @param int|string|Level $level
     * @param bool $bubble
     */
    public function __construct(string $host, int $port, int|string|Level $level = Level::Debug, bool $bubble = true)
    {
        $this->socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
        socket_set_option($this->socket, SOL_SOCKET, SO_SNDTIMEO, ['sec' => 0, 'usec' => 10000]);
        socket_connect($this->socket, $host, $port);

        parent::__construct($level, $bubble);
    }

    /**
     *
     */
    public function __destruct()
    {
        socket_close($this->socket);
    }

    /**
     * @param LogRecord $record
     * @return void
     */
    protected function write(LogRecord $record): void
    {
        $body = '[' . $record->formatted . ']';

        socket_write($this->socket, $body, strlen($body));
    }
}
