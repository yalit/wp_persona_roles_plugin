<?php

namespace shortcode;

use model\DisplayData;
use repository\AffectationRepository;
use shortcode\Enum\FormatEnum;

class Shortcode
{
    public const NAME = 'ups-select';

    public static function init()
    {
        return new self();
    }

    public function __construct()
    {
        add_shortcode(self::NAME, [$this, "display"]);
    }

    public function display($atts = [], $content = null, $tag = "") 
    {
        $mandatoryAttributes = ['format', 'contenu'];
        foreach ($mandatoryAttributes as $attribute) {
            if (!array_key_exists($attribute, $atts)) {
                return '';
            }
        }

        $format = FormatEnum::from($this->getAttribute($atts, 'format', 'TABLEAU'));
        $affectations = $this->getAffectations($atts);
        $displayData = $this->getDisplayData($atts);

        return match($format) {
            FormatEnum::Table => UPSTable::build($affectations, $displayData),
            FormatEnum::Card => UPSVisitCard::build($affectations, $displayData),
            default => ''
        };
    }

    private function getAffectations($atts): array
    {
        return AffectationRepository::findFiltered(
            $this->getAttribute($atts, 'paroisse', null),
            $this->getAttribute($atts, 'groupe', null),
        );
    }

    private function getDisplayData($atts): DisplayData
    {
        return new DisplayData(
            $this->getAttribute($atts, "contenu"),
            $this->getAttribute($atts, "gras", ""),
            $this->getAttribute($atts, "souligne", ""),
            $this->getAttribute($atts, "italique", ""),
            
        );
    }

    private function getAttribute($atts, $attribute, $defaultValue = null)
    {
        if (array_key_exists($attribute, $atts)) {
            return $atts[$attribute];
        }
        return $defaultValue;
    }
}
