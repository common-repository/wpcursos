<?php



get_header();



	the_post();

	

	$custom 			= get_post_custom($post->ID);

	$wpCursos_duracao		= $custom['wpCursos_duracao'][0];

	$wpCursos_requisitos		= $custom['wpCursos_requisitos'][0];

	$wpCursos_aposcurso	= $custom['wpCursos_aposcurso'][0];

	$wpCursos_pontosfortes	= $custom['wpCursos_pontosfortes'][0];

?>

	<article id="post-<?php the_ID(); ?>" class="container main">

		<?php

			/** 
			* Criando Query para o loop de cursos
			*/

			$args = array( 'post_type' => 'cursos', 'posts_per_page' => 10 );

			$loop = new WP_Query( $args );

			

			while ( $loop->have_posts() ) : $loop->the_post();

		?>

		<div class="custom-post-list-item">

			<div class="title-post">

				<a href="<?php the_permalink();?>" alt="saiba mais sobre o curso <?php the_title();?>" title="saiba mais sobre o curso <?php the_title();?>">

					<?php

						the_title( '<header class="entry-header"><h1 class="entry-title">', '</h1></header><!-- .entry-header -->' );

					?>

				</a>

			</div><!--- title-post -->

			<div class="thumb-post">

				<?php 

					if ( has_post_thumbnail() ) { // check if the post has a Post Thumbnail assigned to it.

  						the_post_thumbnail('full');

					} 

				?>

			</div><!--- thumb-post -->

			<div class="description-post">

				<?php

					the_excerpt();

				?>

			</div><!--- description-post -->

			<div class="meta-post">
				<p>
					<strong>Requisitos: </strong><?php echo $wpCursos_requisitos; ?>
				</p>
				<p>
					<strong>Duração: </strong><?php echo $wpCursos_duracao; ?>
				</p>
				<p>
					<strong>Após Este cursos: </strong><?php echo $wpCursos_aposcurso; ?>
				</p>

			</div><!--- meta-post -->

		</div><!--- custom-post-list-item -->

	

	

		<?php

			endwhile;

		?>

	</article>

<?php

	get_footer();

?>