<?php 

require_once("vendor/autoload.php");
require("functions.php");

use App\Caixa;
use \Slim\Slim;
use \App\Page;
use \App\Mesas;
use App\Clientes;
use App\Lancamentos;
use App\Pedidos;
use App\Produtos;
use App\Categorias;
use App\Despesas;
use App\Top5;

$app = new Slim();

$app->config('debug', true);

$app->get('/', function() {	

	$hoje = date('Y-m-d');

	// pesquisas todas as mesas
	$mesas = Mesas::getAll();

	// Verifica se existe caixa de outro dia ainda aberto
	$caixa_aberto = Caixa::caixa_aberto();

	// Verifica se o caixa de hoje foi aberto
	$caixa = Caixa::buscaPorDia($hoje);

	//echo "<pre>"; print_r($caixa_aberto); echo "</pre>"; exit;
	
	$page = new Page();
	$page->setTpl("mesas",[
		'mesas'=>$mesas,
		'caixa'=>$caixa,
		'caixa_aberto'=>$caixa_aberto
	]);

});

$app->get('/mesas', function() {

	$hoje = date('Y-m-d');

	// pesquisas todas as mesas
	$mesas = Mesas::getAll();

	// Verifica se existe caixa de outro dia ainda aberto
	$caixa_aberto = Caixa::caixa_aberto();

	// Verifica se o caixa de hoje foi aberto
	$caixa = Caixa::buscaPorDia($hoje);

	//echo "<pre>"; print_r($caixa_aberto); echo "</pre>"; exit;
	
	$page = new Page();
	$page->setTpl("mesas",[
		'mesas'=>$mesas,
		'caixa'=>$caixa,
		'caixa_aberto'=>$caixa_aberto
	]);

});

$app->get('/vendas', function() {

	$hoje = date('Y-m-d');
	$mes = date('Y-m');
	$msg = "Vendas de Hoje";
	$valorPedidos = '';

	// pesquisar todos os pedidos fechados HOJE
	$pedidos = Pedidos::getAll('status = 1 AND data_fim LIKE "%'.$hoje.'%"', null, 'data_fim DESC', null);
	
	if(isset($pedidos)){
		// Pesquisar valores das vendas
		$vendasDoDia = Pedidos::getSum('data_fim LIKE "%'.$hoje.'%"');
		$vendasDoMes = Pedidos::getSum('data_fim LIKE "%'.$mes.'%"');

		// Pesquisar o lucro das vendas do dia
		$lucroDoDia = Pedidos::getSumLucro('data_fim LIKE "%'.$hoje.'%"');
		
		if($lucroDoDia!=0){
			$percLucroDoDia = round($lucroDoDia / $vendasDoDia * 100);
		} else {
			$lucroDoDia = 0;
			$percLucroDoDia = 0;
		}		
		
		// Pesquisa o lucro das vendas do mês
		$lucroDoMes = Pedidos::getSumLucro('data_fim LIKE "%'.$mes.'%"');
		$percLucroDoMes = round($lucroDoMes / $vendasDoMes * 100);
	} else {
		
		$pedidos = '';
		$vendasDoDia = 0;
		$lucroDoDia = 0;
		$percLucroDoDia = 0;

		// Verifica as vendas e os lucros do MÊS		
		$vendasDoMes = Pedidos::getSum('data_fim LIKE "%'.$mes.'%"');
		//$counterMes = count($vendasDoMes);

		if(isset($vendasDoMes)){
			// Pesquisa o lucro das vendas do mês
			$lucroDoMes = Pedidos::getSumLucro('data_fim LIKE "%'.$mes.'%"');
			$percLucroDoMes = round($lucroDoMes / $vendasDoMes * 100);
		} else {
			$lucroDoMes = 0;
			$percLucroDoMes = 0;
		}
		
	}
	
	
	// Pesquisa todos os produtos
	$produtos = Produtos::getAll(null, 'nome', null, null);	

	// Produtos com maior faturamento
	$top5 = Lancamentos::getTop();

	// Total das vendas no Top 5
	$valorTop5 = 0;
	foreach ($top5 as $key => $value) {
		$valor = $value['valor'];
		$valorTop5 = $valor + $valorTop5;
	}	
	
	$page = new Page();
	$page->setTpl("vendas",[
		'pedidos'=>$pedidos,
		'vendasDoDia'=>$vendasDoDia,
		'lucroDoDia'=>$lucroDoDia,
		'percLucroDoDia'=>$percLucroDoDia,
		'vendasDoMes'=>$vendasDoMes,
		'lucroDoMes'=>$lucroDoMes,
		'percLucroDoMes'=>$percLucroDoMes,
		'top5'=>$top5,
		'valorTop5'=>$valorTop5,
		'msg'=>$msg,
		'produtos'=>$produtos,
		'valorPedidos'=>$valorPedidos
	]);

});

