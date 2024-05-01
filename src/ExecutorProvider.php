<?php

namespace Xtompie\Aspect;

interface ExecutorProvider
{
    public function provide(Advice $advice): Executor;

}
