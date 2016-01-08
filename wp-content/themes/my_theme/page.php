<br>
<div class='container-fluid'>  <!-- START__Основная ОБЕРТКА-->

<!--START__HEADER-->
    <div class='col-sm-12'>
        <?php get_header(); ?>
    </div>
<!--END__HEADER-->



<!-- START__ЦЕНТРАЛЬНАЯ КОЛОНКА С КОНТЕНТОМ -->
    <div class='col-sm-7 center-block' id="popup-shadow">


      <?php  wp_nav_menu($args); // Show main_menu  ?>
<?php
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
?>

<hr>
        <?php while ( have_posts() ) : the_post(); ?>
            <h1 class=""><?php the_title(); ?></h1> <!--Выводится заголовок(Title) запрошенной страницы-->
            <?php the_content(); ?> <!--выводится Контент запрошенной страницы-->
            <?php comments_template(); ?> <!--выводится Блок "Добавить комментарий"-->
        <?php endwhile; ?>

        <?php //get_sidebar(); ?>  <!--подключаем,если нужно вывести СайтБар-->

       <?php
        ?>


    </div>
<!-- END__ЦЕНТРАЛЬНАЯ КОЛОНКА С КОНТЕНТОМ -http://test.loc/page_1/#http://test.loc/page_1/#

<!--START__FOOTER-->
    <div class='col-sm-12'>
        <?php get_footer(); ?>
    </div>
<!--END__FOOTER-->

</div> <!-- END__Основная ОБЕРТКА-->
