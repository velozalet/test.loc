<?php
/*
Plugin Name: Plugin twitt + Google-Map
Plugin URI: http://страница_с_описанием_плагина_и_его_обновлений
Description: Плагин twitt + Google-Map
Version: 1.1v
Author: Lutskyi
Author URI: http://
*/
//____________________________________________________________________________________________________________________
/*
//ПОЛУЧЕНИЕ данных поля(option_value) из табл.(wp_options)- получение настроек,выставленных в Админке для PopUp-окна
/*
global $wpdb;
$result = $wpdb->get_results("SELECT option_value FROM wp_options WHERE option_name='title'"); //WHERE option_name='text'
foreach($result as $item) { echo $my_title= $item->option_value; }
//_________________________________или:
$theme_twitt= get_option('theme_twitt');
$text_twitt= get_option('text_twitt');
$width_twitt_gmap= get_option('width_twitt_gmap');
$length_twitt_gmap= get_option('length_twitt_gmap');
$text_twitt_gmap= get_option('text_twitt_gmap');
$radius_twitt_gmap= get_option('radius_twitt_gmap');
$text_radius_twitt_gmap= get_option('text_radius_twitt_gmap');
$my_range_slider= get_option('my_range_slider');
*/
//____________________________________________________________________________________________________________________


//--------------------------------------------------------------------------------------------------------------------
// РЕГИСТРАЦИЯ CSS-file(plugin_twittgooglemap.css) И javaScript-file(plugin_twittgooglemap.js) В СВОЕМ ПЛАГИНЕ:
function add_my_scripts_plugin_twitt_google() {
			//var_dump(plugins_url()); die; - посмотреть путь URL'a: http://test.loc/wp-content/plugins
			//var_dump(plugins_url('/scc/plugin_popup.css', __FILE__)); die; - проверить путь URL'a

    wp_register_script('maps_googleapis','http://maps.googleapis.com/maps/api/js'); //'http://maps.googleapis.com/maps/api/js?v=3.exp&;sensor=false'
    wp_register_style('plugin_twittgooglemap_css', plugins_url( '/plugin_twittgooglemap.css', __FILE__ ));  //регистрируем plugin_twittgooglemap.css
    wp_register_style('jquery_css', '//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css');  //стили для ползунка с он-лайн библиотеки(не нужны если в свою Ссс прописать их)
    wp_register_script('jquery_ui', '//code.jquery.com/ui/1.11.4/jquery-ui.js', array('jquery'));
	wp_register_script('plugin_twittgooglemap_js', plugins_url( '/plugin_twittgooglemap.js', __FILE__ ), array('jquery', 'maps_googleapis'));  //.2-м парам.:array('jquery')-подкл.библ. jquery, 3-м парам.:array('jquery-ui-slider')-подкл.библ. jquery-плагина UI-Slider

    wp_enqueue_script('maps_googleapis');
    wp_enqueue_script('jquery_ui');
    wp_enqueue_style('jquery_css'); //добавляем в очередь на вывод файл plugin_twittgooglemap.css
    wp_enqueue_style('plugin_twittgooglemap_css'); //добавляем в очередь на вывод файл plugin_twittgooglemap.css
    wp_enqueue_script('plugin_twittgooglemap_js'); //добавляем в очередь на вывод файл plugin_twittgooglemap.js


    $data_arr_for_js = array(
        'theme_twitt'            => get_option('theme_twitt'),
        'text_twitt'             => get_option('text_twitt'),
        'width_twitt_gmap'       => get_option('width_twitt_gmap'),
        'length_twitt_gmap'      => get_option('length_twitt_gmap'),
        'text_twitt_gmap'        => get_option('text_twitt_gmap'),
        'radius_twitt_gmap'      => get_option('radius_twitt_gmap'),
        'text_radius_twitt_gmap' => get_option('text_radius_twitt_gmap'),
        'my_range_slider'        => get_option('my_range_slider'),
    );
    wp_localize_script('plugin_twittgooglemap_js', 'jsObj1', $data_arr_for_js); //локализует javascript,если только этот скрипт уже был определен для WP.Передает javascript'у текст "Hello! I am from php site!"
}
add_action( 'admin_enqueue_scripts', 'add_my_scripts_plugin_twitt_google'); // На wp_enqueue_scripts - не работает(!)
//--------------------------------------------------------------------------------------------------------------------
//START__СОЗДАНИЕ табл.(twitts) в БД АВТОМАТИЧЕСКИ ПРИ АКТИВАЦИИ НАШЕГО ПЛАГИНА В АДМИНКЕ
function table_create() {
    global $wpdb;
    $table_name = $wpdb->prefix . "twitts"; //echo $wpdb->prefix; // wp_ // проверка установленного префикса БД WP.Это тот параметр($table_prefix  = 'wp_';),что находится в wp_config.php

    $sql = "CREATE TABLE IF NOT EXISTS ". $table_name ." (
			id int(11) NOT NULL AUTO_INCREMENT,
			twit_text varchar(255),
 			twit_created_at varchar(255),
 			twit_user_name varchar(255),
 			twit_time_zone varchar(255),
 			twit_lang varchar(255),
 			twit_location varchar(255),
  			PRIMARY KEY (id)
			);";
    $wpdb->query($sql);
}
register_activation_hook(__FILE__,'table_create');

