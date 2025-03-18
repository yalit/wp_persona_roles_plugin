<?php

namespace dataTransformer;

use Affectation;
use GroupRepository;
use ParishRepository;
use PersonaRepository;
use RoleRepository;

class ImportToAffectationDataTransformer extends GenericDataTransformer
{
    public static function transform(array $data): ?Affectation
    {
        return new Affectation(
            null,
            PersonaRepository::findFromLegacyId(sanitize_text_field(static::getProperty($data, 'id_personne', ""))),
            ParishRepository::findFromLegacyId(sanitize_text_field(static::getProperty($data, 'id_paroisse', ""))),
            GroupRepository::findFromLegacyId(sanitize_text_field(static::getProperty($data, 'id_groupe', ""))),
            RoleRepository::findFromLegacyId(sanitize_text_field(static::getProperty($data, 'id_role', ""))),
            intval(sanitize_text_field(static::getProperty($data, 'seq_affectation', ""))),
        );
    }
}
