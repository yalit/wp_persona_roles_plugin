<?php

class ParishRepository
{

    public static function find(string $parishID): ?Parish
    {
        $post = get_post($parishID);

        if (!$post) {
            return null;
        }

        return ParishFactory::createFromPost($post);
    }

    public static function findFromCode(string $parishCode): ?Parish
    {
        $args = [
            'post_type' => ParishType::getPostType(),
            'meta_query' => [
                [
                    'key' => ParishType::getFieldDBId('code'),
                    'value' => $parishCode,
                    'compare' => '='
                ]
            ]
        ];

        $posts = (new WP_Query($args))->get_posts();

        if (count($posts) !== 1) {
            return null;
        }

        $post = $posts[0];
        return ParishFactory::createFromPost($post);
    }

    /** @return array<Parish> */
    public static function findAll(): array
    {
        $args = [
            'post_type' => ParishType::getPostType(),
            'nb_per_page' => -1,
            'orderby' => 'meta_value',
            'order' => 'ASC',
            'meta_key' => ParishType::getFieldDBId('name')
        ];

        $posts = (new WP_Query($args))->get_posts();

        return array_map(fn(WP_Post $post) => ParishFactory::createFromPost($post), $posts);
    }
}
