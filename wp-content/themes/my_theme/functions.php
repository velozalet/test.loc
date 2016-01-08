<?php
//-------------------------------------------------8
// Регистрируем CSS-file(style_css) и javaScript-file(main.js) в WP
function add_my_scripts() { 
    wp_register_style('bootstrap_css', get_template_directory_uri().'/bootstrap/css/bootstrap.css', array(), false, 'all'); // Регистрируем bootstrap.css(Bootstrap)  в WP
    wp_register_script('bootstrap_js', get_template_directory_uri().'/bootstrap/js/bootstrap.js', array('jquery'), false, 'all'); // Регистрируем bootstrap.js(Bootstrap)  в WP
    wp_register_style('style_css', get_template_directory_uri().'/style.css', array('bootstrap_css'), false, 'all'); // Регистрируем CSS-file(style_css) в WP
    wp_register_script('main_js', get_template_directory_uri().'/main.js', array('bootstrap_js'), false, 'all'); // Регистрируем js-file(main.js) в WP

// T OWL Carousel-Slider__START
    wp_register_style('owl_carousel_css', get_template_directory_uri().'/owl-carousel/owl.carousel.css', array('bootstrap_css'), false, 'all');
    wp_register_style('owl_theme_css', get_template_directory_uri().'/owl-carousel/owl.theme.css', array('bootstrap_css'), false, 'all');
    wp_register_script('owl_carousel_js', get_template_directory_uri().'/owl-carousel/owl.carousel.js', array('bootstrap_js'), false, 'all');
// Inclusion OWL Carousel-Slider__END

    wp_enqueue_style('bootstrap_css'); //добавляем в очередь на вывод файл bootstrap.css(Bootstrap) 
    wp_enqueue_script('bootstrap_js'); //добавляем в очередь на вывод файл bootstrap_js(Bootstrap) 
    wp_enqueue_style('style_css'); //добавляем в очередь на вывод файл style.css
    wp_enqueue_script('main_js'); // добавляем в очередь на вывод файл main.js

// Inclusion OWL Carousel-Slider__START
    wp_enqueue_style('owl_carousel_css');
    wp_enqueue_style('owl_theme_css');
    wp_enqueue_script('owl_carousel_js');
// Inclusion OWL Carousel-Slider__END
    wp_localize_script('main_js', 'jsObject', array( 'text' => 'Hello! I am from php site!')); //локализует javascript,если только этот скрипт уже был определен для WP.Передает javascript'у текст "Hello! I am from php site!"
}
add_action('wp_enqueue_scripts', 'add_my_scripts'); //хук через который подключается функция
/*
 // РЕГИСТРИРУЕМ ДОБАВЛЯЕМ СТИЛИ CSS и javascript- по другому, когда сразу идет подключение без предварительной регистрации стилей и javaScript'а
function add_my_scripts(){

    wp_enqueue_script( 'handle', get_template_directory_uri() . '/bootstrap/js/bootstrap.js', array('jquery'));
    wp_enqueue_script( 'main', get_template_directory_uri() . '/main.js', array('handle'));
    wp_localize_script( 'main', 'testObject', array( 'text' => 'Hello! I am from php side!' ) );

    wp_enqueue_style('stylebootstrap', get_template_directory_uri() . '/bootstrap/css/bootstrap.css');
    wp_enqueue_style('style', get_template_directory_uri() . '/style.css', array('stylebootstrap'));
}
add_action('wp_enqueue_scripts', 'add_my_scripts');
*/

//-------------------------------------------------1
 // РЕГИСТРИРУЕМ СВОИ МЕНЮ для свойе ТЕМЫ
function main_menu() { //  Главное меню
    register_nav_menu( 'primary', 'Main Menu' );
}
add_action( 'after_setup_theme', 'main_menu' );


function secondary_menu() { // Подглавное меню
    register_nav_menu( 'primary', 'Secondary Menu');
}
add_action( 'after_setup_theme', 'secondary_menu');

//_______________________________________или:
//register_nav_menus( array(
//    'main_menu' => 'main_menu',
//    'secondary_menu' => 'secondary_menu',
//) );