//УДАЛЕНИЕ табл.(twitts) в БД АВТОМАТИЧЕСКИ ПРИ ДЕАКТИВАЦИИ ПЛАГИНА В АДМИНКЕ
function drop_table_myplugin() {
    global $wpdb;
    $table_name = $wpdb->prefix."twitts";

    if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name) {
        $sql = 'DROP TABLE IF EXISTS '.$table_name.'';
        $wpdb->query($sql);
    }
}
register_deactivation_hook(__FILE__,'drop_table_myplugin');


//START__БЛОК НАСТРОЕК ДЛЯ АДМИНКИ Twitt_GoogleMap:
// Добавляем новую дочернюю страницу(подменю) в меню админ-панели "Параметры"(Settings).Функцию нужно вызывать во время события admin_menu
function my_add_option_page() {
    add_options_page('Options ', 'Настройки Twitt_GoogleMap', 'manage_options', 'twitt_googlemap/twitt_googlemap.php', 'my_plugin_page');
}
add_action('admin_menu', 'my_add_option_page');


// Создает новый блок(секцию): add_settings_section() и создает поля для настройки Twitt_GoogleMap: add_settings_field.
function my_add_section_popup(){
    add_settings_section('id_twittgoogle_section','Страница настройки Twitt Google Maps','','twittgoogle_page'); //создает новый блок(секцию),в кот.выводятся опции(настройки)

    add_settings_field( //cоздает поле опции для указанной страницы и указанного блока(секции) - Для ТЕМЫ Twitt_GoogleMap
        'theme_twitt',  //название опции (идентификатор). Используйте в id атрибуте тега.
        'Тема: ',  //заголовок поля(то,как будет подписано в Админке поля для ввода названия Темы для Twitt_GoogleMap)
        'theme_callback_twgoogle', //название ф-и обратного вызова. Ф-я должна заполнять поле нужным <input> тегом, который станет частью одной большой формы. атрибут name должен быть равен параметру $option_name из register_setting().
        'twittgoogle_page', //страница меню в которую будет добавлено поле. Должен совпадать с $page из do_settings_sections() и $page из add_settings_section()
        'id_twittgoogle_section', // название секции страницы настроек,в которую должно быть добавлено нужное поле.Должно совпадать с парам.$id из add_settings_section()
        array( 'id' => 'theme_twitt',
               'option_name' => '') //дополнительные параметры, которые нужно передать callback функции
        );
/*
    add_settings_field( //cоздает поле опции для указанной страницы и указанного блока(секции)- Для ТЕКСТА Twitt_GoogleMap
        'text_twitt',
        'Текст: ',
        'text_callback_twgoogle',
        'twittgoogle_page',
        'id_twittgoogle_section',
        array('id' => 'text_twitt',
              'option_name' => '')
    );
*/
    add_settings_field( //cоздает поле опции для указанной страницы и указанного блока(секции)- Для ПОЛЯ ШИРОТЫ
        'width_twitt_gmap',
        'Широта(width): ',
        'width_callback_twgoogle',
        'twittgoogle_page',
        'id_twittgoogle_section',
        array('id' => 'width_twitt_gmap',
              'option_name' => '')
    );

    add_settings_field( //cоздает поле опции для указанной страницы и указанного блока(секции)- Для ПОЛЯ ДОЛГОТЫ
        'length_twitt_gmap',
        'Долгота(length): ',
        'length_callback_twgoogle',
        'twittgoogle_page',
        'id_twittgoogle_section',
        array('id' => 'length_twitt_gmap',
              'option_name' => '')
    );
/*
    add_settings_field( //cоздает поле опции для указанной страницы и указанного блока(секции)- Для ТЕКСТА для Широты/Долготы
        'text_twitt_gmap',
        'Текст с координатами(Широта/Долгота):',
        'textmap_callback_twgoogle',
        'twittgoogle_page',
        'id_twittgoogle_section',
        array('id' => 'text_twitt_gmap',
              'option_name' => '')
    );
*/
    add_settings_field( //cоздает поле опции для указанной страницы и указанного блока(секции)- Для РАДИУСА
        'radius_twitt_gmap',
        'Радиус:',
        'radius_callback_twgoogle',
        'twittgoogle_page',
        'id_twittgoogle_section',
        array('id' => 'radius_twitt_gmap',
              'option_name' => '')
   );
/*
    add_settings_field( //cоздает поле опции для указанной страницы и указанного блока(секции)- Для ТЕКСТА для РАДИУСА
        'text_radius_twitt_gmap',
        'Текст для Поля "Радиус":',
        'textmapradius_callback_twgoogle',
        'twittgoogle_page',
        'id_twittgoogle_section',
        array('id' => 'text_radius_twitt_gmap',
              'option_name' => '')
    );
*/
    add_settings_field(  // range slider
        'my_range_slider',
        'RANGE SLIDER',
        'range_slider_callback_twgoogle',
        'twittgoogle_page',
        'id_twittgoogle_section',
        array('id' => 'my_range_slider',
              'option_name' => '')
    );


 //Регистрируем новую(ые) ОПЦИИ настроек:
    register_setting('id_twittgoogle_section', //название группы,к кот.будет принадлежать опция.Это название должно совпадать с названием группы в ф-ии settings_field() в параметре $section
                     'theme_twitt'  //название опции(идентификатор),кот.будет сохраняться в БД.Этот идентификатор должен совпадать с $id из ф-ии settings_field()
                     ); //$sanitize_callback(название функции обратного вызова,которая будет обрабатывать значение опции перед сохранением) - Не передавать!!
    register_setting('id_twittgoogle_section','text_twitt'); //  Текст Твитта
    register_setting('id_twittgoogle_section','width_twitt_gmap'); // Широта
    register_setting('id_twittgoogle_section','length_twitt_gmap'); // Долгота
    register_setting('id_twittgoogle_section','text_twitt_gmap'); // Текст для Широты/Долготы
    register_setting('id_twittgoogle_section','radius_twitt_gmap'); // Радиус
    register_setting('id_twittgoogle_section','text_radius_twitt_gmap'); // Текст для Радиуса
    register_setting('id_twittgoogle_section','my_range_slider'); // range slider
}
add_action('admin_menu', 'my_add_section_popup');


