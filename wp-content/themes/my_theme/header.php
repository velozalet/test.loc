<?php
wp_head(); // запускает хук wp_header -один из основных хуков,без которого не будут работать многие плагины. 

$aa= 'Это shortcode,вызванный через Ф-ю в header.php!';
echo "<br>";
echo "<p class='text-center '>".do_shortcode($aa)."</p>"; //echo do_shortcode('[baztag content="eny text....."]');
echo "<br>";
?>