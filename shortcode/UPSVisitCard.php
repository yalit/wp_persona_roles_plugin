<?php

namespace shortcode;

use model\Affectation;
use model\DisplayData;
use shortcode\Enum\ContentEnum;

class UPSVisitCard
{
    /**
     * @param array<Affectation> $affectations
     */
    public static function build(array $affectations, DisplayData $displayData): string
    {
        $t = '<div class="persona_data card">';

        foreach($affectations as $affectation) {
            $t .= static::buildcardRow($affectation, $displayData);
        }

        $t .= '</div>';

        return $t;
    }

    private static function buildcardRow(Affectation $affectation, DisplayData $displayData): string
    {
        $r = '<div class="persona_data_row">';
        
        // Parish
            $r .= "<div class=\"persona_data_col col_parish ".AffectationDisplay::filledClass($displayData, ContentEnum::Parish)."\">";
        if ($displayData->isDisplayed(ContentEnum::Parish)) {
            $r .= AffectationDisplay::getParish($affectation, $displayData);
        }
        $r .= '</div>';

        // Image
        $r .= "<div class=\"persona_data_col col_image ".AffectationDisplay::filledClass($displayData, ContentEnum::Image)."\">";
        if ($displayData->isDisplayed(ContentEnum::Image)) {
            $r .= AffectationDisplay::getImage($affectation, $displayData);
        }
        $r .= '</div>';

        $r .= "<div class=\"persona_data_col col_data filled\">";
        // Role
        if ($displayData->isDisplayed(ContentEnum::Role) || $displayData->isDisplayed(ContentEnum::Name)) {
            $r .= AffectationDisplay::getName($affectation, $displayData);
            $r .= AffectationDisplay::getRole($affectation, $displayData);
        }
        

        // adresse
        if ($displayData->isDisplayed(ContentEnum::Address)) {
            $r .= AffectationDisplay::getAddress($affectation, $displayData);
        }

        // phone + mobile
        if ($displayData->isDisplayed(ContentEnum::Phone)) {
            if ($affectation->persona->mobile) {
                $r .= AffectationDisplay::getMobile($affectation, $displayData);
            }

            if ($affectation->persona->phone) {
                $r .= AffectationDisplay::getPhone($affectation, $displayData);
            }
        }

        // email
        if ($displayData->isDisplayed(ContentEnum::Email)) {
            $r .= AffectationDisplay::getEmail($affectation, $displayData);
        }

        $r .= '</div></div>';
        return $r;
    }
}
