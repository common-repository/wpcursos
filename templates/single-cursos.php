<?php

/**
 * Template de cursos
 * @package Wordpress
 * @subpackage WpCursos.Plugin
 * @description Template da página de cursos.
 * @author Caio Vinicius <para@caiovinicius.org>
 */





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

			the_title( '<header class="entry-header"><h1 class="entry-title">', '</h1></header><!-- .entry-header -->' );



		?>



		<div class="post-thumbnail">

			<?php 

				if ( has_post_thumbnail() ) { // check if the post has a Post Thumbnail assigned to it.

  					the_post_thumbnail('full');

				} 

			?>

		</div><!--- post-thumbnail -->



		<div class="entry-content">

			<div class="wpCurso-conteudo">

				<?php

					the_content();

				?>

				<!--- calendario de cursos -->

				<div class="calendario-cursos">

		

		

		<?php

			global $post;



			

			$args = array(

				'post_type' => 'turmas',

				'posts_per_page' => 10,

				'meta_query' => array(

						array(

							'key' => 'curso',

							'value' => $post->ID,

							'compare' => '='

						),

						

						array(

							'key' => 'status',

							'value' => 'lotada',

							'compare' => '!='

						)

					)

			);







			$queryTurmas = new wp_query($args);



			// Verifica se esta configurado para mostrar a calendario



			if (get_option('wpcursos_mostra_calendario') == 1) :



			// verifica se tem postagens

			if($queryTurmas->have_posts()) :



		?>

			<table width="100%">

			<thead>

				<tr>

					<th>Curso</th>

					<th>Data Inicio</th>

					<th>Dias da Semana</th>

					<th>Turno</th>

				</tr>

			</thead>

			<tbody>

		<?php



			while($queryTurmas->have_posts()): $queryTurmas->the_post();



			$custom 	= get_post_custom($post->ID);

			$curso       	= $custom["curso"][0];

			$cursolink   	= $custom["cursolink"][0];

			$dataInicio  	= $custom["dataInicio"][0];

			$dataFim     	= $custom["dataFim"][0];

			$diaSemana   	= $custom["diaSemana"][0];

			$horario     	= $custom["horario"][0];

			$status      	= $custom["status"][0];

		?>

			

		<tr>

			<td>

				<?php echo $post->post_title;?>

			</td>

			<td>

				<?php echo $dataInicio;?>

			</td>

			<td>

				<?php 

							if($diaSemana == 1)
							{
								echo 'Domingos';
							}
							elseif ($diaSemana == 2) {
								echo "Sábados";
							}
							elseif ($diaSemana == 3) {
								echo 'Segunda, Quarta e Sexta';
							}
							elseif ($diaSemana == 4) {
								echo 'Segunda, Quarta, Quinta';
							}
							elseif($diaSemana == 5)
							{
								echo 'Segunda, Quarta, Quinta';
							}
							elseif ($diaSemana == 6) {
								echo 'Terça e Quinta';
							}
						?>

			</td>

			<td>

				<?php 
							if($horario == 1)
							{
								echo "08:00 às 12:00";
							}
							elseif ($horario == 2) {
								echo "08:00 às 13;00";
							}
							elseif ($horario == 3) {
								echo "08:00 às 14:00";
							}
							elseif ($horario == 4) {
								echo "13:00 às 18:00";
							}
							elseif ($horario) {
								echo "18:30 às 22:30";
							}
						?>

			</td>

		</tr>

			

		<?php

			endwhile;



			endif;



			endif;

		?>

		</tbody>

		</table>

	</div><!--- calendario-cursos -->

			</div><!--- conteudo-curso -->

			<div class="wpCurso-meta">

				<p>

					<h3>Duração: </h3> <?php echo $wpCursos_duracao; ?>

				</p>

				<p>

					<h3>Requisitos: </h3> <?php echo $wpCursos_requisitos; ?>

				</p>

				<p>

					<h3>Após este curso: </h3> <?php echo $wpCursos_aposcurso; ?>

				</p>

				<p>

					<h3>Pontos Fortes: </h3> <?php echo $wpCursos_pontosfortes; ?>

				</p>

			</div><!--- meta -->

	</div><!-- .entry-content -->



	</article><!--- container -->

	

<?php



get_footer(); ?>

