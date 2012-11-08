<?php
/* Plugin Name: K4 Importer
 * Plugin URI: http://yaledailynews.com
 * Description:
 * Version: 1.0
 * Author: Michael DiScala
 */

function k4_importer_init() {
  add_rewrite_rule('^k4-importer', 'index.php');
  flush_rewrite_rules();
}
add_action('init','k4_importer_init');

function k4_importer_activation() {
}
register_activation_hook(__FILE__, "k4_importer_activation");
?>
