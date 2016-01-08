<div class='center-block'>
	<p class="text-success h3">
<?php
wp_head();
if ( have_posts() ) {
    while (have_posts()) {
        the_post();
        the_title();
        the_content();
    }
} 
else {
}
?>
	</p>
</div>