$app->get('/pesquisar', function() {

	// Pesquisa todos os produtos
	$produtos = Produtos::getAll(null, 'nome', null, null);

	$hoje = date('Y-m-d');
	$mes = date('Y-m');

	// Pesquisar valores das vendas
	$vendasDoDia = Pedidos::getSum('data_fim LIKE "%'.$hoje.'%"');
	$vendasDoMes = Pedidos::getSum('data_fim LIKE "%'.$mes.'%"');
	
	// Pesquisar o lucro das vendas do dia
	$lucroDoDia = Pedidos::getSumLucro('data_fim LIKE "%'.$hoje.'%"');
	if($lucroDoDia!=0){
		$percLucroDoDia = round($lucroDoDia / $vendasDoDia * 100);
	} else {
		$lucroDoDia = 0;
		$percLucroDoDia = 0;
	}
	
	// Pesquisa o lucro das vendas do mês
	$lucroDoMes = Pedidos::getSumLucro('data_fim LIKE "%'.$mes.'%"');
	$percLucroDoMes = round($lucroDoMes / $vendasDoMes * 100);


	//pesquisa por data
	if(isset($_GET['data'])) {

		$data = $_GET['data'];
		$msg = "Vendas do Dia ".formataData($data);

		// pesquisar todos os pedidos fechados
		$pedidos = Pedidos::getAll('status = 1 AND data_fim LIKE "%'.$data.'%"', null, 'data_fim DESC', null);		

		if($pedidos !=0){

			$valorPedidos = Pedidos::getSum('data_fim LIKE "%'.$data.'%"');
		
			// Pesquisar o lucro das vendas do dia
			$lucroData = Pedidos::getSumLucro('data_fim LIKE "%'.$data.'%"');
			$percLucroData = round($lucroData / $valorPedidos * 100);

		} else {

			$valorPedidos = 0;
		
			// Pesquisar o lucro das vendas do dia
			$lucroData = 0;
			$percLucroData = 0;
		}

		
	}

	//pesquisa por número do pedido
	if(isset($_GET['pedido'])) {

		$pedido = $_GET['pedido'];
		$msg = "Pedido nº $pedido";

		// pesquisar todos os pedidos fechados
		$pedidos = Pedidos::getAll('num_pedido = '.$pedido, null, 'data_fim DESC', null);
		$valorPedidos = Pedidos::getSum('num_pedido = '.$pedido);
		
		// Pesquisar o lucro das vendas do dia
		$lucroData = Pedidos::getSumLucro('num_pedido = '.$pedido);
		$percLucroData = round($lucroData / $valorPedidos * 100);
	}

	//pesquisa por produto
	if(isset($_GET['produto'])) {

		$produto = $_GET['produto'];
		
		//pesquisa o nome do produto
		$n = Produtos::getByID($produto);
		$nomeProduto = $n[0]['nome'];
		$msg = "Vendas ".strtoupper($nomeProduto);		
		
		// Pesqusar os lançamentos relativos ao produto selecionado
		$lancamentos = myArray(Lancamentos::getByProduto($produto));
		
		// Soma os valores das vendas do produto selecionado
		$valorPedidos = Lancamentos::sumByProduto($produto);
		
		$counter = count($lancamentos);

		if($counter!=0){
			foreach ($lancamentos as $key => $value) {

				$pedido = $value['num_pedido'];
	
				// pesquisar todos os pedidos fechados
				$sql = Pedidos::getAll('num_pedido = '.$pedido, null, 'data_fim ASC', null);

				// Pesquisar o lucro das vendas do dia
				$lucroData = 0;
				$percLucroData = 0;
				
				$nome_cliente = $sql[0]['nome_cliente'];
				$num_pedido = $sql[0]['num_pedido'];
				$pgto_total = $sql[0]['pgto_total'];
				$data_fim = $sql[0]['data_fim'];
	
				// Transforma em Array
				$pedidos[] = [				
					'nome_cliente'=>$nome_cliente,
					'num_pedido'=>$num_pedido,
					'pgto_total'=>$pgto_total,
					'data_fim'=>$data_fim
				];
	
				
			}
		} else {
			$pedidos = '';
		}
				

				
	}
	
	//pesquisa por Cliente
	if(isset($_GET['cliente'])) {

		$cliente = $_GET['cliente'];

		$msg = "Vendas para o cliente " .strtoupper($cliente);
		$lucroData = 0;
		$percLucroData = 0;

		// Pesquisa o ID do cliente
		$sqlCliente = Clientes::getByNome($cliente);
		$count = count($sqlCliente);
		
		if($count!=0){
			$clienteID = $sqlCliente[0]['id'];
			// pesquisar todos os pedidos fechados
			$pedidos = Pedidos::getAll('status = 1 AND cliente = "'.$clienteID.'"', null, 'data_fim DESC', null);		
			
			
			if($pedidos !=0){

				$valorPedidos = Pedidos::getSum('status = 1 AND cliente = "'.$clienteID.'"');
			
				// Pesquisar o lucro das vendas do dia
				$lucroData = Pedidos::getSumLucro('status = 1 AND cliente = "'.$clienteID.'"');
				$percLucroData = round($lucroData / $valorPedidos * 100);
	
			} else {
	
				$valorPedidos = 0;
			
				// Pesquisar o lucro das vendas do dia
				$lucroData = 0;
				$percLucroData = 0;
			}
			
			
		} else {
			$pedidos = 0;
			$valorPedidos = 0;
			$lucroData = 0;
			$percLucroData = 0;
		}
		
		
	}
    
	$hoje = date('Y-m-d');
	$mes = date('Y-m');	

	// Pesquisar valores das vendas
	$vendasDoDia = Pedidos::getSum('data_fim LIKE "%'.$hoje.'%"');
	$vendasDoMes = Pedidos::getSum('data_fim LIKE "%'.$mes.'%"');	

	$top5 = Lancamentos::getTop();

	// Total das vendas no Top 5
	$valorTop5 = 0;
	foreach ($top5 as $key => $value) {
		$valor = $value['valor'];
		$valorTop5 = $valor + $valorTop5;
	}
	
	
	$page = new Page();
	$page->setTpl("vendas",[
		'pedidos'=>$pedidos,
		'vendasDoDia'=>$vendasDoDia,
		'lucroDoDia'=>$lucroDoDia,
		'percLucroDoDia'=>$percLucroDoDia,
		'vendasDoMes'=>$vendasDoMes,
		'lucroDoMes'=>$lucroDoMes,
		'percLucroDoMes'=>$percLucroDoMes,
		'top5'=>$top5,
		'valorTop5'=>$valorTop5,
		'msg'=>$msg,
		'produtos'=>$produtos,
		'valorPedidos'=>$valorPedidos,
		'lucroData'=>$lucroData,
		'percLucroData'=>$percLucroData
	]);

});

