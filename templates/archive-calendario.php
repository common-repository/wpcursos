<?php
            get_header();		
?>

	<article id="post-<?php the_ID(); ?>" class="container main" style="margin-top: 100px;">



	<?php
                            wpCursos_lista_calendarios(); 
                ?>

	</article>

<?php

	get_footer();

?>