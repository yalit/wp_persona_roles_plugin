<?php

namespace shortcode\Enum;

enum ContentEnum: string
{
    case Parish = 'P';
    case Group = 'G';
    case Role = 'R';
    case Image = 'I';
    case Name = "N";
    case Address = "A";
    case Phone = "T";
    case Email = "E";
    case Order = "O";

    public function display() {
        return match($this) {
            self::Address => 'Adresse',
            self::Email => 'E-mail',
            self::Group => 'Groupe',
            self::Parish => 'Paroisse',
            self::Role => 'Role',
            self::Image => 'Image',
            self::Name => 'Nom/Prénom',
            self::Phone => 'Téléphones',
            default => ''
        };
    }
}
