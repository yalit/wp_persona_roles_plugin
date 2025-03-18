<?php 

require_folder(__DIR__.'/types');
require_folder(__DIR__.'/model');
require_folder(__DIR__.'/repository');
require_folder(__DIR__.'/shortcode');
require_folder(__DIR__.'/svg');
require_folder(__DIR__.'/pages');
require_folder(__DIR__.'/rest');
require_folder(__DIR__.'/services');


function require_folder(string $folder): void
{
    if (!is_dir($folder)) return;

    foreach (scandir($folder) as $filename) {
        if ($filename === '.' || $filename === '..') {
            continue;
        }

        $path = $folder. '/' . $filename;
        if (is_file($path)) {
            require_once($path);
        } else if (is_dir($path)) {
            require_folder($path);
        }
    }
}
