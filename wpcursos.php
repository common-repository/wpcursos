<?php
/**
 * Plugin Name: WpCursos
 * Plugin URI: http://caiovinicius.org
 * Description: Gerenciador cursos para Wordpress. Com o WpCuros
 * Version: 1.4
 * Author: Caio Vinicius <para@caiovinicius.org>
 * Author URI: http://caiovinicius.org
 * License: GPLv2 or later
 *
 * Copyright 2014 Caio Vinicius  (email : para@caiovinicius.org)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as 
 * published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */
include ('wpcursos_install.php');

// função de ativação do plugin
function WpCursos_ativa() 
{
	flush_rewrite_rules();
	wpCursos_cria_paginas();
}
// regitro hook de ativação do plugin
register_activation_hook(__FILE__, 'WpCursos_ativa');

// função de desativação do plugin
function WpCursos_desativa() 
{
	flush_rewrite_rules();
}
// registro do hook de desativação
register_deactivation_hook(__FILE__, 'WpCursos_desativa');

/**
 * Inicialização de hooks
 * Inicia todos os hooks e funções
 */
// inicia o registro dos posts do tipo cursos
add_action('init', 'WpCursos_register_cursos');

// instalador de db 
register_activation_hook( __FILE__, 'wpCursos_install' );
/**
 * Custom Post Types Cursos
 *  
 */
function WpCursos_register_cursos() 
{
	$labels = array(
		'name'                            => _x( 'WpCursos', 'post type general name'),
		'singular_name'          => _x( 'Curso', 'post type singular name'),
		'menu_name'              => _x( 'WpCursos', 'admin menu', 'your-plugin-textdomain'),
		'name_admin_bar'      => _x( 'Novo Curso', 'add new on admin bar', 'your-plugin-textdomain'),
		'add_new'                      => _x( 'Novo Curso', 'cursos' ),
		'add_new_item'            => __( 'Novo Curso', 'book', 'your-plugin-textdomain'),
		'new_item'                     => __( 'Novo Curso', 'your-plugin-textdomain' ),
		'edit_item'                     => __( 'Editar Curso', 'your-plugin-textdomain'),
		'view_item'                     => __( 'Visualizar Curso', 'your-plugin-textdomain'),
		'all_items'                       => __( 'Todos os Cursos', 'your-plugin-textdomain'  ),
		'search_items'              => __( 'Buscar Cursos', 'your-plugin-textdomain'),
		'parent_item_colon'    =>'' ,
		'not_found'                    => __( 'Nenhum curso registrado.', 'your-plugin-textdomain'),
		'not_found_in_trash'   => __( 'Nenhum curso na lixeira.', 'your-plugin-textdomain' ),
	);

	$args = array(
		'labels'                                 => $labels,
		'hierarchical'                      => true,
		'description'                      => 'WpCursos - Gerenciador de cursos',
		'supports'                           => array(
						'title', 
						'editor', 
						'thumbnail'
					 ),
		'public'                                 => true,
		'show_ui'                             => true,
		'show_in_menu'                => true,
		'show_in_nav_menus'      => true,
		'publicly_queryable'          => true,
		'exclude_from_search'   => false,
		'has_archive'                      => true,
		'query_var'                          => true,
		'rewrite'            		 => array( 'slug' => 'cursos' ),
		'can_export'                      => true,
		'rewrite'                             => true,
		'capability_type'               => 'post'
	);

	register_post_type('cursos', $args);
}

function WpCursos_cursos_rewrite_flush() 
{
    	WpCursos_register_cursos();
    	flush_rewrite_rules();
}

register_activation_hook( __FILE__, 'WpCursos_cursos_rewrite_flush' );

/**
 * Add campos Personaizados ao cadastro de cursos
 * 
 * Campos Adicionados
 *
 *  Duração do Curso:  Registra a duração do cursos. Geralmente informado em horas.
 *  Requisitos: 		Registra os requisitos necessários para que o aluno possa fazer o curso.
 *  Após este cursos:	Registra o que o aluno será capaz de fazer  após terminar o curso.
 * Pontos fortes:	Registra os pontos fortes do curso.
 *
 * Todos esses campos são disponibilizados na página do curso.
 */

//
add_action("admin_init", "WpCursos_detalhes_cursos");

// Adiciona meta box ao cadastro de cursos
function WpCursos_detalhes_cursos()
{

	add_meta_box("WpCursos_descricao_meta_cursos", "Detalhes do Curso", "WpCursos_descricao_meta_cursos", "cursos", "normal", "low");
}

