<?php

function dataBR(){

    setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
    date_default_timezone_set('America/Sao_Paulo');
    echo utf8_encode(strftime('%A, %d de %B de %Y', strtotime('today')));

}

function formataData($data){
    return date('d/m/Y', strtotime($data));
}

function reduzData($data){
    return date('Y-m-d', strtotime($data));
}

function formataMes($data){
    return date('m/y', strtotime($data));
}

function formataDateTime($data){
    return date('d/m/Y H:i:s', strtotime($data));
}

function aniversario($data){
    return date('d/m', strtotime($data));
}

function formataDataSQL($data){

    if($data==''){
        $date = "0000-00-00";
    } else {
        $date = "2022-10-27";
    }
    
}

function formataCPF($cpf){

    $cpf1 = substr($cpf,0,3);
    $cpf2 = substr($cpf,3,3);
    $cpf3 = substr($cpf,6,3);
    $cpf4 = substr($cpf,9,2);

    $cpf = "$cpf1.$cpf2.$cpf3-$cpf4";

    return $cpf;
}

function formataNB($nb){

    $nb1 = substr($nb,0,3);
    $nb2 = substr($nb,3,3);
    $nb3 = substr($nb,6,3);
    $nb4 = substr($nb,9,1);

    $nb = "$nb1.$nb2.$nb3-$nb4";

    return $nb;
}

function calculaData($data){
    $d1 = new DateTime('now');
    $d2 = new DateTime($data);
    $intervalo = $d1->diff( $d2 );
    //echo "Diferença de " . $intervalo->d . " dias";
    //echo " e " . $intervalo->m . " mese s";
    //echo " e " . $intervalo->y . " anos.";

    return $intervalo->y. " anos";
}

function calculaDataFim($data){
    $d1 = new DateTime('now');
    $d2 = new DateTime($data);
    $intervalo = $d1->diff( $d2 );
    return "Restam ".$intervalo->m. " meses e ".$intervalo->d. " dias";
}

function iconeFunction($data){
    $icone = substr($data, 0, 1);
    echo "<a title='$data'><div class='iconeFunction'>$icone</div></a>";
}

function formataPhone($data){

        $ddd = substr($data, 0, 2);
        $phone01 = substr($data, 2, 5);
        $phone02 = substr($data, 7, 4);

        return "($ddd) $phone01-$phone02";
}

function newName($data){
    $nome = $data;

    $temp = explode(" ",$nome);

    $nomeNovo = $temp[0] . " " . $temp[count($temp)-1];

    return $nomeNovo;
}

function ola(){

    setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
    date_default_timezone_set('America/Sao_Paulo');
    $hora_do_dia=date("H");

/*uso de condicionais, poderíamos utilizar o switch também*/

if (($hora_do_dia >=6) && ($hora_do_dia <=12)) return "Bom dia";
if (($hora_do_dia >12) && ($hora_do_dia <=18)) return "Boa tarde";
if (($hora_do_dia >18) && ($hora_do_dia <=24)) return "Boa noite";
if (($hora_do_dia >24) && ($hora_do_dia <6)) return "Boa madrugada";

}

function modality($data){

    $modality = $data;

    switch ($modality) {
        case 1: $modality = "Presencial";
        break;
        case 2: $modality = "Semipresencial";
        break;
        case 3: $modality = "Teletrabalho";
        break;
        case 4: $modality = "Estagiário";
        break;
    }

    return $modality;

}

function myArray($data){

    $obj = json_encode($data);
    $myArray = json_decode($obj,true);

    return $myArray;

}

function typeText($type){

    switch($type){
		case 1:
			$typeText = "Anexos GET - Originais";
			break;
		case 2:
			$typeText = "Anexos GET - Simples";
			break;
		case 3:
			$typeText = "Anexos GET - Terceiros";
			break;
		case 4:
			$typeText = "Procuradores - RG/OAB";
			break;
		case 5:
			$typeText = "Procuração";
			break;
		case 6:
			$typeText = "Cópia de Processo";
			break;                
		case 7:
			$typeText = "Dossiê MOB";
			break;                
		case 8:
			$typeText = "Ofícios";
			break;
		case 9:
			$typeText = "Documentos Diversos";
			break;
	}

    return $typeText;

}

function formataValor($valor){

    $nvalor = number_format($valor, 2, ',','.');
    return $nvalor;
}

function arredondar($num){
    $num_redondo = round($num, 1);
    return $num_redondo;
}

function corDaMesa($status){
    if($status==1){
        $cor = "#656565";
    } else {
        $cor = "#fff";
    }

    return $cor;
}