$app->get('/categorias', function() {

	// Pesquisa as Categorias
	$categorias = myArray(Categorias::getAll());

	// Pesquisa todos os produtos
	$produtos = myArray(Produtos::getAll());

	$page = new Page();
	$page->setTpl("categorias",[
		"categorias"=>$categorias,
		"produtos"=>$produtos
	]);

});

$app->get('/financeiro', function() {

	// Verifica o mês atual
	$mesAtual = date('Y-m');

	// Hoje
	$hoje = date('Y-m-d');

	// Verifica se existe caixa de outro dia ainda aberto
	$caixa_aberto = Caixa::caixa_aberto();

	// Verifica se o caixa de hoje foi aberto
	$caixa = Caixa::buscaPorDia($hoje);

	// Pesquisa os lançamentos manuais de ENTRADA
	$entradas = Caixa::getEntradas();

	// Pesquisa os lançamentos manuais de SAÍDAS
	$saidas = Caixa::getSaidas();

	//echo "<pre>"; print_r($saidas); echo "</pre>"; exit;

	// Pesquisas os últimos 3 caixas fechados
	$fechamentos = Caixa::fechamentos();

	// Caso pesquise caixa do dia anterior
	if(isset($_GET['dia_anterior'])){
		$caixaDiaAnterior = Caixa::buscaPorDia($_GET['dia_anterior']);
	} else {
		$caixaDiaAnterior = 0;
	}
	

	// Top5despesas
	$top5despesas = Despesas::getTopDespesas();
	$countDespesas = count($top5despesas);

	if($countDespesas==0){
		$top5despesas = 0;
		$valorTop5 = 0;
	} else {

		// Total das vendas no Top 5
		$valorTop5 = 0;
		foreach ($top5despesas as $key => $value) {
			$valor = $value['valor'];
			$valorTop5 = $valor + $valorTop5;
		}
	}		

	// Define os meses da pesquisa
	$mes01 = date('Y-m', strtotime('-11 months', strtotime($mesAtual)));
	$mes02 = date('Y-m', strtotime('-10 months', strtotime($mesAtual)));
	$mes03 = date('Y-m', strtotime('-9 months', strtotime($mesAtual)));
	$mes04 = date('Y-m', strtotime('-8 months', strtotime($mesAtual)));
	$mes05 = date('Y-m', strtotime('-7 months', strtotime($mesAtual)));
	$mes06 = date('Y-m', strtotime('-6 months', strtotime($mesAtual)));
	$mes07 = date('Y-m', strtotime('-5 months', strtotime($mesAtual)));
	$mes08 = date('Y-m', strtotime('-4 months', strtotime($mesAtual)));
	$mes09 = date('Y-m', strtotime('-3 months', strtotime($mesAtual)));
	$mes10 = date('Y-m', strtotime('-2 months', strtotime($mesAtual)));
	$mes11 = date('Y-m', strtotime('-1 months', strtotime($mesAtual)));

	// Pesquisa as vendas por modalidade de pagamento
	$vendasDinheiro = Pedidos::getSumPgto('pgto_dinheiro');
	$vendasPix = Pedidos::getSumPgto('pgto_pix');
	$vendasDebito = Pedidos::getSumPgto('pgto_debito');
	$vendasCredito = Pedidos::getSumPgto('pgto_credito');

	$vendasTotal = $vendasDinheiro + $vendasPix + $vendasDebito + $vendasCredito;

	// Percentuais
	$percDinheiro = round($vendasDinheiro / $vendasTotal * 100);
	$percPix = round($vendasPix / $vendasTotal * 100);
	$percDebito = round($vendasDebito / $vendasTotal * 100);
	$percCredito = round($vendasCredito / $vendasTotal * 100);

	//echo "<pre>"; print_r($vendasDinheiro); echo "</pre>"; exit;	
	
	// Pesquisa o faturamento de cada mês
	$faturamentoMes01 = Pedidos::getSum('data_fim LIKE "%'.$mes01.'%"');
	$faturamentoMes02 = Pedidos::getSum('data_fim LIKE "%'.$mes02.'%"');
	$faturamentoMes03 = Pedidos::getSum('data_fim LIKE "%'.$mes03.'%"');
	$faturamentoMes04 = Pedidos::getSum('data_fim LIKE "%'.$mes04.'%"');
	$faturamentoMes05 = Pedidos::getSum('data_fim LIKE "%'.$mes05.'%"');
	$faturamentoMes06 = Pedidos::getSum('data_fim LIKE "%'.$mes06.'%"');
	$faturamentoMes07 = Pedidos::getSum('data_fim LIKE "%'.$mes07.'%"');
	$faturamentoMes08 = Pedidos::getSum('data_fim LIKE "%'.$mes08.'%"');
	$faturamentoMes09 = Pedidos::getSum('data_fim LIKE "%'.$mes09.'%"');
	$faturamentoMes10 = Pedidos::getSum('data_fim LIKE "%'.$mes10.'%"');
	$faturamentoMes11 = Pedidos::getSum('data_fim LIKE "%'.$mes11.'%"');
	$faturamentoMesAtual = Pedidos::getSum('data_fim LIKE "%'.$mesAtual.'%"');

	// Pesquisa o lucro de cada mês
	$lucroMes01 = Pedidos::getSumLucro('data_fim LIKE "%'.$mes01.'%"');
	$lucroMes02 = Pedidos::getSumLucro('data_fim LIKE "%'.$mes02.'%"');
	$lucroMes03 = Pedidos::getSumLucro('data_fim LIKE "%'.$mes03.'%"');
	$lucroMes04 = Pedidos::getSumLucro('data_fim LIKE "%'.$mes04.'%"');
	$lucroMes05 = Pedidos::getSumLucro('data_fim LIKE "%'.$mes05.'%"');
	$lucroMes06 = Pedidos::getSumLucro('data_fim LIKE "%'.$mes06.'%"');
	$lucroMes07 = Pedidos::getSumLucro('data_fim LIKE "%'.$mes07.'%"');
	$lucroMes08 = Pedidos::getSumLucro('data_fim LIKE "%'.$mes08.'%"');
	$lucroMes09 = Pedidos::getSumLucro('data_fim LIKE "%'.$mes09.'%"');
	$lucroMes10 = Pedidos::getSumLucro('data_fim LIKE "%'.$mes10.'%"');
	$lucroMes11 = Pedidos::getSumLucro('data_fim LIKE "%'.$mes11.'%"');
	$lucroMesAtual = Pedidos::getSumLucro('data_fim LIKE "%'.$mesAtual.'%"');

	// Pesquisa as despesas de cada mês
	$despesaMes01 = Despesas::sum12meses('data LIKE "%'.$mes01.'%"');
	$despesaMes02 = Despesas::sum12meses('data LIKE "%'.$mes02.'%"');
	$despesaMes03 = Despesas::sum12meses('data LIKE "%'.$mes03.'%"');
	$despesaMes04 = Despesas::sum12meses('data LIKE "%'.$mes04.'%"');
	$despesaMes05 = Despesas::sum12meses('data LIKE "%'.$mes05.'%"');
	$despesaMes06 = Despesas::sum12meses('data LIKE "%'.$mes06.'%"');
	$despesaMes07 = Despesas::sum12meses('data LIKE "%'.$mes07.'%"');
	$despesaMes08 = Despesas::sum12meses('data LIKE "%'.$mes08.'%"');
	$despesaMes09 = Despesas::sum12meses('data LIKE "%'.$mes09.'%"');
	$despesaMes10 = Despesas::sum12meses('data LIKE "%'.$mes10.'%"');
	$despesaMes11 = Despesas::sum12meses('data LIKE "%'.$mes11.'%"');
	$despesaMesAtual = Despesas::sum12meses('data LIKE "%'.$mesAtual.'%"');
	
	$page = new Page();
	$page->setTpl("financeiro",[
		"caixa"=>$caixa,
		"faturamentoMes01"=>$faturamentoMes01,
		"faturamentoMes02"=>$faturamentoMes02,
		"faturamentoMes03"=>$faturamentoMes03,
		"faturamentoMes04"=>$faturamentoMes04,
		"faturamentoMes05"=>$faturamentoMes05,
		"faturamentoMes06"=>$faturamentoMes06,
		"faturamentoMes07"=>$faturamentoMes07,
		"faturamentoMes08"=>$faturamentoMes08,
		"faturamentoMes09"=>$faturamentoMes09,
		"faturamentoMes10"=>$faturamentoMes10,
		"faturamentoMes11"=>$faturamentoMes11,
		"faturamentoMesAtual"=>$faturamentoMesAtual,
		"lucroMes01"=>$lucroMes01,
		"lucroMes02"=>$lucroMes02,
		"lucroMes03"=>$lucroMes03,
		"lucroMes04"=>$lucroMes04,
		"lucroMes05"=>$lucroMes05,
		"lucroMes06"=>$lucroMes06,
		"lucroMes07"=>$lucroMes07,
		"lucroMes08"=>$lucroMes08,
		"lucroMes09"=>$lucroMes09,
		"lucroMes10"=>$lucroMes10,
		"lucroMes11"=>$lucroMes11,
		"lucroMesAtual"=>$lucroMesAtual,
		"despesaMes01"=>$despesaMes01,
		"despesaMes02"=>$despesaMes02,
		"despesaMes03"=>$despesaMes03,
		"despesaMes04"=>$despesaMes04,
		"despesaMes05"=>$despesaMes05,
		"despesaMes06"=>$despesaMes06,
		"despesaMes07"=>$despesaMes07,
		"despesaMes08"=>$despesaMes08,
		"despesaMes09"=>$despesaMes09,
		"despesaMes10"=>$despesaMes10,
		"despesaMes11"=>$despesaMes11,
		"despesaMesAtual"=>$despesaMesAtual,
		"mes01"=>formataMes($mes01),
		"mes02"=>formataMes($mes02),
		"mes03"=>formataMes($mes03),
		"mes04"=>formataMes($mes04),
		"mes05"=>formataMes($mes05),
		"mes06"=>formataMes($mes06),
		"mes07"=>formataMes($mes07),
		"mes08"=>formataMes($mes08),
		"mes09"=>formataMes($mes09),
		"mes10"=>formataMes($mes10),
		"mes11"=>formataMes($mes11),
		"mes12"=>formataMes($mesAtual),
		"vendasDinheiro"=>$vendasDinheiro,
		"vendasPix"=>$vendasPix,
		"vendasDebito"=>$vendasDebito,
		"vendasCredito"=>$vendasCredito,
		"percDinheiro"=>$percDinheiro,
		"percPix"=>$percPix,
		"percDebito"=>$percDebito,
		"percCredito"=>$percCredito,
		"caixa_aberto"=>$caixa_aberto,
		"fechamentos"=>$fechamentos,
		"top5despesas"=>$top5despesas,
		"entradas"=>$entradas,
		"saidas"=>$saidas,
		"caixa_dia_anterior"=>$caixaDiaAnterior
	]);	


});

