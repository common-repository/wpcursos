<?php
/**
 * lista de pré-matricula WpCursos
 */

// Fazendo Query de pré-matriculas
global $wpdb;

$nomeTabela =  $wpdb->prefix . 'wpcursos_prematriculas';
$listaAluno = $wpdb->get_results( 
	"
	SELECT * 
	FROM $nomeTabela
	"
);
?>
<div class="wrap">
	<h2>WpCursos Pré-Matriculas</h2>
	<table class="wp-list-table widefat fixed pages">
		<thead>
			<tr>
				<th id="title"  style="" scope="col" align="center">
					Aluno
				</th>
				<th id="taxonomy"  style="" scope="col" align="center">
					E-mail 
				</th>
            				<th id="date"  style="" scope="col" align="center">
            					Telefone
            				</th>
            				<th align="center">
            					Turma Solicitada
            				</th>
            				<th align="center">
            					Observações
            				</th>
            				<th>
            					Data da Pré-matricula
            				</th>
        			</tr>
    		</thead>
    		<tfoot></tfoot>
    		<tbody id="the-list">
    			<?php foreach ($listaAluno as $aluno): ?>
    			<tr>
    				<td align="center">
    					<?php echo $aluno->nome_aluno;?>
    				</td>
    				<td align="center">
    					<?php echo $aluno->email_aluno;?>
    				</td align="center">
    				<td>
    					<?php echo $aluno->telefone_aluno;?>
    				</td>
    				<td align="center">
    					<?php 
    						$turma 	= get_post($aluno->turma_id);
    						$dataInicio 	= get_post_meta($aluno->turma_id, 'dataInicio', true);
    						//print_r($infoTurma);
    						echo 	'<b>' . $turma->post_title . '</b><br>',
    							'<small>Data Inicio: ' . $dataInicio . '</small>';

    					?>
    				</td>	
    				<td align="left">
    					<?php echo $aluno->obs;?>
    				</td>	
    				<td>
    					<?php echo $aluno->data_matricula;?>
    				</td>			
    			</tr>
    			<?php endforeach;?>
    		</tbody>

	</table>
</div><!--- wrap -->