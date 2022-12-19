<?php

namespace CMIS\Session;

class SessionOptions
{
    /**
     * Session options.
     * @var array
     */
    private array $options = [
        "verify" => true,
    ];

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
        return $this->options[$name];
    }
}