<?php
/*
Plugin Name: plugin_popup
Plugin URI: http://страница_с_описанием_плагина_и_его_обновлений
Description: Плагин Pop Up
Version: 0.9v
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
$title= get_option('title'); //Заголовок окна-PopUp
$text= get_option('text'); //Текст окна-PopUp
$start_time_popap= get_option('start_time_popap'); //Время задержки перед появлением окна-PopUp
$time_show_popap= get_option('time_show_popap');//Время отображения окна-PopUp
$close_button= get_option('close_button'); //Наличие кнопки "Закрыть" на окне-PopUp
$close_esc_key= get_option('close_esc_key'); //Возможность закрыть окно-PopUp по нажатию клавиши "Esc"
$close_popap_overlay= get_option('close_popap_overlay'); //Возможность закрыть окно-PopUp по клику на overlay
*/
//____________________________________________________________________________________________________________________

// РЕГИСТРАЦИЯ CSS-file(plugin_popup.css) И javaScript-file(plugin_popup.js) В СВОЕМ ПЛАГИНЕ:
function add_my_scripts_plugin_popup() {
			//var_dump(plugins_url()); die; - посмотреть путь URL'a: http://test.loc/wp-content/plugins
			//var_dump(plugins_url('/scc/plugin_popup.css', __FILE__)); die; - проверить путь URL'a
	wp_register_style('pluginpopup_css', plugins_url( '/plugin_popup.css', __FILE__ ));  //регистрируем plugin_popup.css
	wp_register_script('pluginpopup_js', plugins_url( '/plugin_popup.js', __FILE__ ), array('jquery'));  //регистрируем plugin_popup.js.2-м парам.:array('jquery')-подкл.библ. jquery

	wp_enqueue_style('pluginpopup_css'); //добавляем в очередь на вывод файл my_plugin1.css
    wp_enqueue_script('pluginpopup_js'); //добавляем в очередь на вывод файл my_plugin1.js


    $data_popup_arr = array(
        'title'                => get_option('title'), // Заголовок окна-PopUp
        'text'                 => get_option('text'), //Текст окна-PopUp
        'start_time_popap'     => get_option('start_time_popap'), //Время задержки перед появлением окна-PopUp
        'time_show_popap'      => get_option('time_show_popap'), //Время отображения окна-PopUp
        'close_button'         => get_option('close_button'), //Наличие кнопки "Закрыть" на окне-PopUp
        'close_esc_key'        => get_option('close_esc_key'), //Возможность закрыть окно-PopUp по нажатию клавиши "Esc"
        'close_popap_overlay'  => get_option('close_popap_overlay'), //Возможность закрыть окно-PopUp по клику на overlay
    );
    wp_localize_script('pluginpopup_js', 'jsObject3', $data_popup_arr); //локализует javascript,если только этот скрипт уже был определен для WP.Передает javascript'у текст "Hello! I am from php site!"
}
add_action( 'wp_enqueue_scripts', 'add_my_scripts_plugin_popup'); //admin_menu - Надо вообще вешать на него(!)
//--------------------------------------------------------------------------------------------------------------------

//START__БЛОК НАСТРОЕК ДЛЯ АДМИНКИ PopUP:
// Добавляем новую дочернюю страницу(подменю) в меню админ-панели "Параметры"(Settings).Функцию нужно вызывать во время события admin_menu
function my_add_option_page() {
    add_options_page('Options PopUp', 'Настройки PopUp', 'manage_options', 'plugin_popup/plugin_popup.php', 'my_plugin_page');
}
add_action('admin_menu', 'my_add_option_page');


