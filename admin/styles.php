<?php // FBC Latest Backup for UpdraftPlus - Add Styles

// disable direct file access
if (! defined('ABSPATH')) {
        exit;
}

function fbc_latest_backup_add_my_stylesheet()
{
    wp_enqueue_style('fbc_latest_backup_CSS', plugin_dir_url(__FILE__) . 'css/style.css');
}

add_action('admin_enqueue_scripts', 'fbc_latest_backup_add_my_stylesheet');
