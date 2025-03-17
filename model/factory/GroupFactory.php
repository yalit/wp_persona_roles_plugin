<?php

class GroupFactory
{
    public static function createFromPost(WP_Post $post): Group
    {
        return new Group(
            $post->ID, 
            get_post_meta( $post->ID, GroupType::getFieldDBId('name'), true),
            get_post_meta( $post->ID, GroupType::getFieldDBId('code'), true)
        );
    }
}