// Создает новый блок(секцию): add_settings_section() и создает поля для настройки PopUp-окна: add_settings_field.
function my_add_section_popup(){
    add_settings_section('id_popup_section','Страница настройки PopUp-окна','','popup_page'); //создает новый блок(секцию),в кот.выводятся опции(настройки)

    add_settings_field( //cоздает поле опции для указанной страницы и указанного блока(секции) - Для ЗАГОЛОВКА PopUp
        'title',  //название опции (идентификатор). Используйте в id атрибуте тега.
        'Заголовок окна-PopUp: ',  //заголовок поля(то,как будет подписано в Админке поля для ввода названия Заголовка для PopUp)
        'title_callback_popup', //название ф-и обратного вызова. Ф-я должна заполнять поле нужным <input> тегом, который станет частью одной большой формы. атрибут name должен быть равен параметру $option_name из register_setting().
        'popup_page', //страница меню в которую будет добавлено поле. Должен совпадать с $page из do_settings_sections() и $page из add_settings_section()
        'id_popup_section', // название секции страницы настроек,в которую должно быть добавлено нужное поле.Должно совпадать с парам.$id из add_settings_section()
        array( 'id' => 'title',
               'option_name' => '') //дополнительные параметры, которые нужно передать callback функции
        );

    add_settings_field( //cоздает поле опции для указанной страницы и указанного блока(секции)- Для ТЕКСТА PopUp
        'text',  //название опции (идентификатор). Используйте в id атрибуте тега.
        'Текст окна-PopUp: ',  //заголовок поля(то,как будет подписано в Админке поля для ввода названия Заголовка для PopUp)
        'text_callback_popup', //название ф-и обратного вызова. Ф-я должна заполнять поле нужным <input> тегом, который станет частью одной большой формы. атрибут name должен быть равен параметру $option_name из register_setting().
        'popup_page', //страница меню в которую будет добавлено поле. Должен совпадать с $page из do_settings_sections() и $page из add_settings_section()
        'id_popup_section', // название секции страницы настроек,в которую должно быть добавлено нужное поле.Должно совпадать с парам.$id из add_settings_section()
        array('id' => 'text',
              'option_name' => '') //дополнительные параметры, которые нужно передать callback функции
    );

    add_settings_field( //cоздает поле опции для указанной страницы и указанного блока(секции)- Для ПОЛЯ выставления времени задержки появления PopUp
        'start_time_popap',  //название опции (идентификатор). Используйте в id атрибуте тега.
        'Время задержки перед появлением окна-PopUp: ',  //заголовок поля(то,как будет подписано в Админке поля для ввода названия Заголовка для PopUp)
        'start_time_callback_popap', //название ф-и обратного вызова. Ф-я должна заполнять поле нужным <input> тегом, который станет частью одной большой формы. атрибут name должен быть равен параметру $option_name из register_setting().
        'popup_page', //страница меню в которую будет добавлено поле. Должен совпадать с $page из do_settings_sections() и $page из add_settings_section()
        'id_popup_section', // название секции страницы настроек,в которую должно быть добавлено нужное поле.Должно совпадать с парам.$id из add_settings_section()
        array('id' => 'start_time_popap',
              'option_name' => '') //дополнительные параметры, которые нужно передать callback функции
    );

    add_settings_field( //cоздает поле опции для указанной страницы и указанного блока(секции)- Для ПОЛЯ выставления времени отображения PopUp
        'time_show_popap',  //название опции (идентификатор). Используйте в id атрибуте тега.
        'Время отображения окна-PopUp: ',  //заголовок поля(то,как будет подписано в Админке поля для ввода названия Заголовка для PopUp)
        'show_time_callback_popup', //название ф-и обратного вызова. Ф-я должна заполнять поле нужным <input> тегом, который станет частью одной большой формы. атрибут name должен быть равен параметру $option_name из register_setting().
        'popup_page', //страница меню в которую будет добавлено поле. Должен совпадать с $page из do_settings_sections() и $page из add_settings_section()
        'id_popup_section', // название секции страницы настроек,в которую должно быть добавлено нужное поле.Должно совпадать с парам.$id из add_settings_section()
        array('id' => 'time_show_popap',
              'option_name' => '') //дополнительные параметры, которые нужно передать callback функции
    );

    add_settings_field( //cоздает поле опции для указанной страницы и указанного блока(секции)- Для КНОПКИ "закрыть" (input type="checkbox");
        'close_button',  //название опции (идентификатор). Используйте в id атрибуте тега.
        'Наличие кнопки "Закрыть" на окне-PopUp: ',  //заголовок поля(то,как будет подписано в Админке поля для ввода названия Заголовка для PopUp)
        'close_button_callback_popup', //название ф-и обратного вызова. Ф-я должна заполнять поле нужным <input> тегом, который станет частью одной большой формы. атрибут name должен быть равен параметру $option_name из register_setting().
        'popup_page', //страница меню в которую будет добавлено поле. Должен совпадать с $page из do_settings_sections() и $page из add_settings_section()
        'id_popup_section', // название секции страницы настроек,в которую должно быть добавлено нужное поле.Должно совпадать с парам.$id из add_settings_section()
        array('id' => 'close_button',
              'option_name' => '') //дополнительные параметры, которые нужно передать callback функции
    );

    add_settings_field( //cоздает поле опции для указанной страницы и указанного блока(секции)- Для наличия закрыть попап по нажатию на кнопку "Esc" (input type="checkbox");
        'close_esc_key',  //название опции (идентификатор). Используйте в id атрибуте тега.
        'Возможность закрыть окно-PopUp по нажатию клавиши "Esc": ',  //заголовок поля(то,как будет подписано в Админке поля для ввода названия Заголовка для PopUp)
        'close_esc_key_callback_popup', //название ф-и обратного вызова. Ф-я должна заполнять поле нужным <input> тегом, который станет частью одной большой формы. атрибут name должен быть равен параметру $option_name из register_setting().
        'popup_page', //страница меню в которую будет добавлено поле. Должен совпадать с $page из do_settings_sections() и $page из add_settings_section()
        'id_popup_section', // название секции страницы настроек,в которую должно быть добавлено нужное поле.Должно совпадать с парам.$id из add_settings_section()
        array('id' => 'close_esc_key',
              'option_name' => '') //дополнительные параметры, которые нужно передать callback функции
    );

    add_settings_field( //cоздает поле опции для указанной страницы и указанного блока(секции)- Для наличия закрыть попап по клику на overlay (input type="checkbox");
        'close_popap_overlay',  //название опции (идентификатор). Используйте в id атрибуте тега.
        'Возможность закрыть окно-PopUp по клику на overlay: ',  //заголовок поля(то,как будет подписано в Админке поля для ввода названия Заголовка для PopUp)
        'close_click_overlay_callback_popup', //название ф-и обратного вызова. Ф-я должна заполнять поле нужным <input> тегом, который станет частью одной большой формы. атрибут name должен быть равен параметру $option_name из register_setting().
        'popup_page', //страница меню в которую будет добавлено поле. Должен совпадать с $page из do_settings_sections() и $page из add_settings_section()
        'id_popup_section', // название секции страницы настроек,в которую должно быть добавлено нужное поле.Должно совпадать с парам.$id из add_settings_section()
        array('id' => 'close_popap_overlay',
              'option_name' => '') //дополнительные параметры, которые нужно передать callback функции
    );

 //Регистрируем новую(ые) и callback(ф-я обратного вызова) функцию(ии) для обработки значения опции при её сохранении в БД.
    register_setting('id_popup_section', //название группы,к кот.будет принадлежать опция.Это название должно совпадать с названием группы в ф-ии settings_field() в параметре $section
                     'title'  //название опции(идентификатор),кот.будет сохраняться в БД.Этот идентификатор должен совпадать с $id из ф-ии settings_field()
                     ); //$sanitize_callback(название функции обратного вызова,которая будет обрабатывать значение опции перед сохранением) - Не передавать!!
    register_setting('id_popup_section','text'); //Регистр.новую опцию: для ТЕКСТА PopUp
    register_setting('id_popup_section','start_time_popap'); //Регистр.новую опцию: время задержки перед отображением PupUp-окна
    register_setting('id_popup_section','time_show_popap'); //Регистр.новую опцию: время отображения PupUp-окна
    register_setting('id_popup_section','close_button'); //Регистр.новую опцию: Кнопка "ЗАКРЫТЬ" PupUp-окно
    register_setting('id_popup_section','close_esc_key'); //Регистр.новую опцию: Возможность закрыть PupUp-окно по нажатию клавиши "Esc"
    register_setting('id_popup_section','close_popap_overlay'); //Регистр.новую опцию: Возможность закрыть PupUp-окно по клику на overlay
}
add_action('admin_menu', 'my_add_section_popup');


