<?php
/**
 *
 */
require_once('../../../wp-config.php');

global $wpdb;

$action = $_POST['acao'];

// $turma_id = $_GET['turma_id'];

$nomeTabela = $wpdb->prefix . 'wpcursos_prematriculas';

$nomealuno 	= $_POST['nome_aluno'];
$emailaluno 	= $_POST['email_aluno'];
$fonealuno  	= $_POST['fone_aluno'] ;
$obs		= $_POST['obs'];
$dataMatricula = date('Y-m-d h:i:s');
$turma_id	= $_POST['turma_id'];

$nomeSite = bloginfo('admin_email');

// pegando dados da turma

$turma = get_post($turma_id);

$custom 	= get_post_custom($turma_id);
$diaSemana 	= $custom['diaSemana'][0];
$horario 	= $custom['horario'][0];

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



/**
 * E-mail de notificação
 */
$headers []	='';
$headers []	="MIME-Version: 1.0 \r\n";
$headers []	="Content-type: text/html; charset=\"UTF-8\" \r\n";
$headers [] 	= 'From: ' . bloginfo('name') . ' <' . bloginfo('admin_email') . '>';
$para 		= get_option('wpcursos_email_notificacao');
$assunto 	= "Nova pré-matricula ";
$body		= 'Nova Pré-matricula para a turma  ' . $turma->post_title . ' com ínicio em ' . $custom['dataInicio'][0]. ', ' . $hora . ' de ' . $dia . ' .<br />';
$body		.= '<strong>Nome: </strong>' . $nomealuno . '.<br>';
$body		.= '<strong>E-mail: </strong>' . $emailaluno . '.<br>';
$body		.= '<strong>Telefone: </strong>' . $fonealuno . '.<br>';

$body		= '<table width="100%" style="border: 1px solid #ccc;">';
$body		.='<tr><td style="background: #f0f0f0; color:#000; text-align: center;"><h3>Nova Pré-Matricula para ' . $turma->post_title . '</h3></td></tr>';
$body		.='<tr><td style="background: #f5f5f5;">';
$body		.='<table><tr><td width="10%"><b> Horário: </b></td>';
$body		.='<td width="20%">' . $hora . '</td>';
$body		.='<td width="20%"> <b>Dias da Semana: </b></td>';
$body		.='<td width="25%">' . $dia . '</td>';
$body		.='<td width="10%"> <b>Início: </b></td>';
$body		.='<td width="15%">' . $custom['dataInicio'][0] . '</td></tr>';
$body		.='</table></td></tr>';
$body		.='<tr><td><table style="margin-top: 20px;"><tr>';
$body		.='<td width="30%"><b>Nome: </b></td>';
$body		.='<td width="70%">' . $nomealuno . '</td></tr>';
$body		.='<tr><td width="30%"><b>E-mail:</b> </td>';
$body		.='<td  width="70%">' . $emailaluno . '</td></tr>';
$body		.='<tr><td width="30%"><b>Telefone: </b></td>';
$body		.='<td  width="70%">' . $fonealuno . '</td></tr>';
$body		.='<tr><td width="30%"><b>Observações: </b></td>';
$body		.='<td  width="70%">' . $obs . '</td></tr>';
$body		.='</table></td></tr><tr><td style="background: #f0f0f0; text-align: center; padding: 5px 0;">Enviado por <a href="http://caiovinicius.org" target="_blank">WpCursos</a></td></tr></table>';

wp_mail( $to, $subject, $message, $headers );

switch ($action)
{
	case "wpcursos_add_prematricula":

	$wpdb->insert($nomeTabela, array(
		'id' => null
		, 'nome_aluno' => $nomealuno
		, 'email_aluno' => $emailaluno
		, 'telefone_aluno' => $fonealuno
		, 'turma_id' => $turma_id
		, 'data_matricula' => $dataMatricula
		,'obs' => $obs
		)
	);

	wp_mail($para, $assunto, $body, $headers);
?>
	<script>
            		alert("Sua Pré-matricula foi realizada com sucesso!");
            		window.location = "<?=get_option("siteurl");?>";
        </script>
<?php	

	break;
}