// Meta box de descrição do curso
function WpCursos_descricao_meta_cursos()
{
	// posts como global
	global $post;

	// Variáveis que serão responsáveis de trabalhar com os campos personalizados.
	$custom 			= get_post_custom($post->ID);
	$wpCursos_duracao		= $custom['wpCursos_duracao'][0];
	$wpCursos_requisitos		= $custom['wpCursos_requisitos'][0];
	$wpCursos_aposcurso	= $custom['wpCursos_aposcurso'][0];
	$wpCursos_pontosfortes	= $custom['wpCursos_pontosfortes'][0];

	/**
	 * Configurações de áreas de texto.
	 * Os campos de Requisitos, Após este curso e Pontos fortes 
	 * possuirão textarea.
	 */

	// Conteúdo das textareas
	$conteudo_requisitos 		= $wpCursos_requisitos;
	$conteudo_aposcurso 	= $wpCursos_aposcurso;
	$conteudo_pontosfortes	= $wpCursos_pontosfortes;

	// ID das textareas
	$requisitos_id 		= 'wpCursos_requisitos';
	$aposcurso_id		= 'wpCursos_aposcurso';
	$pontosfortes_id	= 'wpCursos_pontosfortes';

	// configurações gerais das textareas
	$wp_editor_config = array(
		'textarea_rows' => 4,
		'wpautop'	=> true 
	);

	// Add os campos na página de cadastro dos cursos
	echo '<p><label>Duração do Curso: </label></p>';
	echo '<input type="text" name="wpCursos_duracao" id="wpCursosDuracao" class="form-required" size="20" value="' . $wpCursos_duracao . '"/> <br>' ;

	echo '<p><label>Requisitos: </label> </p>';
	wp_editor($conteudo_requisitos, $requisitos_id, $wp_editor_config);
	// echo '<textarea name="wpCursos_requisitos" rows="5" cols="60">' . $wpCursos_requisitos . '</textarea> <br>';

	echo '<p><label>Após este curso o aluno será capaz de: </label> </p>';
	wp_editor($conteudo_aposcurso, $aposcurso_id, $wp_editor_config);
	//echo '<textarea name="wpCursos_aposcurso" rows="5" cols="60">' . $wpCursos_aposcurso . '</textarea> <br>';

	echo '<p><label>Pontos Fortes do Curso: </label> </p>';
	wp_editor( $conteudo_pontosfortes, $pontosfortes_id , $wp_editor_config);
	//echo '<textarea name="wpCursos_pontosfortes" rows="5" cols="60">' . $wpCursos_pontosfortes . '</textarea> <br>';
}

// Salvando dados dos campos personalizados.
add_action('save_post', 'WpCursos_salva_descricao_cursos');

// função que salva os dados dos campos personalizados no banco de dados.
function WpCursos_salva_descricao_cursos()
{
	global $post;
	// salvando dados
	update_post_meta($post->ID, 'wpCursos_duracao', $_POST['wpCursos_duracao']);
	update_post_meta($post->ID, 'wpCursos_requisitos', $_POST['wpCursos_requisitos']);
	update_post_meta($post->ID, 'wpCursos_aposcurso',	$_POST['wpCursos_aposcurso']);
	update_post_meta($post->ID, 'wpCursos_pontosfortes', $_POST['wpCursos_pontosfortes']);
}

// inicia o registro da taxonomia de categoria de cursos
add_action('init', 'WpCursos_register_categoriaDeCursos');

/**
 * Registrando taxonomia - Categoria de Cursos
 */ 

function WpCursos_register_categoriaDeCursos()
{
	$labels = array(
		'name' 			=> _x('Categoria de Cursos', 'taxonomy general name'),
		'singular_name'	=> _x('Categoria de Cursos', 'taxonomy singular name'),
		'search_items'		=> _x('Busca Categoria', 'busca de categorias'),
		'all_items'		=> _x('Todas as Categorias', 'todas as categorias de cursos'),
		'parent_item'		=> _x('Categoria Pai', 'categoria pai'),
		'parent-item_colon'	=> _x('Categoria Pai', 'coluna da categoria pai'),
		'edit_item'		=> _x('Editar Categoria de Curso', 'editar categoria de cursos'),
		'update_item'		=> _x('Atualizar Categoria de Curso', 'atualizar categoria de cursos'),
		'add_new_item'	=> _x('Nova Categoria de Curso', 'add nova categoria de cursos'),
		'new_item_name'	=> _x('Nova Categoria de Curso', 'nova categoria de cursos'),
		'menu_name'		=> _x('Categorias de Cursos', 'nome do admin'),
	);

	$args = array(
		'hierarchical'		=> true,
		'labels'			=> $labels,	
		'show_ui'		=> true,
		'show_admin_column'	=> true,
		'query_var'		=> true,
		'rewrite'		=> array(
						'slug' => 'categoriacurso'
					),
	);

	register_taxonomy('categoriacurso', array('cursos'), $args);
}

/**
 * Calendários de cursos
 * O calendário de cursos será constituído da seguinte forma.
 * Calendário:  Mês e Ano. - Formato de Taxonomia
 * Turma de Curso: Dados da turma de um curso que irá abrir - Formato de Post Customizado.
 */

