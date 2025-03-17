<?php

class DisplayData
{
    public function __construct(
        private string $content = "",
        private string $bold = "",
        private string $underlined = "", 
        private string $italic = ""
    ){}

    public function isDisplayed(ContentEnum $enum): bool
    {
        return str_contains( $this->content, $enum->value);
    }

    public function isBold(ContentEnum $enum): bool
    {
        return str_contains( $this->bold, $enum->value);
    }

    public function isUnderlined(ContentEnum $enum): bool
    {
        return str_contains( $this->underlined, $enum->value);
    }

    public function isItalic(ContentEnum $enum): bool
    {
        return str_contains( $this->italic, $enum->value);
    }
}