//-------------------------------------------------2
                 /*
                        register_post_type( $post_type, $args );

                        do_action( 'registered_post_type', $post_type, $args );
                 */
 // РЕГИСТРИРУЕМ СВОИ ТИПЫ ЗАПИСЕЙ:
// post types - c поддержкой картинок-миниатюр(thumbnails). 3-й парам.число- приоритет для вывода в Админке
add_action('init', 'myposttype_thumbnails',3); // хук через который подключается функция

function myposttype_thumbnails()
{
    $labels = array(
        'name' => 'Медиа файл', // Основное название типа записи
        'singular_name' => 'Картинка', // отдельное название для одной записи этого типа
        'add_new' => 'Добавить новую картинку', // для добавления новой записи
        'add_new_item' => 'Создать новую запись',  // заголовка у вновь создаваемой записи в админ-панели.
        'edit_item' => 'Редактировать запись', // для редактирования типа записи
        'new_item' => 'Какой-то текст.... ',  // текст новой записи
        'view_item' => 'Просмотреть запись', // для просмотра записи этого типа.
        'search_items' => 'Поиск записи', // для поиска по этим типам записи
        'not_found' =>  'Not Found', // если в результате поиска ничего не было найдено
        'not_found_in_trash' => 'В корзине не найдено', // если не было найдено в корзине
        'parent_item_colon' => '', // для родительских типов. для древовидных типов
        'menu_name' => 'Меню Галлерея'  // название меню
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => true,
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false, // true
        'menu_position' => null,
        'supports' => array('title','editor','author','thumbnail'),
        'taxonomies'          => array(),
    );
    register_post_type('thumbnails',$args); // регистрируем тип записи c поддержкой картинок-миниатюр(thumbnails)
}


// post types -  поддержкой комментариев (comments). 3-й парам.число- приоритет для вывода в Админке
add_action('init', 'myposttype_comments',1); // хук через который подключается функция

function myposttype_comments()
{
    $labels = array(
        'name' => 'Комментарии', // Основное название типа записи
        'singular_name' => 'Комментарий', // отдельное название для одной записи этого типа
        'add_new' => 'Добавить Комментарий', // для добавления новой записи
        'add_new_item' => 'Создать новый Комментарий',  // заголовка у вновь создаваемой записи в админ-панели.
        'edit_item' => 'Редактировать Комментарий', // для редактирования типа записи
        'new_item' => 'Какой-то текст.... ',  // текст новой записи
        'view_item' => 'Просмотреть Комментарий', // для просмотра записи этого типа.
        'search_items' => 'Поиск Комментария', // для поиска по этим типам записи
        'not_found' =>  'Not Found', // если в результате поиска ничего не было найдено
        'not_found_in_trash' => 'В корзине не найдено', // если не было найдено в корзине
        'parent_item_colon' => '', // для родительских типов. для древовидных типов
        'menu_name' => 'Меню Комментарии'  // название меню
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => true,
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false, // true
        'menu_position' => null,
        'supports' => array('title','editor','author','comments'),
        'taxonomies'          => array(),
    );
    register_post_type('comments',$args); // регистрируем тип записи поддержкой комментариев (comments)
}


// post types -  иерархический(hierarchical) с поддержкой отрывка (excerpt). 3-й парам.число- приоритет для вывода в Админке
add_action('init', 'myposttype_excerpt',2); // хук через который подключается функция