// Inicia o registro de tipo de post de turmas
add_action('init', 'WpCursos_register_turmas');

/**
 * Tipo de Post Turmas
 */
function WpCursos_register_turmas() 
{
	$labels = array(
		'name'                             => _x( 'Calendário de turmas', 'post type general name'),
		'singular_name'            => _x( 'Calendário de turmas', 'post type singular name'),
		'menu_name'                => _x( 'Calendário de turmas', 'admin menu', 'your-plugin-textdomain'),
		'name_admin_bar'        => _x( 'Nova Turma', 'add new on admin bar', 'your-plugin-textdomain'),
		'add_new'                       => _x( 'Nova Turma', 'cursos' ),
		'add_new_item'            => __( 'Nova Turma', 'cursos', 'your-plugin-textdomain'),
		'new_item'                     => __( 'Nova Turma', 'your-plugin-textdomain' ),
		'edit_item'                     => __( 'Editar Turma', 'your-plugin-textdomain'),
		'view_item'                     => __( 'Visualizar Turma', 'your-plugin-textdomain'),
		'all_items'                       => __( 'Todas as Turmas', 'your-plugin-textdomain'  ),
		'search_items'              => __( 'Buscar Turma', 'your-plugin-textdomain'),
		'parent_item_colon'    =>'cursos' ,
		'not_found'                    => __( 'Nenhuma turma registrada.', 'your-plugin-textdomain'),
		'not_found_in_trash'   => __( 'Nenhuma turma na lixeira.', 'your-plugin-textdomain' ),
	);

	$args = array(
		'labels'                                 => $labels,
		'hierarchical'                      => true,
		'description'                      => 'Calendário de Turmas',
		'supports'                           => array(
						'title'
					 ),
		'public'                                 => true,
		'show_ui'                             => true,
		'show_in_menu'                => true,
		'show_in_nav_menus'      => true,
		'publicly_queryable'          => true,
		'exclude_from_search'   => false,
		'has_archive'                      => true,
		'query_var'                          => true,
		'rewrite'            		 => array( 'slug' => 'turmas' ),
		'can_export'                      => true,
		'rewrite'                             => true,
		'capability_type'               => 'post'
	);

	register_post_type('turmas', $args);
}

// add ação de meta box de detalhes da turma
add_action("admin_init", "WpCursos_detalhes_turmas");

// add meta box do formulário de turmas.
function wpCursos_detalhes_turmas(){

	add_meta_box("WpCursos_detalhes_meta_turmas", "Detalhes da Turma", "WpCursos_detalhes_meta_turmas", "turmas", "normal", "low");
}

