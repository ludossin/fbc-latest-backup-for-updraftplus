<?php
/*
Plugin Name: FBC Latest Backup for UpdraftPlus
PluginURI: https://github.com/ludossin/fbc-latest-backup-for-updraftplus
Description: Adds a widget to the dashboard, letting you know the date and time of latest backup and how many edits were made since then, if any
Version: 1.0
Author: Lucia Dossin
Text Domain:  fbc-latest-backup
Domain Path:  /languages
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version
2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
with this program. If not, visit: https://www.gnu.org/licenses/
*/

// disable direct file access
if (! defined( 'ABSPATH' ) ) {
        exit;
}

// load text domain
function fbc_latest_backup_load_textdomain() {
    load_plugin_textdomain( 'fbc-latest-backup', false, basename(dirname(__FILE__)).'/languages/');
}

add_action( 'plugins_loaded', 'fbc_latest_backup_load_textdomain' );

if ( is_admin() ) {
    require_once plugin_dir_path( __FILE__ ) . 'admin/widget.php';
    require_once plugin_dir_path( __FILE__ ) . 'admin/styles.php';
}