$app->get('/excluir_saida', function() {		
    
	$saida = Caixa::excluirSaida($_GET);

	header("Location: http://192.168.0.118/adegamodelo/financeiro");
	exit;


});

$app->get('/excluir_entrada', function() {
	
	// Verifica se a entrada a ser excluída é referene a pagamento de pedido
	$pattern = "/Pagamento em dinheiro Pedido n/";
	$subject = $_GET['descricao'];
	$matches = array();		
	$resultado = preg_match($pattern, $subject, $matches);	

	// Caso seja relacionada a pedido, não permite a exclusão
	if($resultado == 1){
		header("Location: http://192.168.0.118/adegamodelo/financeiro#alerta-excluir-entrada");
		exit;
	} else {
		$entrada = Caixa::excluirEntrada($_GET);
		header("Location: http://192.168.0.118/adegamodelo/financeiro");
		exit;
	}	

});

$app->get('/caixa-anterior', function() {
    
	header("Location: http://192.168.0.118/adegamodelo/financeiro#moda-caixa-anterior");
	exit;

});

$app->get('/caixa-dia-anterior', function() {

	$data = $_GET['data'];

	$caixa = Caixa::buscaPorDia($data);
    
	header("Location: http://192.168.0.118/adegamodelo/financeiro#modal-caixa-dia-anterior");
	exit;

});

