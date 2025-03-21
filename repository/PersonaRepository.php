<?php

namespace repository;

use model\factory\PersonaFactory;
use model\Persona;
use pages\AffectationImporterPage;
use types\PersonaType;

class PersonaRepository extends AbstractRepository
{

    public static function find(string $PersonaID): ?Persona
    {
        $post = get_post($PersonaID);

        if (!$post) {
            return null;
        }

        return PersonaFactory::createFromPost($post);
    }

    public static function findFromLegacyId(string $legacyId): ?Persona
    {
        $args = [
            'post_type' => PersonaType::getPostType(),
            'meta_query' => [
                [
                    'key' => PersonaType::getFieldDBId(AffectationImporterPage::LEGACY_ID_FIELD_NAME),
                    'value' => $legacyId,
                    'compare' => '='
                ]
            ]
        ];

        return static::queryPost($args, PersonaFactory::class);
    }

    public static function save(Persona $persona): void
    {
        $postId = "";
        if (! $persona->id) {
            $postId = static::createPost(sprintf("%s %s", $persona->name, $persona->surname), PersonaType::getPostType());
        }

        if($persona->name) {
            update_post_meta($postId, PersonaType::getFieldDBId('name'), $persona->name);
        }
        if($persona->surname) {
            update_post_meta($postId, PersonaType::getFieldDBId('surname'), $persona->surname);
        }
        if($persona->civilite && $persona->civilite !== "") {
            update_post_meta($postId, PersonaType::getFieldDBId('civilite'), $persona->civilite);
        }
        if($persona->email && $persona->email !== "") {
            update_post_meta($postId, PersonaType::getFieldDBId('email'), $persona->email);
        }
        if($persona->phone && $persona->phone !== "") {
            update_post_meta($postId, PersonaType::getFieldDBId('phone'), $persona->phone);
        }
        if($persona->mobile && $persona->mobile !== "") {
            update_post_meta($postId, PersonaType::getFieldDBId('mobile'), $persona->mobile);
        }
        if($persona->address && $persona->address !== "") {
            update_post_meta($postId, PersonaType::getFieldDBId('address'), $persona->address);
        }
        if($persona->rgpd && $persona->rgpd !== "") {
            update_post_meta($postId, PersonaType::getFieldDBId('rgpd'), $persona->rgpd);
        }
        if($persona->function && $persona->function !== "") {
            update_post_meta($postId, PersonaType::getFieldDBId('function'), $persona->function);
        }
        if($persona->imagePath && $persona->imagePath !== "") {
            update_post_meta($postId, PersonaType::getFieldDBId('picture'), $persona->imagePath);
        }
        if($persona->legacyId && $persona->legacyId !== "") {
            update_post_meta($postId, PersonaType::getFieldDBId(AffectationImporterPage::LEGACY_ID_FIELD_NAME), $persona->legacyId);
        }
    }
}
