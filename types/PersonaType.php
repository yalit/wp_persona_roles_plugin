<?php

namespace types;

class PersonaType extends AbstractType
{

    public static function getPostType(): string 
    {
        return 'persona';
    }

    public static function getName(): string 
    { 
        return "Personne";
    }

    public static function getFields(): array 
    { 
        return [
            'civilite' => ['Civilité', 'choice', ["", 'M.', 'Mme', "Abbé", "Père", "Diacre"]],
            'name' => ['Prénom', 'text'],
            'surname' => ['Nom', 'text'],
            'email' => ['E-mail', 'email'],
            'phone' => ['Fixe', 'text'],
            'mobile' => ['Mobile', 'text'],
            'address' => ['Adresse', 'text'],
            'rgpd' => ['RGPD', 'boolean'],
            'picture' => ['Photo', 'file']
        ];
    }

    public static function getPostTitle($postData): string 
    { 
        return sanitize_text_field($postData[static::getFieldId('name')])." ".sanitize_text_field($postData[static::getFieldId('surname')]);
    }

    public function __construct() 
    {
        parent::__construct();
        add_action( 'post_edit_form_tag', [$this, 'updateFormTag']);
    }

    public function updateFormTag($post)
    {
        if ($post->post_type === static::getPostType()) {
            echo " enctype=\"multipart/form-data\"";
        }
    }

    public function addColumns($columns): array {
        unset($columns['date']);
        return array_merge($columns, [
            'email' => __('Email', 'persona-user-roles'),
            'phone' => __('Téléphones', 'persona-user-roles'),
            'adress' => __('Adresse', 'persona-user-roles'),
            'rgpd' => __('RGPD ?', 'persona-user-roles'),
        ]);
    }

    public function displayColumns ($column_key, $post_id): void {
        if ($column_key == 'email') {
            $email = get_post_meta($post_id, '_persona_email', true);
            ?>
            <span><?php echo esc_attr($email); ?></span>
        <?php
        }

        if ($column_key == 'phone') {
            $phone = get_post_meta($post_id, '_persona_phone', true);
            $mobile = get_post_meta($post_id, '_persona_mobile', true);
            ?>
            <div>Fixe : <?php echo esc_attr($phone); ?></div>
            <div>Mobile : <?php echo esc_attr($mobile); ?></div>
        <?php
        }

        if ($column_key == 'adress') {
            $adress = get_post_meta($post_id, '_persona_address', true);
            ?>
            <span><?php echo esc_attr($adress); ?></span>
        <?php
        }

        if ($column_key == 'rgpd') {
            $rgpd = get_post_meta($post_id, '_persona_rgpd', true);
            ?>
            <span><?php echo (!empty($rgpd) ? "OUI" : "NON"); ?></span>
        <?php
        }
    }
}