// Функции ОБРАТНОГО ВЫЗОВА для опций(настроек) PupUp-окна:
function theme_callback_twgoogle() { //Ф-я обратного вызова(отрисовки) для поля(theme_twitt)- ТЕМА
    echo '<input type="text" name="theme_twitt" size="45" value="'.get_option('theme_twitt').'">';
}
/*
function text_callback_twgoogle() { //Ф-я обратного вызова(отрисовки) для поля(text_twitt)- ТЕКСТ
    echo '<textarea name="text_twitt"  rows="6" cols="45"> '.get_option('text_twitt').'</textarea>';
}
*/
function width_callback_twgoogle() { //Ф-я обратного вызова(отрисовки) для поля настройки(width_twitt_gmap)- ШИРОТА
    echo '<input type="text" name="width_twitt_gmap" id="my_width" size="17" value='.get_option('width_twitt_gmap').'> ';
}

function length_callback_twgoogle() { //Ф-я обратного вызова(отрисовки) для поля настройки(length_twitt_gmap)- ДОЛГОТА
    echo '<input type="text" name="length_twitt_gmap" id="my_length" size="17" value='.get_option('length_twitt_gmap').'> ';
}
/*
function textmap_callback_twgoogle() { //Ф-я обратного вызова(отрисовки) для поля(text_twitt_gmap)- ТЕКСТ с координатами(Широта/Долгота)
    echo '<textarea name="text_twitt_gmap"  rows="5" cols="45"> '.get_option('text_twitt_gmap').'</textarea>';
}
*/
function radius_callback_twgoogle() { //Ф-я обратного вызова(отрисовки) для поля(radius_twitt_gmap)- РАДИУС
    echo '<input type="text" name="radius_twitt_gmap" id="radius_gmap" size="15" value='.get_option('radius_twitt_gmap').'> ';

}
/*
function textmapradius_callback_twgoogle() { //Ф-я обратного вызова(отрисовки) для поля(text_twitt_gmap)- ТЕКСТ для Радиуса
    echo '<textarea name="text_radius_twitt_gmap"  rows="5" cols="45"> '.get_option('text_radius_twitt_gmap').'</textarea>';
}
*/
function range_slider_callback_twgoogle() { // <div>-фрейм с Google Map и <input type="range">-(range_slider)ползунок диапазона для карты
    echo '<div id="slider"></div>
          <div id="map-canvas" class="g_map_style"></div> <br>';
}


