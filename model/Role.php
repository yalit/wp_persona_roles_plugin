<?php

namespace model;

class Role
{
    public function __construct(
        public ?string $id,
        public string $name,
        public string $code,
        public string $description,
        public bool $active = false,
        public ?string $sequence = null,
        public ?string $legacyId = null,
    ) {}
}
