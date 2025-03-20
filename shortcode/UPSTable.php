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
        $t = '<div class="persona_data table ">';

        foreach($affectations as $affectation) {
            $t .= static::buildTableRow($affectation, $displayData);
        }

        $t .= '</div>';

        return $t;
    }

    private static function buildTableRow(Affectation $affectation, DisplayData $displayData): string
    {
        $r = '<div class="persona_data_row" data-id="'.$affectation->id.'">';

        // Role
        $r .= "<div class=\"persona_data_col col_role ".AffectationDisplay::filledClass($displayData, ContentEnum::Role)."\">";
        if ($displayData->isDisplayed(ContentEnum::Role) ) {
            $r .= AffectationDisplay::getRole($affectation, $displayData);
        }

        $r .= '</div>';
        // Image
        $r .= "<div class=\"persona_data_col col_image ".AffectationDisplay::filledClass($displayData, ContentEnum::Image)."\">";
        if ($displayData->isDisplayed(ContentEnum::Image)) {
            $r .= AffectationDisplay::getImage($affectation, $displayData);
        }
        $r .= '</div>';

        // Role
        $r .= "<div class=\"persona_data_col col_name_function ".AffectationDisplay::filledClass($displayData, ContentEnum::Function)." ".AffectationDisplay::filledClass($displayData, ContentEnum::Name)."\">";
        if ($displayData->isDisplayed(ContentEnum::Name)) {
            $r .= "<div class=\"flex\">";
            if ($displayData->isDisplayed(ContentEnum::Civility)) {
                $r .= AffectationDisplay::getCivilite($affectation, $displayData);
            }
            $r .= AffectationDisplay::getName($affectation, $displayData);
            $r .= '</div>';
        }
        if ($displayData->isDisplayed(ContentEnum::Function)) {
            $r .= AffectationDisplay::getFunction($affectation, $displayData);
        }

        $r .= '</div>';

        // adresse
        $r .= "<div class=\"persona_data_col col_address ".AffectationDisplay::filledClass($displayData, ContentEnum::Address)."\">";
        if ($displayData->isDisplayed(ContentEnum::Address)) {
            $r .= AffectationDisplay::getAddress($affectation, $displayData);
        }
        $r .= '</div>';

        // phone + mobile
        $r .= "<div class=\"persona_data_col col_phone ".AffectationDisplay::filledClass($displayData, ContentEnum::Phone)."\">";
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
        $r .= "<div class=\"persona_data_col col_email ".AffectationDisplay::filledClass($displayData, ContentEnum::Email)."\">";
        if ($displayData->isDisplayed(ContentEnum::Email)) {
            $r .= AffectationDisplay::getEmail($affectation, $displayData);
        }
        $r .= '</div>';

        $r .= '</div>';
        return $r;
    }

}