$app->get('/clientes', function() {
    
	$page = new Page();
	$page->setTpl("clientes");

});


$app->get('/produtos', function() {
    
	$page = new Page();
	$page->setTpl("produtos");

});


$app->get('/relatorios', function() {
    
	$page = new Page();
	$page->setTpl("relatorios");

});


$app->get('/abrir_caixa', function() {

	$valor = $_GET['valor_inicial'];
	$abrir = Caixa::abrir($valor);
    
	header("Location: http://192.168.0.118/adegamodelo/financeiro");
	exit;

});

$app->get('/lanca_despesa', function() {

	$valor = $_GET['valor'];
	$data = $_GET['data'];
	$descricao = $_GET['descricao'];

	$salvar = Despesas::salva($valor, $descricao, $data);
    
	header("Location: http://192.168.0.118/adegamodelo/financeiro");
	exit;

});

$app->get('/reabrir_caixa', function() {

	$hoje = date('Y-m-d');

	// Saldo do caixa a ser reaberto
	$caixa = Caixa::buscaPorDia($hoje);
	$saldo_inicial = $caixa[0]['saldo_inicial'];
	$entradas = $caixa[0]['entradas'];
	$saidas = $caixa[0]['saidas'];
	$saldo_final = $saldo_inicial + $entradas - $saidas;	

	$reabrir = Caixa::reabrir($saldo_final);
    
	header("Location: http://192.168.0.118/adegamodelo/financeiro");
	exit;

});