function myposttype_excerpt()
{
    $labels = array(
        'name' => '', // Основное название типа записи
        'singular_name' => 'Цитаты', // отдельное название для одной записи этого типа
        'add_new' => 'Добавить Цитату', // для добавления новой записи
        'add_new_item' => 'Создать новую Цитату',  // заголовка у вновь создаваемой записи в админ-панели.
        'edit_item' => 'Редактировать Цитату', // для редактирования типа записи
        'new_item' => 'Какой-то текст.... ',  // текст новой записи
        'view_item' => 'Просмотреть Цитату', // для просмотра записи этого типа.
        'search_items' => 'Поиск Цитаты', // для поиска по этим типам записи
        'not_found' =>  'Not Found', // если в результате поиска ничего не было найдено
        'not_found_in_trash' => 'В корзине не найдено', // если не было найдено в корзине
        'parent_item_colon' => '', // для родительских типов. для древовидных типов
        'menu_name' => 'Меню Цитаты'  // название меню
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => true,
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => true, // true
        'menu_position' => null,
        'supports' => array('title','editor','author','excerpt'),
        'taxonomies'          => array(),
    );
    register_post_type('excerpt',$args); // регистрируем тип записи иерархический (hierarchical) с поддержкой отрывка (excerpt)
}
//-------------------------------------------------5
/*
add_filter('template_include', 'my_template'); // фильтр передает переменную $template - путь до файла шаблона: home/test.loc/wp-content/themes/my_theme/single-thumbnails.php

function my_template($template ) {  // функция фильтрации

    # шаблон для страниц произвольного типа "book"
    // предполагается, что файл шаблона single-thumbnails.php лежит в папке темы
    global $post;
    if( $post->post_type == 'comments' ){
        return get_stylesheet_directory() . '/single-thumbnails';
    }

    return $template;
}
*/
//-------------------------------------------------6
// Регистрируем сайт бар(панель для виджетов): register_sidebar($args);
function register_my_widgets(){
    register_sidebar( array(
        'name' => sprintf('Main sidebar'), // название панели виджетов. Название будет видно в админ-панели WordPress. По умолчанию "Боковая колонка 1". Default: sprintf(__('Sidebar %d'), $i)
        'id' => "main_sidebar", //идентификатор виджета.Строка,где не должно быть заглавных букв и пробелов. Default: "sidebar-$i"
        'description' => 'Это колонка виджетов', // текст описывающий где будет выводиться панель виджетов. Показывается в панели управления виджетами. Default: ''
        'class' => '', // CSS класс, который будет добавлен главному HTML тегу панели виджетов. Default: ''
        'before_widget' => '<li id="%1$s" class="widget %2$s">', // HTML код, который будет расположен перед каждым виджетом в панели. Например: <li class="my-widget">. Конструкции %1$s и %2$s будут заменены на id и class используемого в сайдбаре виджета. Default: '<li id="%1$s" class="widget %2$s">'
        'after_widget' => "</li>\n", // HTML код, который будет расположен после каждого виджета в панели. Например: </li>. Default: "</li>\n"
        'before_title' => '<h3 class="widgettitle">', // HTML код перед заголовком виджета.  Default: '<h2 class="widgettitle">'
        'after_title' => "</h3>\n", // HTML код после заголовка виджета. Default: "</h2>\n"
    ) );
}
add_action( 'widgets_init', 'register_my_widgets'); // подключаем на хук(событие) widgets_init

//-------------------------------------------------7

// Регистрируем shortcode: add_shortcode($tag,$func);
  // $tag- Название шоткода, который будет использоваться в тексте. Пр. 'gallery'
  // $func- Название функции, которая должна сработать, если найден шоткод.

function my_shortcode($atts, $content) {
   return $atts['content'];
}
add_shortcode('baztag', 'my_shortcode');
//========================================================================================================
// ОТОБРАЖЕНИЕ ВСЕХ ЗАГОЛОВКОВ(которые отображается посредством вызова ф-йй the_title()) в ТЕМЕ КРАСНЫМ: 
function add_style_to_title($my_title) { //при отображении любого заголовка, который отображается посредством вызова ф-йй the_title(), он был обёрнут в: <div class="filtered" style="color: red">{the_title}</div>
    return  '<div class="filtered" style="color: red">' . $my_title .'</div>';
}
add_filter('the_title', 'add_style_to_title');

// ОТОБРАЖЕНИЕ ВСЕХ ТЕКСТОВ(которые отображается посредством вызова ф-йй the_content()) в ТЕМЕ ЗЕЛЕНЫМ:
function add_style_to_content($my_content) { //при отображении любого заголовка, который отображается посредством вызова ф-йй the_title(), он был обёрнут в: <div class="filtered" style="color: red">{the_title}</div>
    return  '<div class="filtered" style="color: green">'. $my_content .'</div>';
}
add_filter('the_content', 'add_style_to_content');

//_____________________________________






?>
