<div class='center-block'>
	<p class="text-success h3">
<?php
wp_head();
if ( have_posts() ) {
    while (have_posts()) {
        the_post();
        the_title();
        echo "<div style='color: grey'>"; the_content(); echo "</div>";
    }
} 
else {
}
?>
    </p>
</div>