// Formulário de turmas
function WpCursos_detalhes_meta_turmas()
{
	global $post;

	// Variáveis que serão responsáveis de trabalhar com os campos personalizados.
	$custom 	= get_post_custom($post->ID);
	$curso       	= $custom["curso"][0];
	$cursolink   	= $custom["cursolink"][0];
	$dataInicio  	= $custom["dataInicio"][0];
	$dataFim     	= $custom["dataFim"][0];
	$diaSemana   	= $custom["diaSemana"][0];
	$horario     	= $custom["horario"][0];
	$status      	= $custom["status"][0];
?>
<!--- add bibliotecas -->
<link rel="stylesheet" type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/themes/base/jquery-ui.css" media="all" />
<script type="text/javascript" src="http://code.jquery.com/jquery-1.8.2.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/jquery-ui.min.js"></script>

<!--- Ajax Script -->
<script type="text/javascript">
	$(function() {
		// date picker
		$( "#from" ).datepicker({
			defaultDate: "+1w",
			changeMonth: true,
			dateFormat: "dd/mm/yy",
			numberOfMonths: 3,
			onSelect: function( selectedDate ) {
				$( "#to" ).datepicker( "option", "minDate", selectedDate );

				var name = $("select#curso option:selected").text();
				var day = $("select#mes option:selected").val();
				var inicio = $("#from").val();
				$("#title").val(name + ' - (' + inicio + ')');
			}
		});
		// colocando data final a partir da data inicial.
		$( "#to" ).datepicker({
			defaultDate: "+1w",
			changeMonth: true,
			dateFormat: "dd/mm/yy",
			numberOfMonths: 3,
			onSelect: function( selectedDate ) {
				$( "#from" ).datepicker( "option", "maxDate", selectedDate );
			}
		});

		/**
		 * Preenchendo o título da turma.
		 * O título da turma é escrito pelo nome do curso + data inicio Preenchido via ajax
		 * a medida que o usuário preenche o formulário.
		 */
		$("select#curso").change(function(){

			var name = $("select#curso option:selected").text();
			var inicio = $("#from").val();	
			// preenche titulo do post com nome do curso + data inicio
			$("#title").val(name + ' -  (' + inicio + ')');
		});	

		$("select#curso").change(function(){

			var urlink = $("select#curso option:selected").attr('data-link');	
			// preenche o campo de link do curso com o atributo data-link imbutido no option da selectbox
			$("#cursolink").val(urlink );
		});
	});
</script>

<?php
	/**
	 * Formulário de cadastro de turmas
	 * 
	 * Informações sobre dias da Semana
	 * 
	 * Dias da Semana
	 * 1 - Domingos
	 * 2 - Sabádos
	 * 3 - Segunda, Quarta e Sexta
	 * 4 - Segunda à Sexta
	 * 5 - Segunda, Quarta e Quinta
	 * 6 - Terça e Quinta
	 *
	 * Horários
	 * 1 - 08:00 às 12:00
	 * 2 - 08:00 às 13:00
	 * 3 - 08:00 às 14:00
	 * 4 - 13:00 às 18:00
	 * 5 - 18:30 às 22:30
	 */
	
	// Pegando todos os posts do tipo cursos e armazenando em uma variável
	$posts = get_posts(array('post_type'=> 'cursos', 'posts_per_page' => -1));
	// Select com todos os cursos cadastrados
	echo '<label>Curso</label><br>';
	echo '<select name="curso" id="curso">';
	echo '<option value = "" >Selecione um Curso </option>';

	foreach ($posts as $post):
	?>
	
	<option value="<?php echo $post->ID;?> " data-link=" <?php the_permalink();?>" <?php if($curso == $post->ID) { echo 'selected="selected"';} ?>><?php echo $post->post_title;?> </option>
	<?php
	endforeach;	
	echo "</select><br>";

	// Campo de link de cursos. Esse campo é gerado automáticamente via ajax
	echo '<label>Link do Curso</label><br>';
	echo '<input type="text" size="70" name="cursolink" value="' . $cursolink . '" id="cursolink"/><br>';
	// Campo de data início do curso
	echo '<label>Data Início</label><br>';
	echo '<input type="text" size="70" name="dataInicio" value="' . $dataInicio . '" id="from"/><br>';
	// Campe de data final do curso
	echo '<label>Data Final</label><br>';
	echo '<input type="text" size="70" name="dataFim" value="' . $dataFim . '" id="to"/><br>';
	// Camp de dia da semana
	echo '<label>Dias da Semana</label><br>';
	echo '<select  name="diaSemana">';

		echo '<option value="1"';
			if($diaSemana == 1)   
			{
				echo 'selected="selected"';
			} 
		echo '>Domingo</option>';

		echo '<option value="2"';
			if($diaSemana == 2)   
			{
				echo 'selected="selected"';
			}
		echo '>Sábados</option>';

		echo '<option value="3"';
			if($diaSemana == 3)   
			{
				echo 'selected="selected"';
			}
		echo '>Segunda, Quarta e Sexta</option>';

		echo '<option value="4"';
			if($diaSemana == 4)   
			{
				echo 'selected="selected"';
			}
		echo '>Segunda à Sexta</option>';

		echo '<option value="5"';
			if($diaSemana == 5)   
			{
				echo 'selected="selected"';
			}
		echo '>Segunda, Quarta e Quinta</option>';

		echo '<option value="6"';
			if($diaSemana == 6)   
			{
				echo 'selected="selected"';
			}
		echo '>Terça e Quinta</option>';

	echo '</select><br>'; // fim da select de dias da semana

	// select de horário
	echo '<label>Horário</label><br>';
	echo '<select name="horario">';

		echo '<option value="1"';
			if($diaSemana == 1)   
			{
				echo 'selected="selected"';
			}
		echo '>08:00 às 12:00</option>';

		echo '<option value="2"';
			if($diaSemana == 2)   
			{
				echo 'selected="selected"';
			}
		echo '>08:00 às 13:00</option>';

		echo '<option value="3"';
			if($diaSemana == 3)   
			{
				echo 'selected="selected"';
			}
		echo '>08:00 às 14:00</option>';

		echo '<option value="4"';
			if($diaSemana == 4)   
			{	
				echo 'selected="selected"';
			}
		echo '>13:00 às 18:00</option>';

		echo '<option value="5"';
			if($diaSemana == 5)   
			{
				echo 'selected="selected"';
			}
		echo '>18:00 às 22:30</option>';

	echo '</select><br>'; // fim da select de horário

	// Select de status
	echo '<label>Status</label><br>';
	echo '<select name="status">';

		echo '<option value="aberta"';
			if($status == 'aberta')   
			{
				echo 'selected="selected"';
			}
		echo '>Aberta</option>';

		echo '<option value="lotado"';
			if($status == 'lotada')   
			{
				echo 'selected="selected"';
			}
		echo '>Lotada</option>';

		echo '<option value="1 vaga"';
			if($status == '1 vaga')   
			{
				echo 'selected="selected"';
			}
		echo '>1 vaga</option>';

		echo '<option value="2 vagas"';
			if($status == '2 vagas')   
			{
				echo 'selected="selected"';
			}
		echo '>2 vagas</option>';

		echo '<option value="3 vagas"';
			if($status == '3 vagas')   
			{
				echo 'selected="selected"';
			}
		echo '>3 vagas</option>';

		echo '<option value="4 vagas"';
			if($status == '4 vaga')   
			{
				echo 'selected="selected"';
			}
		echo '>4 vagas</option>';

		echo '<option value="5 vagas"';
			if($status == '5 vagas')   
			{
				echo 'selected="selected"';
			}
		echo '>5 vagas</option>';

		echo '<option value="6 vagas"';
			if($status == '6 vagas')   
			{
				echo 'selected="selected"';
			}
		echo '>6 vagas</option>';

		echo '<option value="7 vagas"';
			if($status == '7 vagas')   
			{
				echo 'selected="selected"';
			}
		echo '>7 vagas</option>';

		echo '<option value="8 vagas"';
			if($status == '8 vagas')   
			{
				echo 'selected="selected"';
			}
		echo '>8 vagas</option>';

		echo '<option value="9 vagas"';
			if($status == '9 vagas')   
			{
				echo 'selected="selected"';
			}
		echo '>9 vagas</option>';

		echo '<option value="10 vagas"';
			if($status == '10 vagas')   
			{
				echo 'selected="selected"';
			}
		echo '>10 vagas</option>';

		echo '<option value="11 vagas"';
			if($status == '11 vagas')   
			{
				echo 'selected="selected"';
			}
		echo '>11 vagas</option>';

		echo '<option value="12 vagas"';
			if($status == '12 vagas')   
			{
				echo 'selected="selected"';
			}
		echo '>12 vagas</option>';

            echo '</select><br>';
            



}

