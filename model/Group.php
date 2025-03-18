<?php

class Group
{
    public function __construct(
        public ?string $id,
        public string $name,
        public string $code,
        public bool $active = false,
        public string $sequence = '1',
        public ?string $legacyId = null,
    ) {}
}
