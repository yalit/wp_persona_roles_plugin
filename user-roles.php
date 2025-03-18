<?php
   /**
   * Plugin Name: User Roles
   * Description: Handles user roles and presence in groups
   * Version: 0.1
   * Author: Yannick Alsberge
   * Author URI: https://yalit.be
   * License: MIT
   * Text Domain: user-roles
   */

use pages\AffectationImporterPage;
use pages\PersonaMenu;
use rest\PersonaRest;
use shortcode\Shortcode;
use types\AffectationType;
use types\GroupType;
use types\ParishType;
use types\PersonaType;
use types\RoleType;

if ( ! defined( 'ABSPATH' ) ) {
      exit; // Exit if accessed directly
   }
   
   require_once('autoload.php');

   add_action('init', [ PersonaMenu::class, 'init']);
   add_action('init', [ ParishType::class, 'init']);
   add_action('init', [ PersonaType::class, 'init']);
   add_action('init', [ GroupType::class, 'init']);
   add_action('init', [ RoleType::class, 'init']);
   add_action('init', [ AffectationType::class, 'init']);
   add_action('init', [ Shortcode::class, 'init']);

   add_action('init', [ AffectationImporterPage::class, 'init']);

   add_action( 'rest_api_init', [PersonaRest::class, 'registerRoutes'] );

   add_action('admin_enqueue_scripts', function() {
      wp_enqueue_style( 'persona_style_admin', plugin_dir_url(__FILE__) .'/styles/persona_admin.css');
      wp_enqueue_style( 'persona_style_front', plugin_dir_url(__FILE__) .'/styles/persona_front.css');
   });

   add_action('wp_enqueue_scripts', function() {
      wp_enqueue_style( 'persona_style_front', plugin_dir_url(__FILE__) .'/styles/persona_front.css');
   });

   add_action('admin_enqueue_scripts', function() {
         wp_register_script(
            'persona_script_shortcode_generator_admin', // Identifiant unique pour le script
            plugin_dir_url(__FILE__) .'/scripts/shortcode-generator.js', // Chemin vers le fichier JavaScript
            [], // Dépendances (par exemple, jQuery)
            1, // Version du script (utilise la date de modification du fichier)
            true // Charger le script dans le pied de page
        );
    
        wp_enqueue_script( 'persona_script_shortcode_generator_admin');
   });