// Salvando dados dos campos personalizados.
add_action('save_post', 'WpCursos_salva_descricao_turmas');

// função que salva os dados dos campos personalizados no banco de dados.
function WpCursos_salva_descricao_turmas()
{
	/*
		$curso
		$cursolink
		$dataInicio
		$dataFim
		$diaSemana
		$horario
		$status
	*/
	global $post;
	// salvando dados
	update_post_meta($post->ID, 'curso', $_POST['curso']);
	update_post_meta($post->ID, 'cursolink', $_POST['cursolink']);
	update_post_meta($post->ID, 'dataInicio', $_POST['dataInicio']);
	update_post_meta($post->ID, 'dataFim', $_POST['dataFim']);
	update_post_meta($post->ID, 'diaSemana', $_POST['diaSemana']);
	update_post_meta($post->ID, 'horario', $_POST['horario']);
	update_post_meta($post->ID, 'status', $_POST['status']);
}

// Editar colunas da lista de turmas
add_filter( 'manage_edit-turmas_columns', 'wpCursos_colunas_turmas' ) ;

function wpCursos_colunas_turmas( $columns ) {

	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __( 'Turma' ),
		'calendario' => __( 'Calendário' ),
		'date' => __( 'Data' )
	);

	return $columns;
}

add_action( 'manage_turmas_posts_custom_column', 'wpCursos_colunas_lista_de_turmas', 10, 2 );

function wpCursos_colunas_lista_de_turmas( $column, $post_id ) {
	global $post;

	switch( $column ) {

		case 'calendario' :

			$terms = get_the_terms( $post_id, 'calendario' );

			if ( !empty( $terms ) ) {

				$out = array();

				foreach ( $terms as $term ) {
					$out[] = sprintf( '<a href="%s">%s</a>',
						esc_url( add_query_arg( array( 'post_type' => $post->post_type, 'calendario' => $term->slug ), 'edit.php' ) ),
						esc_html( sanitize_term_field( 'name', $term->name, $term->term_id, 'calendario', 'display' ) )
					);
				}

				echo join( ', ', $out );
			}

			else {
				_e( 'Esta turma não esta vinculada a nenhum calendário' );
			}

			break;

		default :
			break;
	}
}

// inicia o registro da taxonomia de categoria de cursos
add_action('init', 'WpCursos_register_calendario');

