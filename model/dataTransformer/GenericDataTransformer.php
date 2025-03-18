<?php

namespace model\dataTransformer;

class GenericDataTransformer
{
    protected static function getProperty(array $data, string $property, string $defaultValue): string
    {
        if (array_key_exists($property, $data) && $data[$property] !== "NULL") {
            return str_replace("<NL>", "<br />", str_replace("<COMMA>", ",", $data[$property]));
        }
        return $defaultValue;
    }
}
