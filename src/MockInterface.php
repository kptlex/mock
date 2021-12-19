<?php

declare(strict_types=1);

namespace Lex\Mock;

interface MockInterface
{
    public function acquire(array $keys): bool;

    public function isAcquired(array $keys): bool;

    public function release(array $keys): bool;
}
