<?php
/*
Plugin Name: js_plugin_add_style
Plugin URI: http://страница_с_описанием_плагина_и_его_обновлений
Description: js_plugin_add_style_(Добавляет html-элементы и названия классов в существующую верстку)
Version: 0.9v
Author: Lutskyi
Author URI: http://
*/

/*
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

register_activation_hook(__FILE__,'Pddm_install');
register_deactivation_hook(__FILE__,'Pddm_deactivation');


function Pddm_install () { }
function Pddm_deactivation () { }


function Pddm_script_method() {
    wp_enqueue_style('styleplugin',plugins_url() . '/polylang-drop-down-menu/css/pddm.css');
    wp_enqueue_script('bootstrap', plugins_url('polylang-drop-down-menu/js/bootstrap.min.js'), array('jquery', 'backbone'), false, true);
    wp_enqueue_script('pddm_script', plugins_url('polylang-drop-down-menu/js/freestc.js'), array('jquery', 'backbone'), false, true);
}
add_action( 'wp_enqueue_scripts', 'Pddm_script_method' );
*/
//--------------------------------------------------------------------------------------------------------------------------

// Registration CSS-file & js-file in this Plugin :
function add_script_method_jpas() {
			//var_dump(plugins_url()); die; - посмотреть путь URL'a: http://test.loc/wp-content/plugins
			//var_dump(plugins_url('/scc/plugin_popup.css', __FILE__)); die; - проверить путь URL'a
	wp_register_style('plugin_jpas_css', plugins_url( 'css/plugin_jpas.css', __FILE__ ));  //
	wp_register_script('bootstrap_min_js', plugins_url( 'jscript/bootstrap.min.js', __FILE__ ), array('jquery'));  //
    wp_register_script('plugin_jpas_js', plugins_url( 'jscript/plugin_jpas.js', __FILE__ ), array('jquery'));  //

	wp_enqueue_style('plugin_jpas_css'); //
    wp_enqueue_script('bootstrap_min_js'); //
    wp_enqueue_script('plugin_jpas_js'); //
}
add_action( 'wp_enqueue_scripts', 'add_script_method_jpas'); //admin_menu - Надо вообще вешать на него(!)

