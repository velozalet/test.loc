<div class='center-block'>
	<p class="text-success h3">
<?php
wp_head();
if ( have_posts() ) {
    while (have_posts()) {
        the_post();
        the_title('<div style="color: grey"> <h3>','</h3> </div>');
        the_content(); 
    }
} 
else {
}
?>
	</p>
</div>