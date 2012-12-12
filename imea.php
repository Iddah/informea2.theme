<?php
/*
Plugin Name: InforMEA AI API
Plugin URI: http://www.informea.org/
Description: This is the plugin implements InforMEA API specific to the analytical index management
Version: 1.0
Author: cristiroma
Author URI: http://www.eaudeweb.ro
License: GPL2
*/

/*
    Copyright 2011 Eau De Web  (email : office@eaudeweb.ro)
	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
require_once __DIR__ . '/base.php';
require_once __DIR__ . '/countries.php';
require_once __DIR__ . '/decisions.php';
require_once __DIR__ . '/decisions.admin.php';
require_once __DIR__ . '/ecolex.php';
require_once __DIR__ . '/events.php';
require_once __DIR__ . '/highlights.php';
require_once __DIR__ . '/treaties.php';
require_once __DIR__ . '/utilities.php';



# We need to load this plugin first as other modules depends on this API
# http://stv.whtly.com/2011/09/03/forcing-a-wordpress-plugin-to-be-loaded-before-all-other-plugins/
add_action( 'activated_plugin', 'imea_load_first' );
function imea_load_first() {
    var_dump(1);
	$path = str_replace(WP_PLUGIN_DIR . '/', '', __FILE__);

	if($plugins = get_option( 'active_plugins')) {
        var_dump(2);
		if($key = array_search( $path, $plugins)) {
            var_dump(3);
            var_dump($plugins);
			array_splice($plugins, $key, 1);
			array_unshift($plugins, $path);
			update_option('active_plugins', $plugins);
		}
	}
}