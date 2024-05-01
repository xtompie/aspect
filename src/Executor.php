<?php

namespace Xtompie\Aspect;

interface Executor
{
    public function execute(Advice $advice, Invocation $invocation): mixed;
}
