<?php

class Parish
{
    public function __construct(
        public ?string $id,
        public string $name,
        public string $code,
        public string $sequence = "0",
        public ?string $legacyId = null,
    ) {}
}
