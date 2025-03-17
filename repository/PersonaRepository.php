<?php

class PersonaRepository
{

    public static function find(string $PersonaID): ?Persona
    {
        $post = get_post($PersonaID);

        if (!$post) {
            return null;
        }

        return PersonaFactory::createFromPost($post);
    }

    public static function findFromCode(string $personaCode): ?Persona
    {
        $args = [
            'post_type' => PersonaType::getPostType(),
            'meta_query' => [
                [
                    'key' => PersonaType::getFieldDBId('code'),
                    'value' => $personaCode,
                    'compare' => '='
                ]
            ]
        ];

        $posts = (new WP_Query($args))->get_posts();

        if (count($posts) !== 1) {
            return null;
        }

        $post = $posts[0];
        return PersonaFactory::createFromPost($post);
    }
}
