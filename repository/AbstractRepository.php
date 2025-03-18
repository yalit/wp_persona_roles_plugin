<?php

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
}
