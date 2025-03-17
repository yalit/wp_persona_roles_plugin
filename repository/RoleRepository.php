<?php

class RoleRepository
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

        $posts = (new WP_Query($args))->get_posts();

        if (count($posts) !== 1) {
            return null;
        }

        $post = $posts[0];
        return RoleFactory::createFromPost($post);
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

        $posts = (new WP_Query($args))->get_posts();

        return array_map(fn(WP_Post $post) => RoleFactory::createFromPost($post), $posts);
    }
}
