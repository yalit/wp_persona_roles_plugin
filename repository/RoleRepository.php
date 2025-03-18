<?php

namespace repository;

use model\factory\RoleFactory;
use model\Role;
use pages\AffectationImporterPage;
use types\RoleType;

class RoleRepository extends AbstractRepository
{
    public static function find(string $RoleID): ?Role
    {
        $post = get_post($RoleID);

        if (!$post) {
            return null;
        }

        return RoleFactory::createFromPost($post);
    }

    public static function findFromCode(string $RoleCode): ?Role
    {
        $args = [
            'post_type' => RoleType::getPostType(),
            'meta_query' => [
                [
                    'key' => RoleType::getFieldDBId('code'),
                    'value' => $RoleCode,
                    'compare' => '='
                ]
            ]
        ];

        return static::queryPost($args, RoleFactory::class);
    }

    /** @return array<Role> */
    public static function findAll(): array
    {
        $args = [
            'post_type' => RoleType::getPostType(),
            'nb_per_page' => -1,
            'orderby' => 'meta_value',
            'order' => 'ASC',
            'meta_key' => RoleType::getFieldDBId('name')
        ];

        return static::queryPosts($args, RoleFactory::class);
    }

    public static function findFromLegacyId(string $legacyId): ?Role
    {
        $args = [
            'post_type' => RoleType::getPostType(),
            'meta_query' => [
                [
                    'key' => RoleType::getFieldDBId(AffectationImporterPage::LEGACY_ID_FIELD_NAME),
                    'value' => $legacyId,
                    'compare' => '='
                ]
            ]
        ];

        return static::queryPost($args, RoleFactory::class);
    }

    public static function save(Role $role): void
    {
        $postId = "";
        if (!$role->id) {
            $postId = static::createPost(sprintf("%s", $role->name), RoleType::getPostType());
        }

        if ($role->name) {
            update_post_meta($postId, RoleType::getFieldDBId('name'), $role->name);
        }
        if ($role->code) {
            update_post_meta($postId, RoleType::getFieldDBId('code'), $role->code);
        }
        if ($role->description && $role->description !== "") {
            update_post_meta($postId, RoleType::getFieldDBId('description'), $role->description);
        }
        if ($role->sequence && $role->sequence !== "") {
            update_post_meta($postId, RoleType::getFieldDBId('sequence'), $role->sequence);
        }
        if($role->legacyId && $role->legacyId !== "") {
            update_post_meta($postId, RoleType::getFieldDBId(AffectationImporterPage::LEGACY_ID_FIELD_NAME), $role->legacyId);
        }
    }
}
