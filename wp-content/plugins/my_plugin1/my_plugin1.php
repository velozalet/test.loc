<?php
/*
Plugin Name: my_plugin1
Plugin URI: http://страница_с_описанием_плагина_и_его_обновлений
Description: Мой тестовый плагин.....
Version: 0.0v
Author: Lutskyi
Author URI: http://
*/

// РЕГИСТРАЦИЯ CSS-file(my_plugin1.css) И javaScript-file(my_plugin1.js) В СВОЕМ ПЛАГИНЕ:
function add_my_scripts_to_plugin() { 
			//var_dump(plugins_url()); die; - посмотреть путь URL'a: http://test.loc/wp-content/plugins
			//var_dump(plugins_url('/scc/my_plugin1.css', __FILE__)); die; - проверить путь URL'a: http://test.loc/wp-content/plugins/my_plugin1/my_plugin1.css
	wp_register_style('myplugin1_css', plugins_url( '/style.css', __FILE__ ));  //регистрируем style.css
	wp_register_script('myplugin1_js', plugins_url( '/functions.js', __FILE__ ), array('jquery'));  //регистрируем functions.js.2-м парам.:array('jquery')-подкл.библ. jquery

	wp_enqueue_style('myplugin1_css'); //добавляем в очередь на вывод файл my_plugin1.css
    wp_enqueue_script('myplugin1_js'); //добавляем в очередь на вывод файл my_plugin1.js

    wp_localize_script('myplugin1_js', 'jsObject2', array( 'text' => 'Hello! This text from Script my_plugin1')); //локализует javascript,если только этот скрипт уже был определен для WP.Передает javascript'у текст "Hello! I am from php site!"
}  
add_action( 'wp_enqueue_scripts', 'add_my_scripts_to_plugin');
//--------------------------------------------------------------------------------------------------------------------

//START__СОЗДАНИЕ табл.(my_plugin1) в БД АВТОМАТИЧЕСКИ ПРИ АКТИВАЦИИ НАШЕГО ПЛАГИНА В АДМИНКЕ
function table_create() {
    global $wpdb;

   $table_name = $wpdb->prefix . "my_plugin1"; //echo $wpdb->prefix; // wp_ // проверка установленного префикса БД WP.Это тот параметр($table_prefix  = 'wp_';),что находится в wp_config.php
      
   $sql = "CREATE TABLE IF NOT EXISTS ". $table_name ." (
			id int(11) NOT NULL AUTO_INCREMENT,
 			name varchar(255) NOT NULL,
  			text text NOT NULL,
  			url varchar(255) NOT NULL,
  			time int(1) NOT NULL,
  			PRIMARY KEY (id)
			);";
	
	$wpdb->query($sql);
}
register_activation_hook(__FILE__,'table_create');
//_______________________________или:
/*   
function table_create() {
    global $wpdb;

   $table_name = $wpdb->prefix . "my_plugin1"; //echo $wpdb->prefix; // wp_ // проверка установленного префикса БД WP.Это тот параметр($table_prefix  = 'wp_';),что находится в wp_config.php
  
  if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) { //проверяет,если есть в БД таблица с таким именем.Если нету(т.е.!= wp_my_plugin1),то создаем ее  
   $sql = "CREATE TABLE ". $table_name ." (
			`id` int(11) NOT NULL AUTO_INCREMENT,
 			`name` varchar(255) NOT NULL,
  			`text` text NOT NULL,
  			`url` varchar(255) NOT NULL,
  			`time` int(1) NOT NULL,
  			PRIMARY KEY (`id`)
			);";
	
	$wpdb->query($sql);
	}
}
register_activation_hook(__FILE__,'table_create');
*/
//END__СОЗДАНИЕ табл.(my_plugin1) в БД АВТОМАТИЧЕСКИ ПРИ АКТИВАЦИИ НАШЕГО ПЛАГИНА В АДМИНКЕ
//----------------------------------------------------------------------------------------------------------------------

//START__УДАЛЕНИЕ табл.(my_plugin1) в БД АВТОМАТИЧЕСКИ ПРИ ДЕАКТИВАЦИИ НАШЕГО ПЛАГИНА В АДМИНКЕ
function table_drop() {
    global $wpdb;

    $table_name = $wpdb->prefix ."my_plugin1";

    if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name) {
        $sql = 'DROP TABLE IF EXISTS ' . $table_name .'';

        $wpdb->query($sql);
    }

}
register_deactivation_hook(__FILE__,'table_drop');
//END__УДАЛЕНИЕ табл.(my_plugin1) в БД АВТОМАТИЧЕСКИ ПРИ ДЕАКТИВАЦИИ НАШЕГО ПЛАГИНА В АДМИНКЕ


?>