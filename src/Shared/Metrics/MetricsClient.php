<?php
declare(strict_types=1);

namespace App\Shared\Metrics;

use Socket;
use UnitEnum;

class MetricsClient implements MetricsClientInterface
{
    /**
     * @var Socket|false|resource
     */
    private Socket $socket;

    /**
     * @param string $host
     * @param int $port
     */
    public function __construct(string $host, int $port)
    {
        $this->socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
        socket_connect($this->socket, $host, $port);
    }

    /**
     *
     */
    public function __destruct()
    {
        socket_close($this->socket);
    }

    /**
     * @param string|UnitEnum $measurement
     * @param float $ms
     * @param array $tags
     * @param int|null $timestampNs
     * @return void
     */
    public function timing(string|UnitEnum $measurement, float $ms, array $tags = [], int|null $timestampNs = null): void
    {
        $this->send($measurement, ['value' => $ms], $tags, $timestampNs);
    }

    /**
     * @param string|UnitEnum $measurement
     * @param int $value
     * @param array $tags
     * @param int|null $timestampNs
     * @return void
     */
    public function increment(string|UnitEnum $measurement, int $value = 1, array $tags = [], int|null $timestampNs = null): void
    {
        $this->send($measurement, ['value' => $value], $tags, $timestampNs);
    }

    /**
     * @param string|UnitEnum $measurement
     * @param array $fields
     * @param array $tags
     * @param int|null $timestampNs
     * @return void
     */
    private function send(string|UnitEnum $measurement, array $fields, array $tags, int|null $timestampNs): void
    {
        $line = $this->buildLine($measurement, $fields, $tags, $timestampNs);
        @socket_send($this->socket, $line, strlen($line), 0);
    }

    /**
     * @param string|UnitEnum $measurement
     * @param array $fields
     * @param array $tags
     * @param int|null $timestampNs
     * @return string
     */
    private function buildLine(string|UnitEnum $measurement, array $fields, array $tags, int|null $timestampNs): string
    {
        $line = $this->escape($this->resolveValue($measurement));

        foreach ($tags as $k => $v) {
            $line .= ',' . $this->escape($k) . '=' . $this->escape($this->resolveValue($v));
        }

        $fieldParts = [];
        foreach ($fields as $k => $v) {
            if (is_float($v)) {
                $fieldParts[] = $this->escape($k) . '=' . $v;
            } else {
                $fieldParts[] = $this->escape($k) . '=' . (int) $v;
            }
        }

        $line .= ' ' . implode(',', $fieldParts);

        if ($timestampNs !== null) {
            $line .= ' ' . $timestampNs;
        }

        return $line;
    }

    /**
     * @param string $value
     * @return string
     */
    private function escape(string $value): string
    {
        return str_replace(
            [' ', ',', '='],
            ['\ ', '\,', '\='],
            $value
        );
    }

    /**
     * @param mixed $value
     * @return string
     */
    private function resolveValue(mixed $value): string
    {
        return $value instanceof UnitEnum ? (string) $value->value : (string) $value;
    }
}
