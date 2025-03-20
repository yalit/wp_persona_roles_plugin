<?php

namespace model\dataTransformer;

use model\Persona;

class ImportToPersonaDataTransformer extends GenericDataTransformer
{
    public static function transform(array $data): ?Persona
    {
        $surname = static::getProperty($data, 'nom', "");
        $name = static::getProperty($data, 'prenom', "");
        if (!($surname && $name)) {
            return null;
        }

        return new Persona(
            null,
            sanitize_text_field(static::getProperty($data, 'civilite', "")),
            sanitize_text_field($surname),
            sanitize_text_field($name),
            sanitize_text_field(static::getProperty($data, 'adresse_mail', "")),
            sanitize_text_field(static::getProperty($data, 'telfixe', "")),
            sanitize_text_field(static::getProperty($data, 'tel', "")),
            sanitize_text_field(static::getProperty($data, 'adresse_postale', ""))." ".sanitize_text_field(static::getProperty($data, 'cpos', "")).' '.sanitize_text_field(static::getProperty($data, 'localite', "")),
            sanitize_textarea_field(static::getProperty($data, 'fonction', "")),
            intval(sanitize_text_field(static::getProperty($data, 'actif', "0"))) === 1,
            "",
            sanitize_text_field(static::getProperty($data, 'id', "")),
        );
    }
}
