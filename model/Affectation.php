<?php

namespace model;

use shortcode\Enum\ContentEnum;

class Affectation
{
    public function __construct(
        public ?string $id,
        public ?Persona $persona,
        public ?Parish $parish,
        public ?Group $group,
        public ?Role $role,
        public int $order,
    ) {}

    public function isEqual(Affectation $a, ContentEnum $enum): bool
    {
        return match($enum) {
            ContentEnum::Address => $this->persona->address === $a->persona->address,
            ContentEnum::Email => $this->persona->email === $a->persona->email,
            ContentEnum::Group => $this->group->name === $a->group->name,
            ContentEnum::Name => $this->persona->name.$this->persona->surname === $a->persona->name.$a->persona->surname,
            ContentEnum::Parish => $this->parish->name === $a->parish->name,
            ContentEnum::Phone => $this->persona->phone === $a->persona->phone,
            ContentEnum::Role => $this->role->name === $a->role->name,
            ContentEnum::Order => $this->order === $a->order,
            default => true
        };
    }

    public function isLower(Affectation $a, ContentEnum $enum): bool
    {
        return match($enum) {
            ContentEnum::Address => $this->persona->address < $a->persona->address,
            ContentEnum::Email => $this->persona->email < $a->persona->email,
            ContentEnum::Group => $this->group->name < $a->group->name,
            ContentEnum::Name => $this->persona->name.$this->persona->surname < $a->persona->name.$a->persona->surname,
            ContentEnum::Parish => $this->parish->name < $a->parish->name,
            ContentEnum::Phone => $this->persona->phone < $a->persona->phone,
            ContentEnum::Role => $this->role->name < $a->role->name,
            ContentEnum::Order => $this->order < $a->order,
            default => true
        };
    }
}
