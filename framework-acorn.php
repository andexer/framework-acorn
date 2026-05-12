<?php

/**
 * Plugin Name: Framework
 * Plugin URI:  https://roots.io/sage/
 * Description: WordPress framework for creating plugins.
 * Version:     1.0.0
 * Author:      Roots
 * Author URI:  https://roots.io/
 * License:     GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: framework
 */

if (! defined('ABSPATH')) {
	exit;
}

define('FRAMEWORK_PLUGIN_FILE', __FILE__);
define('FRAMEWORK_PLUGIN_BASENAME', plugin_basename(__FILE__));

require __DIR__ . '/framework.php';
