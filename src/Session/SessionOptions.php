<?php

namespace CMIS\Session;

class SessionOptions
{
    /**
     * Session options (forwarded as Guzzle options).
     * @var array<string, mixed>
     */
    private array $options;

    /**
     * @param array<string, mixed> $options
     */
    public function __construct(array $options = ["verify" => true])
    {
        $this->options = $options;
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return SessionOptions
     */
    public function setOption(string $name, mixed $value): static
    {
        $this->options[$name] = $value;
        return $this;
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function getOption(string $name): mixed
    {
        return $this->options[$name] ?? null;
    }

    /**
     * @return array<string, mixed>
     */
    public function getOptions(): array
    {
        return $this->options;
    }
}
