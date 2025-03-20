<?php

namespace repository;

use WP_Query;
use WP_Post;

class AbstractRepository
{
    protected static function createPost(string $postTitle, string $postType): int
    {
        $args = [
            'post_title' => $postTitle,
            'post_type' => $postType,
            'post_status' => 'publish',
        ];

        return wp_insert_post($args);
    }

    protected static function queryPost(array $args, string $factoryClass)
    {
        $posts = (new WP_Query($args))->get_posts();

        if (count($posts) !== 1) {
            return null;
        }

        $post = $posts[0];
        return $factoryClass::createFromPost($post);
    }

    protected static function queryPosts(array $args, string $factoryClass)
    {
        $posts = (new WP_Query($args))->get_posts();

        return array_map(fn($post) => $factoryClass::createFromPost($post), $posts);
    }
}
