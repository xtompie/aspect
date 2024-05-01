<?php

declare(strict_types=1);

namespace Xtompie\Aspect;

class Invocation
{
    public function __construct(
        protected ExecutorProvider $executorProvider,
        protected array $advices,
        protected string $method,
        protected array $args,
        protected $main,
    ) {
    }

    public function __invoke(): mixed
    {
        if ($this->advices) {
            $new = clone $this;
            $advice = array_pop($new->advices);
            $executor = $this->executorProvider->provide(advice: $advice);
            return $executor->execute(advice: $advice, invocation: $new);
        }

        return ($this->main)(...$this->args);
    }

    public function method(): string
    {
        return $this->method;
    }

    public function args(): array
    {
        return $this->args;
    }

    public function withArgs(array $args): static
    {
        $new = clone $this;
        $new->args = $args;
        return $new;
    }

    public function hash(): string
    {
        return sha1(serialize([$this->method(), $this->args()]));
    }
}