// Registrando taxonomia de Calendário
function WpCursos_register_calendario()
{
	$labels = array(
		'name' 			=> _x('Calendário', 'taxonomy general name'),
		'singular_name'		=> _x('Calendário de Cursos', 'taxonomy singular name'),
		'search_items'		=> _x('Busca Calendário', 'busca de categorias'),
		'all_items'		=> _x('Todas os Calendários', 'todas as categorias de cursos'),
		'parent_item'		=> _x('Calendário Pai', 'categoria pai'),
		'parent-item_colon'	=> _x('Calendário Pai', 'coluna da categoria pai'),
		'edit_item'		=> _x('Editar Calendário de Curso', 'editar categoria de cursos'),
		'update_item'		=> _x('Atualizar Calendário de Curso', 'atualizar categoria de cursos'),
		'add_new_item'	=> _x('Novo Calendário de Curso', 'add nova categoria de cursos'),
		'new_item_name'	=> _x('Novo Calendário de Curso', 'nova categoria de cursos'),
		'menu_name'		=> _x('Calendários de Curso', 'nome do admin'),
	);

	$args = array(
		'hierarchical'		=> true,
		'labels'			=> $labels,	
		'show_ui'		=> true,
		'show_admin_column'	=> true,
		'query_var'		=> true,
		'rewrite'		=> array(
						'slug' => 'calendario'
					),
	);

	register_taxonomy('calendario', array('turmas'), $args);
}

/**
 * Templates
 * Add templates de cursos e turmas ao plugin.
 */

add_filter('template_include', 'wpCursos_cursos_template');

function wpCursos_cursos_template( $template ) {

	global $post;
	
	// adiciono template para lista de cursos
	if ( is_post_type_archive('cursos') ) 
	{
		$archive_template = array('archive-WpCursos_cursos.php', 'gerenciadorcursos/templates/archive-cursos.php');

		$exists_in_theme = locate_template($archive_template, false);

		if ( $exists_in_theme != '' ) {
			return $exists_in_theme;
		} 
		else 
		{
			return plugin_dir_path(__FILE__) . 'templates/archive-cursos.php';
    		}
  	}
  	// adiociono template para lista de calendarios
  	
  	elseif ( is_tax('calendario') ) 
	{
		$archive_template = array('archive-WpCursos_calendario.php', 'gerenciadorcursos/templates/archive-calendario.php');

		$exists_in_theme = locate_template($archive_template, false);

		if ( $exists_in_theme != '' ) {
			return $exists_in_theme;
		} 
		else 
		{
			return plugin_dir_path(__FILE__) . 'templates/archive-calendario.php';
    		}
  	}

  	// adiciona template para página de curso
  	elseif ( $post->post_type == 'cursos' ) 
	{
		$single_template = array('gerenciadorcursos/templates/single-cursos.php');

		$exists_in_theme = locate_template($single_template, false);

		if ( $exists_in_theme != '' ) {
			return $exists_in_theme;
		} 
		else 
		{
			return plugin_dir_path(__FILE__) . 'templates/single-cursos.php';
    		}
  	}
  	return $template;
}

/**
 * Add folha de estilo ao plugin
 */
function wpCursos_add_css_js() {
	wp_enqueue_style(
		'newscript',
		plugins_url( '/assets/css/wpCursos_style.css' , __FILE__ )
	);
}

add_action( 'wp_enqueue_scripts', 'wpCursos_add_css_js' );

/**
 * Cria página de cursos no processo de instalação
 */

function wpCursos_cria_paginas() {

	global $wpdb;

	$paginaCursos = array(
  		'post_title'    	=> 'Cursos',
  		'post_content'	=> '',
  		'post_status'   	=> 'publish',
  		'post_author'   => 1,
  		'post_type'        => 'page',
	);
	// Salvando a página no banco de dados.
	wp_insert_post( $paginaCursos );
}


/**
 * Configurações Gerais WpCursos
 */

function wpCursos_register_settings() {
	add_option( 'wpcursos_mostra_calendario', '1');
	add_option('wpcursos_email_notificacao', '');
	register_setting( 'default', 'wpcursos_mostra_calendario' ); 
	register_setting( 'default', 'wpcursos_email_notificacao' ); 
} 
add_action( 'admin_init', 'wpCursos_register_settings' );
 
function wpCursos_register_config_page() {
	add_options_page('Configurações WpCursos', 'Configurações WpCursos', 'manage_options', 'wpcursos-options', 'wpCursos_config_page');
}
add_action('admin_menu', 'wpCursos_register_config_page');
 
function wpCursos_config_page() {
	?>
<div class="wrap">
	<?php screen_icon(); ?>
	<h2>Configurações WpCursos</h2>
	<form method="post" action="options.php"> 
		<?php settings_fields( 'default' ); ?>
		<h3>Configurações Gerais</h3>
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label for="wpcursos_mostra_calendario">Mostrar Turmas na página de Cursos</label></th>
					<td>
						<?php
							$opcaoSelecionada = get_option('wpcursos_mostra_calendario');
						?>
						<select name="wpcursos_mostra_calendario" id="wpcursos_mostra_calendario">
							<option value="0" <?php if($opcaoSelecionada == 0) { echo 'selected="selected"'; } ?>>
								Não
							</option>
							<option value="1" <?php if($opcaoSelecionada == 1) { echo 'selected="selected"'; } ?>>
								Sim
							</option>
						</select>
						<p>
							Marcando como Sim, no final da página do curso, é motrados todas as turmas em aberto para este curso.
						</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="wpcursos_email_notificacao">Mostrar Turmas na página de Cursos</label></th>
					<td>
						<input type="email" name="wpcursos_email_notificacao" id="	" value="<?php echo get_option('wpcursos_email_notificacao');?>">						
						<p>
							Defina um e-mail para ser notificado quando um aluno fizer uma pré-matricula.
						</p>
					</td>
				</tr>
			</table>
		<?php submit_button(); ?>
	</form>
</div>
<?php
}

