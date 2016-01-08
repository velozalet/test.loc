<br>
<div class='container-fluid'>  <!-- START__Основная ОБЕРТКА-->

<!--START__HEADER--> 
    <div class='col-sm-12'>   
    <?php  get_header();
    //wp_head(); //запускает хук-действие(wp_head).Это Тег шаблона,кот.надо вставлять всегда перед </head>, в файлах темы: header.php или index.php.Без этого стили(styles),скрипты(scripts) и метатеги не подключатся и работпть не будут
?>
    </div>
<!--END__HEADER-->  

<!-- START__ЦЕНТРАЛЬНАЯ КОЛОНКА С КОНТЕНТОМ -->
    <div class='col-sm-7 center-block' id="popup-shadow">

<?php
//-------------------------------------------------1
echo "Main Menu:";
$args = array(
    'theme_location'  => 'primary',
    'menu'            => 'main_menu',
    'container'       => 'div',
    'container_class' => '',
    'container_id'    => '',
    'menu_class'      => 'menu',
    'menu_id'         => '',
    'echo'            => true,
    'fallback_cb'     => 'wp_page_menu',
    'before'          => '',
    'after'           => '',
    'link_before'     => '',
    'link_after'      => '',
    'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
    'depth'           => 0
);

wp_nav_menu($args); // Show main_menu

echo "<br>";

echo "Secondary Menu:";
$args1 = array(
    'theme_location'  => 'primary',
    'menu'            => 'secondary_menu',
    'container'       => 'div',
    'container_class' => '',
    'container_id'    => '',
    'menu_class'      => 'menu',
    'menu_id'         => '',
    'echo'            => true,
    'fallback_cb'     => 'wp_page_menu',
    'before'          => '',
    'after'           => '',
    'link_before'     => '',
    'link_after'      => '',
    'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
    'depth'           => 0
);

wp_nav_menu($args1); // Show secondary_menu
echo "<hr>";
//-------------------------------------------------2
// Вывод "Записей типа "Галерея"c поддержкой картинок-миниатюр(thumbnails)
echo "Записи типа \"Галерея\"c поддержкой картинок-миниатюр(thumbnails):";

$the_query = new WP_Query( array('post_type' => 'thumbnails')); 

if ( $the_query->have_posts() ) {  // проверяем есть ли записи для вывода в объекте $query. 
    echo '<ul>';
    while ( $the_query->have_posts() ) {
        $the_query->the_post(); // готовит текущую в цикле запись к выводу,делая доступными привычные функции the_title(), the_content() и другие.
        echo '<li><a href="/'.$the_query->post->post_name.'">' . get_the_title() . '</a></li>';
    }
    echo '</ul>';
} 
else { echo " Нет записей"; // no posts found
}
wp_reset_postdata();


// Вывод "Записей типа "Комментарии" c поддержкой с поддержкой комментариев(comments)
echo "Записи типа \"Комментарии\" с поддержкой комментариев(comments):";

$the_query = new WP_Query( array('post_type' => 'comments'));

if ( $the_query->have_posts() ) {  // проверяем есть ли записи для вывода в объекте $query. 
    echo '<ul >';
    while ( $the_query->have_posts() ) {
        $the_query->the_post(); // готовит текущую в цикле запись к выводу,делая доступными привычные функции the_title(), the_content() и другие.
        echo '<li><a href="/'.$the_query->post->post_name.'">' . get_the_title() . '</a></li>';
    }
    echo '</ul>';
} 
else { echo " Нет записей"; // no posts found
}
wp_reset_postdata();

// Вывод "Записей типа "Цитаты" иерархический(hierarchical) с поддержкой отрывка (excerpt)
echo "Записи типа \"Цитаты\" иерархический(hierarchical) с поддержкой отрывка (excerpt):";

$the_query = new WP_Query( array('post_type' => 'excerpt'));

if ( $the_query->have_posts() ) {  // проверяем есть ли записи для вывода в объекте $query. 
    echo '<ul >';
    while ( $the_query->have_posts() ) {
        $the_query->the_post(); // готовит текущую в цикле запись к выводу,делая доступными привычные функции the_title(), the_content() и другие.
        echo '<li><a href="/'.$the_query->post->post_name.'">' . get_the_title() . '</a></li>';
    }
    echo '</ul>';
} 
else { echo " Нет записей"; // no posts found
}
wp_reset_postdata();
echo "<hr>";
//-------------------------------------------------3
// Так, WordPress сделает запрос в базу данных, чтобы получить записи из категории "news".
$query = new WP_Query( array( 'category_name' => 'news' ) );
while ( $query->have_posts() ) { // Теперь, переменная $query содержит в себе объект с результатами запроса. Обработаем результат с помощью специальных методов:
    $query->the_post();
    the_title(); // выведем заголовок поста
}


//-------------------------------------------------------------------
/*____Выведит список всех имеющихся страниц в виде ссылок. По-сути это как вывод меню-Навигации по страницам сайта
$my_query = new WP_Query();
$result = $my_query->query( array('post_type' => 'page'));
if($result) {
    foreach( $result as $item ){ echo '<li><a href="/'.$item->post_title.'">'.$item->post_title.'</li>'; }
}
else { echo 'Нет страниц из кот.можно сделать НавМеню'; }  //no page found
*/

//--------------------------TESTING-----------------------------------------
//get_sidebar();  пример подключения сайтбара



//--------------------------TESTING__END----------------------------------------
?>
    </div>
        <!-- <div> Блок вывод Твиттов__START-->
        <div class='col-sm-7 center-block' id="popup-shadow">
            <?php
                echo "<h3 class='text-center '>".do_shortcode('ВЫВОД ТВИТТОВ:')."</h3>";
                echo do_shortcode('[print_twitts content=""]');
            ?>
        <!-- <div> Блок вывод Твиттов__END-->
    </div>
    </div>
<!-- END__ЦЕНТРАЛЬНАЯ КОЛОНКА С КОНТЕНТОМ -->

<!-- START__ПРАВАЯ КОЛОНКА  С НАВБАРОМ -->
    <div class='col-sm-2 pull-right'> 
<?php
//-------------------------------------------------6
// Выводим сайт-бар(панель для виджетов): dynamic_sidebar();
if (is_active_sidebar('main_sidebar')){
    echo '<ul id="main_sidebar">';
        dynamic_sidebar('main_sidebar');
    echo '</ul>';
}
//___________________________________________или:
/*
if(function_exists('dynamic_sidebar')) {
    dynamic_sidebar('Main sidebar');
}
*/
//-------------------------------------------------7 
//$qq= 'Это shortcode,вызванный через Ф-ю в index.php!';
//echo do_shortcode($qq); // echo do_shortcode('[baztag content="eny text....."]');
?>
    </div>
 <!-- END__ПРАВАЯ КОЛОНКА  С НАВБАРОМ-->


<!--START__FOOTER-->
    <div class='col-sm-12'>
<?php
    get_footer(); // подключает файл footer.php из шаблона(темы).Если указано имя в параметре,то будет подключен файл: footer-{name}.php из шаблона темы
?>
   </div>
<!--END__FOOTER-->



</div>  <!-- END__ Основная ОБЕРТКА-->
