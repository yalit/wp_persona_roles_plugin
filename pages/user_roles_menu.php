<?php

namespace pages;

use types\AffectationType;
use types\GroupType;
use types\ParishType;
use types\PersonaType;
use types\RoleType;

class PersonaMenu
{
    public const MENU_NAME = 'persona_menu';

    public function __construct()
    {
        add_action('admin_menu', [$this, 'addMenu']);
    }

    public static function init(): void
    {
        new self();
    }    

    public function addMenu()
    {
    if (!is_admin()) {
            return;
        }
        
        add_menu_page(
            __('Roles et Affectations'),
            __('Roles et Affectations'),
            'manage_options',
            self::MENU_NAME,
            [$this, 'displayMenu'],
            'dashicons-admin-post',
            30
        );

        add_submenu_page(
            self::MENU_NAME,
            __(AffectationType::getName()."s"),
            __(AffectationType::getName()."s"),
            'edit_posts',
            'edit.php?post_type='.AffectationType::getPostType()
        );

        add_submenu_page(
            self::MENU_NAME,
            __('+ '.AffectationType::getName()),
            __('+ '.AffectationType::getName()),
            'edit_posts',
            'post-new.php?post_type='.AffectationType::getPostType()
        );

        add_submenu_page(
            self::MENU_NAME,
            __(PersonaType::getName()."s"),
            __(PersonaType::getName()."s"),
            'edit_posts',
            'edit.php?post_type='.PersonaType::getPostType()
        );

        add_submenu_page(
            self::MENU_NAME,
            __('+ '.PersonaType::getName()),
            __('+ '.PersonaType::getName()),
            'edit_posts',
            'post-new.php?post_type='.PersonaType::getPostType()
        );

        add_submenu_page(
            self::MENU_NAME,
            __(GroupType::getName()."s"),
            __(GroupType::getName()."s"),
            'edit_posts',
            'edit.php?post_type='.GroupType::getPostType()
        );

        add_submenu_page(
            self::MENU_NAME,
            __('+ '.GroupType::getName()),
            __('+ '.GroupType::getName()),
            'edit_posts',
            'post-new.php?post_type='.GroupType::getPostType()
        );

        add_submenu_page(
            self::MENU_NAME,
            __(ParishType::getName()."s"),
            __(ParishType::getName()."s"),
            'edit_posts',
            'edit.php?post_type='.ParishType::getPostType()
        );

        add_submenu_page(
            self::MENU_NAME,
            __('+ '.ParishType::getName()),
            __('+ '.ParishType::getName()),
            'edit_posts',
            'post-new.php?post_type='.ParishType::getPostType()
        );

        add_submenu_page(
            self::MENU_NAME,
            __(RoleType::getName()."s"),
            __(RoleType::getName()."s"),
            'edit_posts',
            'edit.php?post_type='.RoleType::getPostType()
        );

        add_submenu_page(
            self::MENU_NAME,
            __('+ '.RoleType::getName()),
            __('+ '.RoleType::getName()),
            'edit_posts',
            'post-new.php?post_type='.RoleType::getPostType()
        );
    }

    public function displayMenu(): void
    {
        AffectationHome::display();
    }
}