function wpCursos_lista_calendarios() {

	$tax_current = get_queried_object();
	$post_type = 'turmas';
	$tax = 'calendario';
	$tax_terms = get_terms($tax, array('parent' => $tax_current->term_id, 'orderby' => 'ID', 'order' => 'ASC'));
	

	if ($tax_terms) 
	{
  		foreach ($tax_terms  as $tax_term) {
    			$args=array(
      				'post_type' => $post_type,
      				"$tax" => $tax_term->slug,
      				'orderby' => 'ID',
    				'order' => 'ASC',
      				'post_status' => 'publish',
      				'posts_per_page' => -1,
      				'ignore_sticky_posts'=> 1,
      				'child_of' => $tax_current->term_id,

    			);

    		$queryCalendarios = null;
    		
    		$queryCalendarios = new WP_Query($args);
    			if( $queryCalendarios->have_posts() ) 
    			{
      			
      			echo '<h3>' . $tax_term->name . '</h3>',
      			 '<table class="table table-wpc">',
      				'<thead>',
      					'<tr>',
      						'<th>',
      							'Curso',
      						'</th>',
      						'<th>',
      							'Data Início',
      						'</th>',
      						'<th>',
      							'Dias da Semana',
      						'</th>',
      						'<th>',
      							'Turno',
      						'</th>',
      						'<th>',
      							'Status',
      						'</th>',
      					'</tr>',
      				'</thead>',
      				'<tbody>';

      			//echo 'List of '.$post_type . ' where the taxonomy '. $tax . '  is '. $tax_term->name;
      				while ($queryCalendarios->have_posts()) : $queryCalendarios->the_post(); 

      				global $post;

				// Variáveis que serão responsáveis de trabalhar com os campos personalizados.
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
						<?php echo $curso;?>
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
					<td>
						<?php echo $status;?>
					</td>
				</tr>
        				<!--- <p>
        					<a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>">
        						<?php the_title(); ?>
        					</a>
        				</p> -->
			<?php
				endwhile;
      			?>
				</tbody>
        			</table>
		<?php
			}
			wp_reset_query();
		}
	}

}



function WpCursos_lista_turmas ($atts) {

	ob_start();

	extract(shortcode_atts(array(
			'categoria' 	=> '',
			'titulo'		=> '',
			'ordem'	=> '',
			'ordenar_por'	=> ''

		), $atts
	));

	$opcoesQuery = new WP_query( array(
			'post_type'		=> 'turmas',
			'calendario'		=> $categoria,
			'posts_per_page' 	=> -1,
			'order'			=> $ordem,
			'orderby'		=> $ordenar_por

		)
	);

	if($opcoesQuery->have_posts()) {
	
	
	$output = '<h2> ' . $titulo . '</h2>';
	$output .= '<table class="table table-wpc">';
	$output .= '<thead>';
	$output .= '<tr>';
	$output .= '<th> Nome do Curso </th>';
	$output .= '<th> Data Inicio </th>';
	$output .= '<th> Dias da Semana </th>';
	$output .= '<th> Turno </th>';
	$output .= '<th> Status </th>';
	$output .= '</tr>';
	$output .= ' </thead>';
	$output .= '<tbody>';
	
				while ( $opcoesQuery->have_posts() ) : $opcoesQuery->the_post();

				global $post;

				// Variáveis que serão responsáveis de trabalhar com os campos personalizados.
				$custom 	= get_post_custom($post->ID);
				$curso       	= $custom["curso"][0];
				$cursolink   	= $custom["cursolink"][0];
				$dataInicio  	= $custom["dataInicio"][0];
				$dataFim     	= $custom["dataFim"][0];
				$diaSemana   	= $custom["diaSemana"][0];
				$horario     	= $custom["horario"][0];
				$status      	= $custom["status"][0];

				$nomeCurso = $post->post_title;
	
	$output .= '<tr>';
	$output .= '<td> <a href=" ' . $cursolink . '">' . $nomeCurso . '</a></td>';
	$output .= '<td> ' .  $dataInicio . '</td>';
	$output .= '<td>'; 
	if($diaSemana == 1)
	{
	$output .= 'Domingos';
	}
	elseif ($diaSemana == 2) 
	{
	$output .= 'Sábados';
	}
	elseif ($diaSemana == 3) 
	{
	$output .='Segunda, Quarta e Sexta';
	}
	elseif ($diaSemana == 4) 
	{
	$output .= 'Segunda, Quarta, Quinta';
	}
	elseif($diaSemana == 5)
	{
	$output .= 'Segunda, Quarta, Quinta';
	}
	elseif ($diaSemana == 6) 
	{
	$output .= 'Terça e Quinta';
	}
	$output .= '</td>';
	$output .= '<td>';
	
	if($horario == 1)
	{
	$output .= '08:00 às 12:00';
	}
	elseif ($horario == 2) 
	{
	$output .= '08:00 às 13;00';
	}
	elseif ($horario == 3) 
	{	
	$output .= '08:00 às 14:00';
	}
	elseif ($horario == 4) 
	{
	$output .= '13:00 às 18:00';
	}
	elseif ($horario) 
	{
	$output .= '18:30 às 22:30';
	}
	
	$output .= '</td>';
	$output .= '<td> ' . $status . '</td>';
	$output .= '</tr>';
	
	endwhile;
	
	$output .= '</tbody>';
	$output .= '</table>';

	} // fim da query

	return $output;
}

