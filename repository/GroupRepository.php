<?php

class GroupRepository extends AbstractRepository
{
    public static function find(string $groupID): ?Group
    {
        $post = get_post($groupID);

        if (!$post) {
            return null;
        }

        return GroupFactory::createFromPost($post);
    }

    public static function findFromCode(string $groupCode): ?Group
    {
        $args = [
            'post_type' => GroupType::getPostType(),
            'meta_query' => [
                [
                    'key' => GroupType::getFieldDBId('code'),
                    'value' => $groupCode,
                    'compare' => '='
                ]
            ]
        ];

        return static::queryPost($args, GroupFactory::class);
    }

    /** @return array<Group> */
    public static function findAll(): array
    {
        $args = [
            'post_type' => GroupType::getPostType(),
            'nb_per_page' => -1,
            'orderby' => 'meta_value',
            'order' => 'ASC',
            'meta_key' => GroupType::getFieldDBId('name')
        ];

        return static::queryPosts($args, GroupFactory::class);
    }

    public static function findFromLegacyId(string $legacyId): ?Group
    {
        $args = [
            'post_type' => GroupType::getPostType(),
            'meta_query' => [
                [
                    'key' => GroupType::getFieldDBId(AffectationImporterPage::LEGACY_ID_FIELD_NAME),
                    'value' => $legacyId,
                    'compare' => '='
                ]
            ]
        ];

        return static::queryPost($args, GroupFactory::class);
    }

    public static function save(Group $group): void
    {
        $postId = "";
        if (!$group->id) {
            $postId = static::createPost(sprintf("%s", $group->name), GroupType::getPostType());
        }

        if ($group->name) {
            update_post_meta($postId, GroupType::getFieldDBId('name'), $group->name);
        }
        if ($group->code) {
            update_post_meta($postId, GroupType::getFieldDBId('code'), $group->code);
        }
        if ($group->active && $group->active !== "") {
            update_post_meta($postId, GroupType::getFieldDBId('active'), $group->active);
        }
        if ($group->sequence && $group->sequence !== "") {
            update_post_meta($postId, GroupType::getFieldDBId('sequence'), $group->sequence);
        }
        if($group->legacyId && $group->legacyId !== "") {
            update_post_meta($postId, GroupType::getFieldDBId(AffectationImporterPage::LEGACY_ID_FIELD_NAME), $group->legacyId);
        }
    }
}