$app->get('/atualizar_caixa', function() {

	$valor = $_GET['valor'];	
	$atualizar = Caixa::atualizar($valor);
    
	header("Location: http://192.168.0.118/adegamodelo/financeiro");
	exit;

});

$app->get('/caixa_entrada', function() {

	$valor = $_GET['valor'];
	$descricao = $_GET['descricao'];
	$entrada = Caixa::entrada($valor, $descricao);
    
	header("Location: http://192.168.0.118/adegamodelo/financeiro");
	exit;

});

$app->get('/caixa_saida', function() {

	$valor = $_GET['valor'];
	$descricao = $_GET['descricao'];
	$saida = Caixa::saida($valor, $descricao);
    
	header("Location: http://192.168.0.118/adegamodelo/financeiro");
	exit;

});

$app->get('/caixa_saida', function() {

	$valor = $_GET['valor'];
	$descricao = $_GET['descricao'];
	$saida = Caixa::saida($valor, $descricao);
    
	header("Location: http://192.168.0.118/adegamodelo/financeiro");
	exit;

});

$app->get('/fechar_caixa', function() {	

	$data_abertura = $_GET['data_abertura'];
	$saldo_final = $_GET['saldo_final'];
	$conferencia = $_GET['conferencia'];
	$observacao = $_GET['observacao'];
	$diferenca = $conferencia - $saldo_final;
	$fechar = Caixa::fechar($data_abertura, $conferencia, $diferenca, $observacao);
    
	header("Location: http://192.168.0.118/adegamodelo/financeiro");
	exit;

});

$app->get('/mesa/:id', function($id) {

	// Pesquisa as Categorias
	$categorias = myArray(Categorias::getAll());

	// Pesquisa todos os produtos
	$produtos = myArray(Produtos::getAll());

	// Pesquisa todos os cliente
	$clientes = myArray(Clientes::getClientes());

	// pesquisas as informações da mesa selecionada
	$mesa = myArray(Mesas::getByID($id));
	$status = $mesa[0]['status'];

	//echo "<pre>"; print_r($mesa); echo "</pre>"; exit;

	$pedido = $mesa[0]['num_pedido'];

	// Pesquisa as informações do Pedido
	$pedidoDetalhado = myArray(Pedidos::getByID($pedido));	

	if($pedido!=0){
		$pedidoData = myArray(Lancamentos::getByPedido($pedido));

		//echo "<pre>"; print_r($pedidoData); echo "</pre>"; exit;

		// Subtotal do Pedido
		$s = 0;
		foreach ($pedidoData as $key => $value) {
			$subtotalProduto = $value['subtotalProduto'];
			$s = $s + $subtotalProduto;
		}
		
		// Valores
		$subtotalPedido = $s;
		$desconto = Lancamentos::getDescontos($pedido);
		$acrescimo = Lancamentos::getAcrescimos($pedido);
		$adiantamento = Lancamentos::getAdiantamentos($pedido);
		$valor_total = $subtotalPedido + $acrescimo - $desconto - $adiantamento;

		
		
		$page = new Page();
		$page->setTpl("mesa",[
			"mesa"=>$mesa,
			"status"=>$status,
			"pedidoData"=>$pedidoData,
			'pedidoDetalhado'=>$pedidoDetalhado,
			"subtotalPedido"=>$subtotalPedido,
			"desconto"=>$desconto,
			"acrescimo"=>$acrescimo,
			"adiantamento"=>$adiantamento,
			"valor_total"=>$valor_total,
			"categorias"=>$categorias,
			"produtos"=>$produtos,
			"clientes"=>$clientes
		]);
	} else {

		$page = new Page();
		$page->setTpl("mesa",[
			"mesa"=>$mesa,
			"status"=>$status,
			"categorias"=>$categorias,
			"produtos"=>$produtos,
			"clientes"=>$clientes,
			"subtotalPedido"=>0,
			"desconto"=>0,
			"acrescimo"=>0,
			"adiantamento"=>0,
			"valor_total"=>0,
			'pedidoDetalhado'=>''
		]);

	}

});

