<?php

namespace model;

class Persona
{
    public function __construct(
        public ?string $id,
        public string $civilite = "",
        public string $name,
        public string $surname,
        public string $email,
        public string $phone = "",
        public string $mobile = "",
        public string $address = "",
        public string $function = "",
        public bool $rgpd = false,
        public string $imagePath = "",
        public ?string $legacyId = null
    ){}
}
