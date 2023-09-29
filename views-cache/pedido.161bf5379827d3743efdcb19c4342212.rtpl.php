<?php if(!class_exists('Rain\Tpl')){exit;}?><div class="comanda">
    <div class="topo-comanda">
        <span class="mesa_num">Pedido nº <?php echo htmlspecialchars( $pedidos["0"]["num_pedido"], ENT_COMPAT, 'UTF-8', FALSE ); ?></span>
        <span class="mesa_cliente"><i class="las la-user"></i><?php echo htmlspecialchars( $nomeCliente, ENT_COMPAT, 'UTF-8', FALSE ); ?></span>
    </div>
    
    <div class="scrollbox">            
        <div class="scrollbox-inner">                
            <div class="corpo-comanda">
                
                <div class="venda">
                    <?php $counter1=-1;  if( isset($lancamentos) && ( is_array($lancamentos) || $lancamentos instanceof Traversable ) && sizeof($lancamentos) ) foreach( $lancamentos as $key1 => $value1 ){ $counter1++; ?>
                    <?php if( $value1["quantidade"] >=1 ){ ?>
                    <div class="venda_produto">
                        <?php echo htmlspecialchars( $value1["produto"], ENT_COMPAT, 'UTF-8', FALSE ); ?>                         
                    </div>
                    <div class="venda_disc">
                        <div class="venda_quant">
                            <?php echo htmlspecialchars( $value1["quantidade"], ENT_COMPAT, 'UTF-8', FALSE ); ?> x R$ <?php echo formataValor($value1["valor_unit"]); ?>
                        </div>
                        <div class="venda_valor">
                            <b>R$ <?php echo formataValor($value1["quantidade"] * $value1["valor_unit"]); ?></b>
                        </div>                    
                    </div>
                    <?php } ?>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="rodape-comanda">
            
        <div class="valor-comanda">
            <span>VALOR DO PEDIDO</span> <span>R$ <?php echo formataValor($pedidos["0"]["subtotal"]); ?></span>
        </div>
        <div class="valor-comanda">
            <span>(-) <a href="#modal-descontos">DESCONTOS</a></span> <span>R$ <?php echo formataValor($pedidos["0"]["descontos"]); ?></span>
        </div>
        <div class="valor-comanda">
            <span>(+) <a href="#modal-acrescimos">ACRÉSCIMOS</a></span> <span>R$ <?php echo formataValor($pedidos["0"]["acrescimos"]); ?></span>
        </div>        
        <div class="valor-comanda">
            <span><b>(=) TOTAL PAGO</b></span> <span><b>R$ <?php echo formataValor($pedidos["0"]["pgto_total"]); ?></b></span>
        </div>
    </div>

</div>

<div class="pgto-vendas">

    <div class="pagamento">    
        <div class="pagamento-forma">
          <div class="pagamento-img">
            <img src="/adegamodelo/assets/images/dinheiro.png" alt="">
          </div>
          <div class="pagamento-nome">
            Dinheiro
          </div>
          <div class="pagamento-form">
            R$ <?php echo formataValor($pedidos["0"]["pgto_dinheiro"]); ?>
          </div>
        </div>
        
        <div class="pagamento-forma">
          <div class="pagamento-img">
            <img src="/adegamodelo/assets/images/pix.png" alt="">
          </div>
          <div class="pagamento-nome">
            Pix
          </div>
          <div class="pagamento-form">
            R$ <?php echo formataValor($pedidos["0"]["pgto_pix"]); ?>
          </div>
        </div>
    
        <div class="pagamento-forma">
          <div class="pagamento-img">
            <img src="/adegamodelo/assets/images/debito.png" alt="">
          </div>
          <div class="pagamento-nome">
            Cartão Débito
          </div>
          <div class="pagamento-form">
            R$ <?php echo formataValor($pedidos["0"]["pgto_debito"]); ?>
          </div>
        </div>
    
        <div class="pagamento-forma">
          <div class="pagamento-img">
            <img src="/adegamodelo/assets/images/credito.png" alt="">
          </div>
          <div class="pagamento-nome">
            Cartão Crédito
          </div>
          <div class="pagamento-form">
            R$ <?php echo formataValor($pedidos["0"]["pgto_credito"]); ?>
          </div>
        </div>
      
        <div class="pagamento-forma">
          <div class="pagamento-img">
            <img src="/adegamodelo/assets/images/4564998.png" alt="">
          </div>
          <div class="pagamento-nome">
            Valor Pago<br>
            <span class="text-muted">Valor da compra (-) Descontos (+) Acréscimos</span>
          </div>
          <div class="pagamento-form">
            <span><b>R$ <?php echo formataValor($pedidos["0"]["pgto_total"]); ?></b></span>        
          </div>
        </div>  
    
      </div>
      
      <div class="pedido-options">
        <a href="editar/<?php echo htmlspecialchars( $pedidos["0"]["num_pedido"], ENT_COMPAT, 'UTF-8', FALSE ); ?>"><button class="btn-azul"><i class="lar la-edit"></i> Editar</button></a>
        <a href="imprimir/<?php echo htmlspecialchars( $pedidos["0"]["num_pedido"], ENT_COMPAT, 'UTF-8', FALSE ); ?>"><button class="btn-cinza"><i class="las la-print"></i> Imprimir</button></a>
        <a href="excluir/<?php echo htmlspecialchars( $pedidos["0"]["num_pedido"], ENT_COMPAT, 'UTF-8', FALSE ); ?>"><button class="btn-vermelho"><i class="lar la-trash-alt"></i> Excluir</button></a>
      </div>

</div>

<div class="box-lucro">
  <div class="icon-lucro">
    <i class="las la-hand-holding-usd"></i>
  </div>
  <div class="lucro-txt">
    <strong>R$ <?php echo formataValor($lucro_liquido); ?></strong>&nbsp;&nbsp;<span style="font-size: 20px; color: grey;"><?php echo htmlspecialchars( $lucro_perc, ENT_COMPAT, 'UTF-8', FALSE ); ?>%&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;Tarifas: &nbsp;</span><strong style="color: brown;">R$ <?php echo formataValor($tarifas); ?></strong>
    
  </div>
</div>
