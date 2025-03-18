<?php

namespace dataTransformer;

use Parish;

class ImportToParishDataTransformer extends GenericDataTransformer
{
    public static function transform(array $data): ?Parish
    {
        return new Parish(
            null,
            sanitize_text_field(static::getProperty($data, 'nom_paroisse', "")),
            sanitize_text_field(static::getProperty($data, 'code_paroisse', "")),
            sanitize_text_field(static::getProperty($data, 'seq_paroisse', "")),
            sanitize_text_field(static::getProperty($data, 'id', "")),
        );
    }
}