// ВЫВОД всего БЛОКА ОПЦИЙ на страницу управления окном-PopUp,добавленную через my_add_option_page()
function my_plugin_page() {
    echo "Здесь вывод настроек для Twitt Google Maps:"; //запись на пустой новой странице в меню админ-панели,кот.мы создали через my_plugin_menu()

    echo '<form action="/wp-admin/options.php" method="POST">';
        do_settings_sections('twittgoogle_page'); //Выводит на экран все блоки опций, относящиеся к указанной странице настроек в админ-панели// $page- идентификатор страницы админ-панели на которой нужно вывести блоки опций.Должен совпадать с параметром $page из add_settings_section()
        settings_fields('id_twittgoogle_section'); //выводит скрытые поля формы на странице настроек// $option_group- название группы настроек,должно совпадать с парам.$option_group из register_setting()
        submit_button('SEND/Отправить'); // отрисовка кнопки формы "ОТПРАВИТЬ"
    echo '</form>';
}
//END__БЛОК НАСТРОЕК ДЛЯ АДМИНКИ PopUP
//------------------------------------------------------------------------------------------------------------------------------------


//Add TwitterOAuth library (PHP library for use with the Twitter OAuth)
require_once "twitteroauth/autoload.php"; //путь к библиотеке twitteroauth
use Abraham\TwitterOAuth\TwitterOAuth;

//echo '<pre>'; var_dump($content); die;echo '<pre>';// проверить что пришло