$app->get('/pedido/:id', function($id) {

	//$data = date('Y-m-d H:i:s');

	//$d = date('d/m/Y H:i:s', strtotime($data));
	
	$pedidos = Pedidos::getByID($id);
	
	// Verifica se as vendas foram tarifadas pela operadora de cartão
	$vlrDebito = $pedidos[0]['pgto_debito'];
	if($vlrDebito!=0){
		$tarifaDebito = arredondar($vlrDebito * 0.0199);
	} else {
		$tarifaDebito = 0;
	}	

	$vlrCredito = $pedidos[0]['pgto_credito'];
	if($vlrCredito!=0){
		$tarifaCredito = arredondar($vlrCredito * 0.0199);
	} else {
		$tarifaCredito = 0;
	}

	$tarifas = $tarifaCredito + $tarifaDebito;
	
	if($pedidos!=0){
		$num_pedido = $pedidos[0]['num_pedido'];
	} else {
		$num_pedido = 0;
	}

	$lancamentos = Lancamentos::getByPedido($num_pedido);

	// Calcular Lucro do Pedido
	$lucro = 0;
	foreach ($lancamentos as $key => $value) {		
		$lucro = $lucro + $value['subtotalLucro'];		
	}

	$lucro_liquido = $lucro - $pedidos[0]['descontos'];

	$pgto_total = $pedidos[0]['pgto_total'];
	$lucro_perc = round($lucro / $pgto_total * 100);

	$cliente = myArray(Clientes::getByID($pedidos[0]['cliente']));
	$nomeCliente = $cliente['nome'];

	//echo "<pre>"; print_r($lucro_perc); echo "</pre>"; exit;

	
	$page = new Page();
	$page->setTpl("pedido",[		
		"pedidos"=>$pedidos,
		"lancamentos"=>$lancamentos,
		"nomeCliente"=>$nomeCliente,
		"lucro_liquido"=>$lucro_liquido,
		"lucro_perc"=>$lucro_perc,
		"tarifas"=>$tarifas
	]);

});

$app->get('/pedido/imprimir/:id', function($id) {
    
	$page = new Page();
	$page->setTpl("imprimir_pedido",[
		"num_pedido"=>$id
	]);

});

$app->get('/pedido/excluir/:id', function($id) {
    
	$page = new Page();
	$page->setTpl("imprimir_pedido",[
		"num_pedido"=>$id
	]);

});

$app->get('/pedido/editar/:id', function($id) {
    
	$page = new Page();
	$page->setTpl("imprimir_pedido",[
		"num_pedido"=>$id
	]);

});

$app->get('/mesa/print/:id', function($id) {
    
	$page = new Page();
	$page->setTpl("imprimir_pedido",[
		"num_pedido"=>$id
	]);

});

$app->get('/add', function() {

	$mesa = $_GET['mesa'];
	$pedido = $_GET['pedido'];
	$produto = $_GET['produto'];
	$tab = $_GET['tab'];
	$clienteID = $_GET['clienteID'];

	$lancamento = Lancamentos::create($mesa, $pedido, $produto, $clienteID);

	//echo "<pre>"; print_r($cliente); echo "</pre>"; exit;
    
	header("Location: http://192.168.0.118/adegamodelo/mesa/".$mesa."#tab".$tab."");
	exit;

});

$app->get('/remove', function() {

	$mesa = $_GET['mesa'];
	$pedido = $_GET['pedido'];
	$produto = $_GET['produto'];
	$tab = $_GET['tab'];
	$clienteID = $_GET['clienteID'];

	$lancamento = Lancamentos::remove($mesa, $pedido, $produto, $clienteID);

	//echo "<pre>"; print_r($cliente); echo "</pre>"; exit;
    
	header("Location: http://192.168.0.118/adegamodelo/mesa/".$mesa."#tab".$tab."");
	exit;

});

$app->get('/remover_produto', function() {

	$mesa = $_GET['mesa'];
	$pedido = $_GET['pedido'];
	$produto = $_GET['produto'];
	$clienteID = $_GET['clienteID'];

	$lancamento = Lancamentos::removeAll($pedido, $produto, $mesa, $clienteID);	
    
	header("Location: http://192.168.0.118/adegamodelo/mesa/".$mesa);
	exit;

});

$app->get('/excluir_pedido', function() {

	$mesa = $_GET['mesa'];
	$pedido = $_GET['pedido'];
	$clienteID = $_GET['clienteID'];

	$del_pedido = Pedidos::delete($pedido, $mesa, $clienteID);	
    
	header("Location: http://192.168.0.118/adegamodelo/mesa/".$mesa);
	exit;

});

$app->get('/desconto', function() {

	$mesa = $_GET['mesaID'];
	$pedido = $_GET['pedidoID'];
	$opcao = $_GET['opcao'];
	$subtotalPedido = $_GET['subtotalPedido'];
	$desc = $_GET['desconto'];
	$desconto = round(str_replace(',','.',$desc), 2);

	if($opcao==1) {
		$valor_desconto = round($subtotalPedido * $desconto / 100, 2);
	} else {
		$valor_desconto = $desconto;
	}

	$salvaDesconto = Lancamentos::salvaDesconto($valor_desconto, $pedido, $mesa);
    
	header("Location: http://192.168.0.118/adegamodelo/mesa/".$mesa.'#modal-descontos');
	exit;

});

$app->get('/acrescimo', function() {

	$mesa = $_GET['mesaID'];
	$pedido = $_GET['pedidoID'];
	$opcao = $_GET['opcao'];
	$subtotalPedido = $_GET['subtotalPedido'];
	$acresc = $_GET['acrescimo'];
	$acrescimo = round(str_replace(',','.',$acresc), 2);

	if($opcao==1) {
		$valor_acrescimo = round($subtotalPedido * $acrescimo / 100, 2);
	} else {
		$valor_acrescimo = $acrescimo;
	}

	$salvaAcrescimo = Lancamentos::salvaAcrescimo($valor_acrescimo, $pedido, $mesa);
    
	header("Location: http://192.168.0.118/adegamodelo/mesa/".$mesa.'#modal-acrescimos');
	exit;

});