add_shortcode('calendario', 'WpCursos_shortcode_turmas');

function WpCursos_shortcode_turmas() {

	return WpCursos_lista_turmas($atts);
}

function wpCursos_add_prematricula_form($atts) {

	global $post;

	extract(shortcode_atts(array(
			'curso_id' 	=> '',
		), $atts
	));

	$posts = get_posts(array(
		'post_type'=> 'turmas', 
		'posts_per_page' => -1, 
		'meta_query' => array(
				'key' => 'curso',
				'value' => $curso_id,
				'compare' => '='
			)
		)
	);


	


	if(empty($curso_id))
	{
		$curso_id = $post->ID;


	}
	

	$output = '<form action="'.plugins_url('/wpcursos/wpcursos_processa_forms.php', '') . '" method="post">';
	$output .= '<input type="hidden" name="acao" value="wpcursos_add_prematricula" />';
	$output .= '<label for="nome_aluno">Nome:</label><br>';
	$output .= '<input type="text" name="nome_aluno" id="nome_aluno" /><br>';
	$output .= '<label for="email_aluno">E-mail Aluno:</label><br>';
	$output .= '<input type="text" name="email_aluno" id="email_aluno" /><br>';
	$output .= '<label for="fone_aluno">Telefone:</label><br>';
	$output .= '<input type="text" name="fone_aluno" id="fone_aluno" /><br>';
	$output .= '<label for="turma_id">Selecione a Turma de seu interesse:</label><br>';
	$output .= '<select name="turma_id" id="turma_id">';
	foreach ($posts as $post) :

	$diaSemana 	= $post->diaSemana;
	$horario 	= $post->horario;


	if($diaSemana == 1)
	{
	$dia = 'Domingos';
	}
	elseif ($diaSemana == 2) 
	{
	$dia = 'Sábados';
	}
	elseif ($diaSemana == 3) 
	{
	$dia ='Segunda, Quarta e Sexta';
	}
	elseif ($diaSemana == 4) 
	{
	$dia = 'Segunda, Quarta, Quinta';
	}
	elseif($diaSemana == 5)
	{
	$dia = 'Segunda, Quarta, Quinta';
	}
	elseif ($diaSemana == 6) 
	{
	$dia = 'Terça e Quinta';
	}
		
	if($horario == 1)
	{
	$hora = '08:00 às 12:00';
	}
	elseif ($horario == 2) 
	{
	$hora = '08:00 às 13;00';
	}
	elseif ($horario == 3) 
	{	
	$hora = '08:00 às 14:00';
	}
	elseif ($horario == 4) 
	{
	$hora = '13:00 às 18:00';
	}
	elseif ($horario) 
	{
	$hora = '18:30 às 22:30';
	}

	$output .= '<option value="' . $post->ID. '">' . $post->post_title . ' - ' . $dia. ' - ' .  $hora. '</option>';
	endforeach;
	$output .= '</select><br>';
	$output .= '<label for="obs">Observações: </label><br>';
	$output .= '<textarea name="obs" id="obs" cols="30" rows="10"></textarea><br>';
	$output .= '<input type="submit" id="submit" value="Fazer Pré-matricula" />';
	$output .= '</form>';

	return $output;

}

add_shortcode('form-matricula', 'wpCursos_add_prematricula_form');

// Add subpágina de lista de Pré-matriculas
add_action( 'admin_menu', 'wpCursos_add_matriculas_page' );

function wpCursos_add_matriculas_page (){
    add_menu_page( 'WpCursos Pré-matriculas', 'WpCursos Pré-matriculas', 'manage_options', 'wpcursos/manage_matriculas.php', '', '', 30 );
}

