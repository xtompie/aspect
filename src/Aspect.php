<?php

namespace Xtompie\Aspect;

use ReflectionAttribute;
use ReflectionMethod;

class Aspect
{
    public function __construct(
        protected ExecutorProvider $executorProvider,
        protected array $advices = [],
    ){
    }

    public function __invoke(string $method, array $args, callable $main): mixed
    {
        return (new Invocation(
            executorProvider: $this->executorProvider,
            advices: $this->advices($method),
            method: $method,
            args: $args,
            main: $main,
        ))();
    }

    protected function advices(string $method): array
    {
        if (!array_key_exists($method, $this->advices)) {
            [$className, $methodName] = explode('::', $method);
            $reflection = new ReflectionMethod($className, $methodName);
            $advices = $reflection->getAttributes(Advice::class, ReflectionAttribute::IS_INSTANCEOF);
            $advices = array_map(fn ($attribute) => $attribute->newInstance(), $advices);
            $this->advices[$method] = $advices;
        }

        return $this->advices[$method];
    }
}