// Функции ОБРАТНОГО ВЫЗОВА для опций(настроек) PupUp-окна:
function title_callback_popup() { //Ф-я обратного вызова(отрисовки) для поля(title)
    echo '<input type="text" name="title" size="45" value="'.get_option('title').'">';
}

function text_callback_popup() { //Ф-я обратного вызова(отрисовки) для поля(text)
    echo '<textarea name="text"  rows="11" cols="75"> '.get_option('text').'</textarea>';
}

function start_time_callback_popap() { //Ф-я обратного вызова(отрисовки) для поля настройки "времени задержки перед отображением PupUp-окна"
    echo '<input type="text" name="start_time_popap" size="15" value='.get_option('start_time_popap').'> ';
}

function show_time_callback_popup() { //Ф-я обратного вызова(отрисовки) для поля настройки "показа PupUp-окна"
    echo '<input type="text" name="time_show_popap" size="15" value="'.get_option('time_show_popap').'">';
}

function close_button_callback_popup() { //Ф-я обратного вызова(отрисовки) для поля настройки "наличие кнопки "Закрыть"" (input type="checkbox")
    $options= get_option('close_button');
    echo '<input type="checkbox" name="close_button" value="1" '.checked( $options, 1, false).'>'; //
}

function close_esc_key_callback_popup() { //Ф-я обратного вызова(отрисовки) для поля настройки "наличиe возможности закрыть попап по нажатию на кнопку "Esc"" (input type="checkbox");
    $options= get_option('close_esc_key');
    echo '<input type="checkbox" name="close_esc_key" value="1" '.checked( $options, 1, false).'>'; //
}