function add_table(){
    // Settings keys:
    define('CONSUMER_KEY','8zx0iDI1URlyDp84Fg3lydMUm');
    define('CONSUMER_SECRET','q2DCR4TEzf3OxT7pB7DwUBfQLZ3wleavTM1yN1KEUBGbbsAa5U');

    //define('OAUTH_CALLBACK', 'http://yousite.ru/callback.php');
    $access_token= '89649557-JUdPM5iSFVnayMfrphv6iRMVLVtYXExlLD1lM2tp1';
    $access_token_secret= 'UtQ7DUooabMP9Awd6bpfVhZUcKryC7NHFHXSQMeDzJoCb';

    //создаем TwitterOAuth объект на основе учетной записи нашего приложения и временных токенов:
    $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token, $access_token_secret);   //$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);

    //методы для получения данных пользователя:
    //$content = $connection->get("account/verify_credentials"); //  получить данные учетной записи пользователя
    //$content = $connection->get("statuses/home_timeline", array("count" => 2, "exclude_replies" => true));
    $content = $connection->get("search/tweets", array("count" => 7, //кол-во твиттов,кот мы подтягиваем к себе в выборку
        "q" => get_option('theme_twitt'), // тема на которую будет поиск твиттов
        "geocode" => get_option('width_twitt_gmap').','.get_option('length_twitt_gmap').','.get_option('radius_twitt_gmap').'mi', // гео-координаты в виде: 45.50735445050955,-122.67608642578125,10mi
        "lang" => "en", // язык твиттов
        "locale" => "ja",
        "result_type" => "recent", // тип твиттов,кот.мы подтягиваем
        "until" => "2015-07-24", // время период закоторое выбираем твитты
        "since_id" => "", // начальный № id (если надо выборка по номеру id)
        "max_id" => "", // конечный № id (если надо выборка по номеру id)
        "include_entities" => "false",
        //"callback" => "processTweets",
    ));
    // выбираем нужные нам значения из Объекта,в котором вытянутые Твиты и кот.пришел GET-запросом и ложим их в БД
       foreach ($content->statuses as $my_content) { // ->statuses -обращение к объекту в котором массив
       $twit_text = $my_content->text; // выбираем в этом Объекте Тему Твитта //esc_sql()- аналог escape_string() в pHp
       $twit_created_at = $my_content->created_at; // выбираем в этом Объекте дату создания Твитта
       $twit_user_name = $my_content->user->name; // выбираем в этом Объекте имя Автора данного Твитта
       $twit_time_zone = $my_content->user->time_zon; // выбираем в этом Объекте временную Зону по месту пребывания Автора Твитта
       $twit_lang = $my_content->user->lang; // выбираем в этом Объекте язык Твитта
       $twit_location = $my_content->user->location; // Географическое место расположения Автора Твитта

        global $wpdb;
        $table_name = $wpdb->prefix."twitts";
        $wpdb->query( $wpdb->prepare(
            "INSERT INTO " .DB_NAME.".".$table_name. " (twit_text,twit_created_at, twit_user_name, twit_time_zone, twit_lang,twit_location)
             VALUES (%s,%s,%s,%s,%s,%s)", array( $twit_text,
                $twit_created_at,
                $twit_user_name,
                $twit_time_zone,
                $twit_lang,
                $twit_location
            )
        ));
    } //END__foreach
}
//add_action('wp_footer', 'add_table'); // или на wp_head - закомментировано,т.к. мы эту функцию повесили на my_activation с ф-ей времени wp_schedule_event()


// Добавление своего интервала времени для события my_activation с ф-ей времени wp_schedule_event()
function cron_add( $schedules ) {
       $schedules['one_min'] = array(
        'interval' => 60*1,
        'display' => __( 'One min' )
    );
    return $schedules;
}
add_filter( 'cron_schedules', 'cron_add' );


// добавляем запланированный хук
function my_activation() {
    wp_schedule_event( time(), 'one_min', 'my_hourly_event');
}
register_activation_hook(__FILE__, 'my_activation');

// добавляем функцию к указанному хуку
add_action('my_hourly_event', 'add_table');

//очистка действия
function my_deactivation() {
    wp_clear_scheduled_hook('my_hourly_event');
}
register_deactivation_hook(__FILE__, 'my_deactivation');
//--------------------------------------------------------------------------

function shr_tcode_for_twitts($atts, $content) {
    global $wpdb;
    $sql ="SELECT twit_text,twit_user_name,twit_time_zone,twit_lang,twit_location FROM wp_twitts ORDER BY id DESC LIMIT 5";
    $results= $wpdb->get_results($sql, ARRAY_A);

    if($results==false) {return $atts['нет твиттов'];}
    foreach ($results as $result){
        echo '<b><i>ТЕМА:</i></b> '.$result['twit_text'].'<br>';
        echo '<b><i>АВТОР:</i></b> '.$result['twit_user_name'].'<br>';
        echo '<b><i>ВРЕМЕННАЯ ЗОНА:</i></b>'.$result['twit_time_zone'].'<br>';
        echo '<b><i>ЯЗЫК:</i></b> '.$result['twit_lang'].'<br>';
        echo '<b><i></i>МЕСТОНАХОЖДЕНИЕ:</b> '.$result['twit_location'].'<br>---------------------------------------------------------------------------------------------------------------------------------<br>';
    }
    return $atts['content'];
}
add_shortcode('print_twitts', 'shr_tcode_for_twitts');




