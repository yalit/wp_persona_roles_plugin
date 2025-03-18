<?php

namespace shortcode;

use model\Affectation;
use model\DisplayData;
use shortcode\Enum\ContentEnum;

class UPSTable
{
    /**
     * @param array<Affectation> $affectations
     */
    public static function build(array $affectations, DisplayData $displayData): string
    {
        $t = '<div class="persona_data table">';

        foreach($affectations as $affectation) {
            $t .= static::buildTableRow($affectation, $displayData);
        }

        $t .= '</div>';

        return $t;
    }

    private static function buildTableRow(Affectation $affectation, DisplayData $displayData): string
    {
        $r = '<div class="persona_data_row">';
        
        // Image
        $r .= "<div class=\"persona_data_col col_image\">";
        if ($displayData->isDisplayed(ContentEnum::Image)) {
            $r .= AffectationDisplay::getImage($affectation, $displayData);
        }
        $r .= '</div>';

        // Role
        $r .= "<div class=\"persona_data_col col_role-name\">";
        if ($displayData->isDisplayed(ContentEnum::Role) || $displayData->isDisplayed(ContentEnum::Name)) {
            $r .= AffectationDisplay::getName($affectation, $displayData);
            $r .= AffectationDisplay::getRole($affectation, $displayData);

            //TODO : add role description
            //$r .= sprintf("<div>%s</div>", $affectation->role->description);
        }      
        $r .= '</div>';

        // adresse
        $r .= "<div class=\"persona_data_col col_address\">";
        if ($displayData->isDisplayed(ContentEnum::Address)) {
            $r .= AffectationDisplay::getAddress($affectation, $displayData);
        }
        $r .= '</div>';

        // phone + mobile
        $r .= "<div class=\"persona_data_col col_phone\">";
        if ($displayData->isDisplayed(ContentEnum::Phone)) {
            if ($affectation->persona->mobile) {
                $r .= AffectationDisplay::getMobile($affectation, $displayData);
            }

            if ($affectation->persona->phone) {
                $r .= AffectationDisplay::getPhone($affectation, $displayData);
            }
        }
        $r .= '</div>';

        // email
        $r .= "<div class=\"persona_data_col col_email\">";
        if ($displayData->isDisplayed(ContentEnum::Email)) {
            $r .= AffectationDisplay::getEmail($affectation, $displayData);
        }
        $r .= '</div>';

        $r .= '</div>';
        return $r;
    }
}