function close_click_overlay_callback_popup() { //Ф-я обратного вызова(отрисовки) для поля настройки "наличиe возможности закрыть попап по overlay" (input type="checkbox");
    $options= get_option('close_popap_overlay');
    echo '<input type="checkbox" name="close_popap_overlay" value="1" '.checked( $options, 1, false).'>'; //
}


// ВЫВОД всего БЛОКА ОПЦИЙ на страницу управления окном-PopUp,добавленную через my_add_option_page()
function my_plugin_page() {
    echo "Здесь вывод настроек окна PopUp плагина [plugin_popup]:"; //запись на пустой новой странице в меню админ-панели,кот.мы создали через my_plugin_menu()

    echo '<form action="/wp-admin/options.php" method="POST">';
        do_settings_sections('popup_page'); //Выводит на экран все блоки опций, относящиеся к указанной странице настроек в админ-панели// $page- идентификатор страницы админ-панели на которой нужно вывести блоки опций.Должен совпадать с параметром $page из add_settings_section()
        settings_fields('id_popup_section'); //выводит скрытые поля формы на странице настроек// $option_group- название группы настроек,должно совпадать с парам.$option_group из register_setting()
        submit_button('SEND/Отправить'); // отрисовка кнопки формы "ОТПРАВИТЬ"
    echo '</form>';
}
//END__БЛОК НАСТРОЕК ДЛЯ АДМИНКИ PopUP


// Подружаем popup.html с HTML-кодом окошка-PopUp:
function load_script_template() {
   $file = file_get_contents(plugin_dir_path(__FILE__).'popup.html'); // путь к HTML-файлу,где лежит html-код с окном-PopUp
    if($file) { echo $file; }
}
add_action('wp_footer', 'load_script_template'); // вешаем на хук к футеру



?>



