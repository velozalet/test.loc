<?php
wp_footer(); // запускает хук wp_footer -один из основных хуков,без которого не будут работать многие плагины. Этот тег шаблона должен располагаться прямо перед закрывающим HTML тегом </body>. Обычно он используется в файлах шаблона footer.php, index.php.

$qq= 'Это shortcode,вызванный через Ф-ю в footer.php!';
echo "<br>";
echo "<p class='text-center '>".do_shortcode($qq)."</p>"; // echo do_shortcode('[baztag content="eny text....."]');
echo "<br>";
?>