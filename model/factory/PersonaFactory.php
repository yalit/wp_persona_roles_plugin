<?php

class PersonaFactory
{
    public static function createFromPost(WP_Post $post): Persona
    {
        return new Persona(
            $post->ID, 
            get_post_meta( $post->ID, PersonaType::getFieldDBId('civilite'), true),
            get_post_meta( $post->ID, PersonaType::getFieldDBId('name'), true),
            get_post_meta( $post->ID, PersonaType::getFieldDBId('surname'), true),
            get_post_meta( $post->ID, PersonaType::getFieldDBId('email'), true),
            get_post_meta( $post->ID, PersonaType::getFieldDBId('phone'), true),
            get_post_meta( $post->ID, PersonaType::getFieldDBId('mobile'), true),
            get_post_meta( $post->ID, PersonaType::getFieldDBId('address'), true),
            get_post_meta( $post->ID, PersonaType::getFieldDBId('rgpd'), true),
            get_post_meta( $post->ID, PersonaType::getFieldDBId('picture'), true),

        );
    }
}