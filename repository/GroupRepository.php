<?php

class GroupRepository
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

        $posts = (new WP_Query($args))->get_posts();

        if (count($posts) !== 1) {
            return null;
        }

        $post = $posts[0];
        return GroupFactory::createFromPost($post);
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

        $posts = (new WP_Query($args))->get_posts();

        return array_map(fn(WP_Post $post) => GroupFactory::createFromPost($post), $posts);
    }
}