$app->get('/adiantamentos', function() {

	$mesa = $_GET['mesaID'];
	$pedido = $_GET['pedidoID'];

	$pgto_dinheiro = !empty($_GET['form_pgto_dinheiro']) ? $_GET['form_pgto_dinheiro'] : 0;
	$pgto_pix = !empty($_GET['form_pgto_pix']) ? $_GET['form_pgto_pix'] : 0;
	$pgto_debito = !empty($_GET['form_pgto_debito']) ? $_GET['form_pgto_debito'] : 0;
	$pgto_credito = !empty($_GET['form_pgto_credito']) ? $_GET['form_pgto_credito'] : 0;
	
	$valor_adiantamento = $pgto_dinheiro + $pgto_pix + $pgto_debito + $pgto_credito;

	$salvaAdiantamento = Lancamentos::salvaAdiantamento($valor_adiantamento, $pedido);
	$lancaPgto = Pedidos::lancaPgto($pgto_dinheiro, $pgto_pix, $pgto_debito, $pgto_credito, $pedido);	
    
	header("Location: http://192.168.0.118/adegamodelo/mesa/".$mesa.'#modal-adiantamentos');
	exit;

});

$app->get('/encerrar_venda', function() {

	$mesa = $_GET['mesaID'];
	$pedido = $_GET['pedidoID'];
	$cliente = $_GET['clienteID'];	

	$pgto_dinheiro = !empty($_GET['final_form_pgto_dinheiro']) ? $_GET['final_form_pgto_dinheiro'] : 0;
	$pgto_pix = !empty($_GET['final_form_pgto_pix']) ? $_GET['final_form_pgto_pix'] : 0;
	$pgto_debito = !empty($_GET['final_form_pgto_debito']) ? $_GET['final_form_pgto_debito'] : 0;
	$pgto_credito = !empty($_GET['final_form_pgto_credito']) ? $_GET['final_form_pgto_credito'] : 0;

	$lancaPgto = Pedidos::lancaPgto($pgto_dinheiro, $pgto_pix, $pgto_debito, $pgto_credito, $pedido);
	$encerraPedido = Pedidos::encerraPedido($pedido, $mesa, $cliente);	

	// Lança tarifas do Mercado Pago como despesas
	// --------------- Crédito
	if($pgto_credito!=0){
		$valorTarifaCredito = arredondar($pgto_credito * 0.0498);
		$descCredito = "Tarifa de Venda Cartão Crédito";
		$tCredito = Despesas::salva($valorTarifaCredito, $descCredito, date('Y-m-d H:i:s'));
	}
	//----------------- Débito
	if($pgto_debito!=0){
		$valorTarifaDebito = arredondar($pgto_debito * 0.0199);
		$descDebito = "Tarifa de Venda Cartão Débito";
		$tDebito = Despesas::salva($valorTarifaDebito, $descDebito, date('Y-m-d H:i:s'));
	}

	if($pgto_dinheiro!=0){
		// Atualiza o caixa incluindo o pagamento em dinheiro
		$addDinheiro = Caixa::entrada($pgto_dinheiro, "Pagamento em dinheiro Pedido nº ".$pedido);
	}	
    
	// pesquisas todas as mesas
	$mesas = Mesas::getAll();

	// Pesquisa as Categorias
	$categorias = myArray(Categorias::getAll());

	//URL
	$url = $_SERVER['HTTP_HOST'];

	header("Location: http://192.168.0.118/adegamodelo/mesas");
	exit;

});



$app->get('/registrar_cliente', function() {

	// Verifica se foi cadastrado um novo cliente a partir da mesa
	if(isset($_GET['clienteNome'])){

		$cliente = Clientes::create($_GET['clienteNome'], '', '', 1);		
		$clienteID = $cliente;		
		
	} else {

		$clienteID = $_GET['clienteID'];

	}
	
	// Dados recebidos da página
	$pedidoID = $_GET['pedidoID'];	
	$mesaID = $_GET['mesaID'];	

	// Pesquisa qual cliente está ocupando a mesa atualmente
	$sqlMesa = Mesas::getByID($mesaID);
	$clienteAtual = $sqlMesa[0]['clienteID'];	

	//Muda o status do cliente atual para 0 = LIVRE
	$updateClienteAtual = Clientes::updateStatus($clienteAtual, 0);

	// Atribui o pedido para o cliente selecionado
	$updatePedido = Pedidos::registrarCliente($pedidoID, $clienteID, $mesaID);
	
	// Atribui a mesa para o cliente selecionado
	$updateMesa = Mesas::registrarCliente($pedidoID, $clienteID, $mesaID);

	// Muda o status do cliente selecionado para 1 = OCUPADO
	$updateCliente = Clientes::updateStatus($clienteID, 1);

	
    
	header("Location: http://192.168.0.118/adegamodelo/mesa/".$mesaID);
	exit;

});


$app->run();

 ?>