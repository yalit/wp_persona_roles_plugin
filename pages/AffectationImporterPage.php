<?php

namespace pages;

use services\AffectationImporter;
use services\GroupImporter;
use services\Parishimporter;
use services\PersonaImporter;
use services\RoleImporter;

class AffectationImporterPage
{
    public const LEGACY_ID_FIELD_NAME = 'legacyId';

    public static function init(): self
    {
        return new self();
    }

    public function __construct()
    {
        add_action('admin_menu', [$this, 'setImportMenu']);
        add_action('admin_init', [$this, 'importHandler']);
    }

    public function setImportMenu(): void
    {
/*        if (!is_admin()) {
            return;
        }

        add_submenu_page(
            PersonaMenu::MENU_NAME,
            __("Import Data"),
            __("Import Data"),
            'manage_options',
            PersonaMenu::MENU_NAME . '_import',
            [$this, 'importPage']
        );*/
    }

    public function importPage(): void
    {
        ?>
        <div class="wrap">
            <h1 class="wp-heading-inline">Import CSV</h1>

            <div id="poststuff">
                <div class="postbox-container">
                    <div class="postbox">
                        <div class="postbox-header"><h2>Formulaire d'import</h2></div>
                        <div class="inside">
                            <form method="post" enctype="multipart/form-data">
                                <?php wp_nonce_field('csv_import_nonce', 'csv_import_nonce'); ?>
                                <div class="form-line">
                                    <div class="form-group">
                                        <label>Fichier</label>
                                        <input type="file" name="person_import_csv_file" accept=".csv">
                                    </div>
                                </div>
                                <div class="form-line">
                                    <div class="form-group">
                                        <label>Type</label>
                                        <select name="import_type">
                                            <option value="personas">Personnes</option>
                                            <option value="roles">Roles</option>
                                            <option value="groups">Groupes</option>
                                            <option value="parishes">Paroisses</option>
                                            <option value="affectations">Affectations</option>
                                        </select>
                                    </div>
                                </div>
                                <input type="submit" name="submit" class="button button-primary" value="Import CSV">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    public function importHandler(): void
    {
        if (!empty($_FILES['person_import_csv_file']['tmp_name']) && array_key_exists('import_type', $_POST)) {
            // Security check: Verify nonce
            if (!isset($_POST['csv_import_nonce']) || !wp_verify_nonce($_POST['csv_import_nonce'], 'csv_import_nonce')) {
                wp_die('Security check failed.');
            }

            $file = $_FILES['person_import_csv_file'];
            $file_type = wp_check_filetype($file['name'], array('csv' => 'text/csv'));

            // Validate file type
            if ($file_type['ext'] === 'csv') {
                $csv_path = $file['tmp_name'];
                $this->process_csv($csv_path, $_POST['import_type']);
            } else {
                wp_die('Invalid file type. Only CSV files are allowed.');
            }
        }
    }

    public function process_csv(string $csv_path, string $importType): void
    {
        $handle = fopen($csv_path, 'r');
        if ($handle === false) {
            wp_die('Failed to open CSV file.');
        }

        $getRowData = function (string $line) {
            $line = str_replace(";;", ";NULL;", $line);
            return array_map(fn(string $w) => str_replace('"', '', $w), explode(";", $line));
        };

        $line = fgetcsv($handle); // Read header row
        $batch_size = 100; // Process 100 rows at a time
        $row_count = 0;
        $headers = $getRowData($line[0]);

        // Process each row
        while (($row = fgetcsv($handle)) !== false) {
            $datas = array_combine($headers, $getRowData($row[0]));

            match ($importType) {
                "personas" => PersonaImporter::import($datas),
                "roles" => RoleImporter::import($datas),
                "groups" => GroupImporter::import($datas),
                "parishes" => Parishimporter::import($datas),
                'affectations' => AffectationImporter::import($datas),
            };

            $row_count++;
            if ($row_count % $batch_size === 0) {
                sleep(1); // Reduce server load
            }
        }

        fclose($handle);
    }
}
