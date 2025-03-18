<?php

namespace shortcode;

use model\Affectation;
use model\DisplayData;
use shortcode\Enum\ContentEnum;

class AffectationDisplay
{

    public static function getParish(Affectation $affectation, DisplayData $display): string
    {
        return sprintf(
            "<div class=\"persona_parish %s\">%s</div>", 
            static::getClassInfo(ContentEnum::Parish, $display), 
            $affectation->parish->name,
        );
    }

    public static function getImage(Affectation $affectation, DisplayData $display): string
    {
        return sprintf(
            "<div class=\"persona_image %s\"><img src=\"%s\" /></div>", 
            static::getClassInfo(ContentEnum::Image, $display), 
            $affectation->persona->imagePath
        );
    }

    public static function getName(Affectation $affectation, DisplayData $display): string
    {
        return sprintf(
            "<div class=\"persona_name %s\">%s %s</div>", 
            static::getClassInfo(ContentEnum::Name, $display), 
            $affectation->persona->name, 
            $affectation->persona->surname
        );
    }

    public static function getAddress(Affectation $affectation, DisplayData $display): string
    {
        return sprintf(
            "<div class=\"persona_address %s\">%s</div>", 
            static::getClassInfo(ContentEnum::Address, $display), 
            $affectation->persona->address,
        );
    }

    public static function getRole(Affectation $affectation, DisplayData $display): string
    {
        return sprintf(
            "<div class=\"persona_role %s\">%s</div>", 
            static::getClassInfo(ContentEnum::Role, $display), 
            $affectation->role->name,
        );
    }

    public static function getPhone(Affectation $affectation, DisplayData $display): string
    {
        return sprintf(
            "<div class=\"persona_phone flex %s\"><span class=\"icon\">%s</span>%s</div>", 
            static::getClassInfo(ContentEnum::Phone, $display), 
            SVG::phone(),
            $affectation->persona->phone,
        );
    }

    public static function getMobile(Affectation $affectation, DisplayData $display): string
    {
        return sprintf(
            "<div class=\"persona_mobile flex %s\"><span class=\"icon\">%s</span>%s</div>", 
            static::getClassInfo(ContentEnum::Phone, $display), 
            SVG::mobile(),
            $affectation->persona->mobile,
        );
    }

    public static function getEmail(Affectation $affectation, DisplayData $display): string
    {
        return sprintf(
            "<div class=\"persona_email flex %s\"><span class=\"icon\">%s</span><a href=\"mailto:%s\">%s</a></div>", 
            static::getClassInfo(ContentEnum::Email, $display), 
            SVG::envelop(),
            $affectation->persona->email,
            $affectation->persona->email
        );
    }

    private static function getClassInfo(ContentEnum $enum, DisplayData $display): string
    {
        $r = [];

        if ($display->isBold($enum)) {
            $r[] = "bold";
        }

        if ($display->isUnderlined($enum)) {
            $r[] = "underlined";
        }

        if ($display->isItalic($enum)) {
            $r[] = "italic";
        }

        return join(" ", $r);
    }
}
