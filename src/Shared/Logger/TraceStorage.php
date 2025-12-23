<?php
declare(strict_types=1);

namespace App\Shared\Logger;

final class TraceStorage
{
    /**
     * @var string|null
     */
    private string|null $traceParent = null;

    /**
     * @param string $value
     * @return void
     */
    public function set(string $value): void
    {
        $this->traceParent = $value;
    }

    /**
     * @return string|null
     */
    public function get(): ?string
    {
        return $this->traceParent;
    }
}
