<?php if(!class_exists('Rain\Tpl')){exit;}?><div class="pedido">
    <?php $counter1=-1;  if( isset($mesa) && ( is_array($mesa) || $mesa instanceof Traversable ) && sizeof($mesa) ) foreach( $mesa as $key1 => $value1 ){ $counter1++; ?>
    <?php $pedidoID = $value1["num_pedido"]; ?>
    <?php $mesaID = $value1["id"]; ?>
    <?php $clienteID = $value1["clienteID"]; ?>
    <?php $nomeCliente = $value1["cliente"]; ?>
    <div class="comanda">
        <div class="topo-comanda">
                <span class="mesa_num">MESA <?php echo htmlspecialchars( $value1["id"], ENT_COMPAT, 'UTF-8', FALSE ); ?></span>
                <span class="mesa_cliente"><a href="#modal-cliente"><i class="las la-user"></i><?php echo htmlspecialchars( $value1["cliente"], ENT_COMPAT, 'UTF-8', FALSE ); ?></a></span>
            </div>
        <div class="scrollbox">            
            <div class="scrollbox-inner">                
                <div class="corpo-comanda">
                    <?php if( $value1["num_pedido"]!=0 ){ ?> <span class="num_pedido"><a href="print/<?php echo htmlspecialchars( $value1["num_pedido"], ENT_COMPAT, 'UTF-8', FALSE ); ?>">PEDIDO Nº&nbsp;&nbsp;<b><?php echo htmlspecialchars( $value1["num_pedido"], ENT_COMPAT, 'UTF-8', FALSE ); ?>&nbsp;&nbsp;<i class="las la-print"></i></b></a></span><?php } ?>
                    <div class="venda">
                        <?php $counter2=-1;  if( isset($pedidoData) && ( is_array($pedidoData) || $pedidoData instanceof Traversable ) && sizeof($pedidoData) ) foreach( $pedidoData as $key2 => $value2 ){ $counter2++; ?>
                        <?php if( $value2["quantidade"] >=1 ){ ?>
                        <div class="venda_produto">
                            <?php echo htmlspecialchars( $value2["produto"], ENT_COMPAT, 'UTF-8', FALSE ); ?> 
                            <form action="http://192.168.0.118/adegamodelo/remover_produto" method="get">
                              <input type="hidden" name="mesa" value="<?php echo htmlspecialchars( $mesaID, ENT_COMPAT, 'UTF-8', FALSE ); ?>">
                              <input type="hidden" name="pedido" value="<?php echo htmlspecialchars( $pedidoID, ENT_COMPAT, 'UTF-8', FALSE ); ?>">
                              <input type="hidden" name="produto" value="<?php echo htmlspecialchars( $value2["produtoID"], ENT_COMPAT, 'UTF-8', FALSE ); ?>">                              
                              <input type="hidden" name="clienteID" value="<?php echo htmlspecialchars( $clienteID, ENT_COMPAT, 'UTF-8', FALSE ); ?>">
                              <button class="remover-produto"><i class="las la-times-circle"></i></button>
                            </form>
                        </div>
                        <div class="venda_disc">
                            <div class="venda_quant">
                                <?php echo htmlspecialchars( $value2["quantidade"], ENT_COMPAT, 'UTF-8', FALSE ); ?> x R$ <?php echo formataValor($value2["valor_unit"]); ?>
                            </div>
                            <div class="venda_valor">
                                <b>R$ <?php echo formataValor($value2["quantidade"] * $value2["valor_unit"]); ?></b>
                            </div>                    
                        </div>
                        <?php } ?>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <?php if( $value1["num_pedido"]!=0 ){ ?>
        <div class="rodape-comanda">
            
            <div class="valor-comanda">
                <span>VALOR DA MESA</span> <span>R$ <?php echo formataValor($subtotalPedido); ?></span>
            </div>
            <div class="valor-comanda">
                <span>(-) <a href="#modal-descontos">DESCONTOS</a></span> <span>R$ <?php echo formataValor($desconto); ?></span>
            </div>
            <div class="valor-comanda">
                <span>(+) <a href="#modal-acrescimos">ACRÉSCIMOS</a></span> <span>R$ <?php echo formataValor($acrescimo); ?></span>
            </div>
            <div class="valor-comanda">
                <span>(-) <a href="#modal-adiantamentos">ADIANTAMENTOS</a></span> <span>R$ <?php echo formataValor($adiantamento); ?></span>
            </div>
            <div class="valor-comanda">
                <span><b>(=) TOTAL A PAGAR</b></span> <span><b>R$ <?php echo formataValor($valor_total); ?></b></span>
            </div>
            
                
            <div class="btn-comanda">                
                <a class="btn-encerrar-mesa" href="#modal-encerrar-pedido">ENCERRAR VENDA</a>
                <form action="http://192.168.0.118/adegamodelo/excluir_pedido" method="get">
                  <input type="hidden" name="mesa" value="<?php echo htmlspecialchars( $mesaID, ENT_COMPAT, 'UTF-8', FALSE ); ?>">
                  <input type="hidden" name="pedido" value="<?php echo htmlspecialchars( $pedidoID, ENT_COMPAT, 'UTF-8', FALSE ); ?>">                                                
                  <input type="hidden" name="clienteID" value="<?php echo htmlspecialchars( $clienteID, ENT_COMPAT, 'UTF-8', FALSE ); ?>">
                  <button class="btn-excluir-pedido"><i class="lar la-trash-alt"></i></button>
                </form>
            </div>
            
        </div>
        <?php } ?>
    </div>
    <?php } ?>
    
    <div class="produtos">
        <div class="c-body">
          <div class="wrapper">
            <i id="left" class="las la-angle-left"></i>
            <ul class="carousel">
              <?php $counter1=-1;  if( isset($categorias) && ( is_array($categorias) || $categorias instanceof Traversable ) && sizeof($categorias) ) foreach( $categorias as $key1 => $value1 ){ $counter1++; ?>
              <li class="card">
                <a href="#tab<?php echo htmlspecialchars( $value1["id_categoria"], ENT_COMPAT, 'UTF-8', FALSE ); ?>">
                <div class="img"><img src="/adegamodelo/assets/images/<?php echo htmlspecialchars( $value1["imagem_categoria"], ENT_COMPAT, 'UTF-8', FALSE ); ?>" alt="img" draggable="false"></div>
                <h2><?php echo htmlspecialchars( $value1["nome_categoria"], ENT_COMPAT, 'UTF-8', FALSE ); ?></h2> 
                </a>         
              </li>
              <?php } ?>        
            </ul>
            <i id="right" class="las la-angle-right"></i>
          </div>
        </div>
      
        <article>
      
          <?php $counter1=-1;  if( isset($categorias) && ( is_array($categorias) || $categorias instanceof Traversable ) && sizeof($categorias) ) foreach( $categorias as $key1 => $value1 ){ $counter1++; ?>
      
          <?php $categoriaID = $value1["id_categoria"]; ?>
      
          <section id="tab<?php echo htmlspecialchars( $value1["id_categoria"], ENT_COMPAT, 'UTF-8', FALSE ); ?>" <?php if( $categoriaID ==1 ){ ?>class="active"<?php } ?>>
            
            <div class="produtos-box">
              
              <?php $counter2=-1;  if( isset($produtos) && ( is_array($produtos) || $produtos instanceof Traversable ) && sizeof($produtos) ) foreach( $produtos as $key2 => $value2 ){ $counter2++; ?>

              <?php $produtoNome = $value2["nome"]; ?>
              
              <?php if( $categoriaID == $value2["categoria"] ){ ?>
      
              <?php $menor_preco = $value2["preco_custo"] * 1.2; ?>

              <?php $diferenca = $value2["preco_venda"] - $menor_preco; ?>

              <?php $maior_desconto = $diferenca / $value2["preco_venda"] * 100; ?>
      
              <div class="produtos-box">          
                    <div class="produto">
                      <div class="produto_select">
                        <a href="#" title="Menor preço: R$ <?php echo formataValor($menor_preco); ?> (Desconto: <?php echo arredondar($maior_desconto); ?>%)">
                        <div class="produto-preco">
                          R$ <?php echo formataValor($value2["preco_venda"]); ?>
                        </div>
                        </a>

                        
                        <div class="quantidade">
                          <form action="http://192.168.0.118/adegamodelo/add" method="get">
                            <input type="hidden" name="mesa" value="<?php echo htmlspecialchars( $mesaID, ENT_COMPAT, 'UTF-8', FALSE ); ?>">
                            <input type="hidden" name="pedido" value="<?php echo htmlspecialchars( $pedidoID, ENT_COMPAT, 'UTF-8', FALSE ); ?>">
                            <input type="hidden" name="produto" value="<?php echo htmlspecialchars( $value2["id"], ENT_COMPAT, 'UTF-8', FALSE ); ?>">
                            <input type="hidden" name="tab" value="<?php echo htmlspecialchars( $categoriaID, ENT_COMPAT, 'UTF-8', FALSE ); ?>">
                            <input type="hidden" name="clienteID" value="<?php echo htmlspecialchars( $clienteID, ENT_COMPAT, 'UTF-8', FALSE ); ?>">
                          <div class="quant_add">
                            <button type="submit"><i class="las la-plus"></i></button>
                          </div>
                          </form>
                          
                          <div class="quant_num">
                            <?php $counter3=-1;  if( isset($pedidoData) && ( is_array($pedidoData) || $pedidoData instanceof Traversable ) && sizeof($pedidoData) ) foreach( $pedidoData as $key3 => $value3 ){ $counter3++; ?>
                            <?php if( $produtoNome==$value3["produto"] ){ ?>
                                <?php $produtoQtd = $value3["quantidade"]; ?>
                                <?php echo htmlspecialchars( $produtoQtd, ENT_COMPAT, 'UTF-8', FALSE ); ?>                                
                            <?php } ?>
                            <?php } ?>
                          </div>                            
                                                       
                          <form action="http://192.168.0.118/adegamodelo/remove" method="get">                             
                            <input type="hidden" name="mesa" value="<?php echo htmlspecialchars( $mesaID, ENT_COMPAT, 'UTF-8', FALSE ); ?>">
                            <input type="hidden" name="pedido" value="<?php echo htmlspecialchars( $pedidoID, ENT_COMPAT, 'UTF-8', FALSE ); ?>">
                            <input type="hidden" name="produto" value="<?php echo htmlspecialchars( $value2["id"], ENT_COMPAT, 'UTF-8', FALSE ); ?>">
                            <input type="hidden" name="tab" value="<?php echo htmlspecialchars( $categoriaID, ENT_COMPAT, 'UTF-8', FALSE ); ?>">
                            <input type="hidden" name="clienteID" value="<?php echo htmlspecialchars( $clienteID, ENT_COMPAT, 'UTF-8', FALSE ); ?>">
                          <div class="quant_remove">
                            <button type="submit"><i class="las la-minus"></i></button>
                          </div>
                          </form>                         
                          
                        </div>
                       
                      </div>
                        <div class="produto-img">
                            <img src="/adegamodelo/assets/images/<?php echo htmlspecialchars( $value2["imagem"], ENT_COMPAT, 'UTF-8', FALSE ); ?>" alt="">
                        </div>
                        <div class="produto-text">
                            <div class="produto-desc">
                                <?php echo htmlspecialchars( $value2["nome"], ENT_COMPAT, 'UTF-8', FALSE ); ?>
                            </div>                      
                        </div>
                    </div>
            </div>
      
              <?php } ?>
              
              <?php } ?>
      
            </div>        
              
          </section> 
          
          <?php } ?>
      
        </article>
      
      </div>

<div id="modal-cliente" class="modal">
    
    <div class="modal_content">
        
        <div class="modal_top">
            <h2>INFORMAR CLIENTE DA MESA</h2><span><a href="#" class="modal_close">&times;</a></span>
        </div>
        <b>Cliente Atual:</b>
        <div class="mesa-cliente-atual">
          <div class="nome-cliente-atual">
            <i class="las la-user"></i> <?php echo htmlspecialchars( $nomeCliente, ENT_COMPAT, 'UTF-8', FALSE ); ?>
          </div>
          <div class="btn-cliente-atual">
            <?php if( $clienteID>=21 ){ ?>
            <form action="http://192.168.0.118/adegamodelo/registrar_cliente" method="get">
              <input type="hidden" name="clienteID" value="<?php echo htmlspecialchars( $mesaID, ENT_COMPAT, 'UTF-8', FALSE ); ?>">
              <input type="hidden" name="pedidoID" value="<?php echo htmlspecialchars( $pedidoID, ENT_COMPAT, 'UTF-8', FALSE ); ?>">
              <input type="hidden" name="mesaID" value="<?php echo htmlspecialchars( $mesaID, ENT_COMPAT, 'UTF-8', FALSE ); ?>">                
              <button type="submit"><i class="las la-times"></i></button>
            </form>
            <?php } ?>
          </div>
        </div>
        <b>Escolher outro Cliente:</b>
        <div class="clientes-lista">
          <?php $counter1=-1;  if( isset($clientes) && ( is_array($clientes) || $clientes instanceof Traversable ) && sizeof($clientes) ) foreach( $clientes as $key1 => $value1 ){ $counter1++; ?>
            
            <?php if( $value1["status"]==0 ){ ?>
            <div class="cliente-nome">
             <?php echo htmlspecialchars( $counter1 + 1, ENT_COMPAT, 'UTF-8', FALSE ); ?>. <?php echo htmlspecialchars( $value1["nome"], ENT_COMPAT, 'UTF-8', FALSE ); ?> 
             <div class="btn-cliente">
              <form action="http://192.168.0.118/adegamodelo/registrar_cliente" method="get">
                <input type="hidden" name="clienteID" value="<?php echo htmlspecialchars( $value1["id"], ENT_COMPAT, 'UTF-8', FALSE ); ?>">
                <input type="hidden" name="pedidoID" value="<?php echo htmlspecialchars( $pedidoID, ENT_COMPAT, 'UTF-8', FALSE ); ?>">
                <input type="hidden" name="mesaID" value="<?php echo htmlspecialchars( $mesaID, ENT_COMPAT, 'UTF-8', FALSE ); ?>">                
                <button type="submit"><i class="lar la-check-square"></i></button>
              </form>
             </div>
            </div>
            <?php }else{ ?>
            <div class="cliente-nome" style="color:rgb(199, 194, 194);">
              <?php echo htmlspecialchars( $counter1 + 1, ENT_COMPAT, 'UTF-8', FALSE ); ?>. <?php echo htmlspecialchars( $value1["nome"], ENT_COMPAT, 'UTF-8', FALSE ); ?> 
              <span>Cliente já registrado em mesa.</span>
             </div>            
            <?php } ?>
            
            
          <?php } ?>
        </div>
        <b>Cadastrar Novo Cliente</b>
        <div class="cliente-novo">
          <form action="http://192.168.0.118/adegamodelo/registrar_cliente" method="get">
            <input type="text" size="55" name="clienteNome">
            <input type="hidden" name="pedidoID" value="<?php echo htmlspecialchars( $pedidoID, ENT_COMPAT, 'UTF-8', FALSE ); ?>">
            <input type="hidden" name="mesaID" value="<?php echo htmlspecialchars( $mesaID, ENT_COMPAT, 'UTF-8', FALSE ); ?>">                
            <button type="submit"><i class="las la-plus"></i></button>
          </form>
        </div>

    </div>   

</div>

<!-- MODAL DESCONTOS -->
<div id="modal-descontos" class="modal-pequeno">
    
  <div class="modal-pequeno_content">
      
      <div class="modal_top">
          <h2>DESCONTOS</h2><span><a href="#" class="modal_close">&times;</a></span>
      </div>     
      <div class="desconto-opcoes">
        <div class="opcao">
          Percentual
        </div>
        <div class="valor">
          <?php if( $desconto!='' ){ ?>
          <?php if( $desconto!=0 ){ ?>
            <?php $percentualDesconto = $desconto / $subtotalPedido * 100; ?>
          <?php }else{ ?>
            <?php $percentualDesconto = 0; ?>
          <?php } ?>
          <?php } ?>
          <form action="http://192.168.0.118/adegamodelo/desconto" method="get">
            % <input type="text" size="5" name="desconto" value="<?php echo htmlspecialchars( $percentualDesconto, ENT_COMPAT, 'UTF-8', FALSE ); ?>">
            <input type="hidden" name="opcao" value="1">
            <input type="hidden" name="pedidoID" value="<?php echo htmlspecialchars( $pedidoID, ENT_COMPAT, 'UTF-8', FALSE ); ?>">
            <input type="hidden" name="mesaID" value="<?php echo htmlspecialchars( $mesaID, ENT_COMPAT, 'UTF-8', FALSE ); ?>">
            <input type="hidden" name="subtotalPedido" value="<?php echo htmlspecialchars( $subtotalPedido, ENT_COMPAT, 'UTF-8', FALSE ); ?>">                 
            <button type="submit"><i class="las la-arrow-right"></i></button>
          </form>
        </div>        
      </div>

      <div class="desconto-opcoes">
        <div class="opcao">
          Valor
        </div>
        <div class="valor">          
          <form action="http://192.168.0.118/adegamodelo/desconto" method="get">
            R$ <input type="text" size="5" name="desconto" value="<?php echo htmlspecialchars( $desconto, ENT_COMPAT, 'UTF-8', FALSE ); ?>">
            <input type="hidden" name="opcao" value="2">
            <input type="hidden" name="pedidoID" value="<?php echo htmlspecialchars( $pedidoID, ENT_COMPAT, 'UTF-8', FALSE ); ?>">
            <input type="hidden" name="mesaID" value="<?php echo htmlspecialchars( $mesaID, ENT_COMPAT, 'UTF-8', FALSE ); ?>">
            <input type="hidden" name="subtotalPedido" value="<?php echo htmlspecialchars( $subtotalPedido, ENT_COMPAT, 'UTF-8', FALSE ); ?>">                            
            <button type="submit"><i class="las la-arrow-right"></i></button>
          </form>
        </div>        
      </div>      
      
  </div> 
  <div class="modal_footer">
    <h4>Escolha a modalidade do desconto e clique na seta.</h4>
  </div>

</div>

<!-- MODAL ACRÉSCIMOS -->
<div id="modal-acrescimos" class="modal-pequeno">
    
  <div class="modal-pequeno_content">
      
      <div class="modal_top">
          <h2>ACRÉSCIMOS</h2><span><a href="#" class="modal_close">&times;</a></span>
      </div>     
      <div class="desconto-opcoes">
        <div class="opcao">
          Percentual
        </div>
        <div class="valor">
          <?php if( $acrescimo!='' ){ ?>
          <?php if( $acrescimo!=0 ){ ?>
            <?php $percentualAcrescimo = $acrescimo / $subtotalPedido * 100; ?>
          <?php }else{ ?>
            <?php $percentualAcrescimo = 0; ?>
          <?php } ?>
          <?php } ?>
          <form action="http://192.168.0.118/adegamodelo/acrescimo" method="get">
            % <input type="text" size="5" name="acrescimo" value="<?php echo htmlspecialchars( $percentualAcrescimo, ENT_COMPAT, 'UTF-8', FALSE ); ?>">
            <input type="hidden" name="opcao" value="1">
            <input type="hidden" name="pedidoID" value="<?php echo htmlspecialchars( $pedidoID, ENT_COMPAT, 'UTF-8', FALSE ); ?>">
            <input type="hidden" name="mesaID" value="<?php echo htmlspecialchars( $mesaID, ENT_COMPAT, 'UTF-8', FALSE ); ?>">
            <input type="hidden" name="subtotalPedido" value="<?php echo htmlspecialchars( $subtotalPedido, ENT_COMPAT, 'UTF-8', FALSE ); ?>">                 
            <button type="submit"><i class="las la-arrow-right"></i></button>
          </form>
        </div>        
      </div>

      <div class="desconto-opcoes">
        <div class="opcao">
          Valor
        </div>
        <div class="valor">          
          <form action="http://192.168.0.118/adegamodelo/acrescimo" method="get">
            R$ <input type="text" size="5" name="acrescimo" value="<?php echo htmlspecialchars( $acrescimo, ENT_COMPAT, 'UTF-8', FALSE ); ?>">
            <input type="hidden" name="opcao" value="2">
            <input type="hidden" name="pedidoID" value="<?php echo htmlspecialchars( $pedidoID, ENT_COMPAT, 'UTF-8', FALSE ); ?>">
            <input type="hidden" name="mesaID" value="<?php echo htmlspecialchars( $mesaID, ENT_COMPAT, 'UTF-8', FALSE ); ?>">
            <input type="hidden" name="subtotalPedido" value="<?php echo htmlspecialchars( $subtotalPedido, ENT_COMPAT, 'UTF-8', FALSE ); ?>">                            
            <button type="submit"><i class="las la-arrow-right"></i></button>
          </form>
        </div>        
      </div>      
      
  </div> 
  <div class="modal_footer">
    <h4>Escolha a modalidade do acréscimo e clique na seta.</h4>
  </div>

</div>


<!-- MODAL ADIANTAMENTOS -->
<form action="http://192.168.0.118/adegamodelo/adiantamentos" method="get">
<div id="modal-adiantamentos" class="modal">
    
  <div class="modal_content">
    
      <input type="hidden" name="pedidoID" value="<?php echo htmlspecialchars( $pedidoID, ENT_COMPAT, 'UTF-8', FALSE ); ?>">
      <input type="hidden" name="mesaID" value="<?php echo htmlspecialchars( $mesaID, ENT_COMPAT, 'UTF-8', FALSE ); ?>">

      <div class="modal_top">
          <h2>PAGAMENTO ADIANTADO</h2><span><a href="#" class="modal_close">&times;</a></span>
      </div>
      <b>Informe o meio de pagamento.</b>
      <div class="pagamento">
        <?php if( $pedidoDetalhado!='' ){ ?>
        <?php $counter1=-1;  if( isset($pedidoDetalhado) && ( is_array($pedidoDetalhado) || $pedidoDetalhado instanceof Traversable ) && sizeof($pedidoDetalhado) ) foreach( $pedidoDetalhado as $key1 => $value1 ){ $counter1++; ?>
          <?php $dinheiro = $value1["pgto_dinheiro"]; ?>
          <?php $pix = $value1["pgto_pix"]; ?>
          <?php $debito = $value1["pgto_debito"]; ?>
          <?php $credito = $value1["pgto_credito"]; ?>
          <?php $totalPedido = $subtotalPedido + $acrescimo - $desconto; ?>
        <?php } ?>
        <?php }else{ ?>
          <?php $dinheiro = 0; ?>
          <?php $pix = 0; ?>
          <?php $debito = 0; ?>
          <?php $credito = 0; ?>
          <?php $totalPedido = 0; ?>
        <?php } ?>
        <div class="pagamento-forma">
          <div class="pagamento-img">
            <img src="/adegamodelo/assets/images/dinheiro.png" alt="">
          </div>
          <div class="pagamento-nome">
            Dinheiro
          </div>
          <div class="pagamento-form">
            <input type="text" id="form_pgto_dinheiro" name="form_pgto_dinheiro" onkeyup="soma()" value="<?php echo htmlspecialchars( $dinheiro, ENT_COMPAT, 'UTF-8', FALSE ); ?>">
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
            <input type="text" id="form_pgto_pix" name="form_pgto_pix" onkeyup="soma()" value="<?php echo htmlspecialchars( $pix, ENT_COMPAT, 'UTF-8', FALSE ); ?>">
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
            <input type="text" id="form_pgto_debito" name="form_pgto_debito" onkeyup="soma()" value="<?php echo htmlspecialchars( $debito, ENT_COMPAT, 'UTF-8', FALSE ); ?>">
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
            <input type="text" id="form_pgto_credito" name="form_pgto_credito" onkeyup="soma()" value="<?php echo htmlspecialchars( $credito, ENT_COMPAT, 'UTF-8', FALSE ); ?>">
          </div>
        </div>
      
        <div class="pagamento-forma">
          <div class="pagamento-img">
            <img src="/adegamodelo/assets/images/4564998.png" alt="">
          </div>
          <div class="pagamento-nome">
            Total do Pedido<br>
            <span class="text-muted">Valor da compra (-) Descontos (+) Acréscimos</span>
          </div>
          <div class="pagamento-form">
            <span><b>R$ <?php echo formataValor($totalPedido); ?></b></span>
            <span class="valor_total" id="valor_total"><?php echo htmlspecialchars( $totalPedido, ENT_COMPAT, 'UTF-8', FALSE ); ?></b></span>
          </div>
        </div>
        <div class="pagamento-forma">
          <div class="pagamento-img">
            <img src="/adegamodelo/assets/images/troco.jpg" alt="">
          </div>
          <div class="pagamento-nome">
            Restante
          </div>
          <div class="pagamento-form">
            <input type="text" id="restante" name="restante" disabled>
          </div>
        </div>

      </div>     

  </div>  
  <div id="modal_footer" class="modal_footer">
    
  </div>
 

</div>
</form>


<!-- MODAL ENCERRAMENTO -->
<form action="http://192.168.0.118/adegamodelo/encerrar_venda" method="get">
  <div id="modal-encerrar-pedido" class="modal">
      
    <div class="modal_content">
      
        <input type="hidden" name="pedidoID" value="<?php echo htmlspecialchars( $pedidoID, ENT_COMPAT, 'UTF-8', FALSE ); ?>">
        <input type="hidden" name="mesaID" value="<?php echo htmlspecialchars( $mesaID, ENT_COMPAT, 'UTF-8', FALSE ); ?>">
        <input type="hidden" name="clienteID" value="<?php echo htmlspecialchars( $clienteID, ENT_COMPAT, 'UTF-8', FALSE ); ?>">
  
        <div class="modal_top">
            <h2>ENCERRAR VENDA</h2><span><a href="#" class="modal_close">&times;</a></span>
        </div>
        <b>Informe o meio de pagamento.</b>
        <div class="pagamento">
          <?php if( $pedidoDetalhado!='' ){ ?>
          <?php $counter1=-1;  if( isset($pedidoDetalhado) && ( is_array($pedidoDetalhado) || $pedidoDetalhado instanceof Traversable ) && sizeof($pedidoDetalhado) ) foreach( $pedidoDetalhado as $key1 => $value1 ){ $counter1++; ?>
            <?php $dinheiro = $value1["pgto_dinheiro"]; ?>
            <?php $pix = $value1["pgto_pix"]; ?>
            <?php $debito = $value1["pgto_debito"]; ?>
            <?php $credito = $value1["pgto_credito"]; ?>
            <?php $totalPedido = $subtotalPedido + $acrescimo - $desconto; ?>
          <?php } ?>
          <?php }else{ ?>
            <?php $dinheiro = 0; ?>
            <?php $pix = 0; ?>
            <?php $debito = 0; ?>
            <?php $credito = 0; ?>
            <?php $totalPedido = 0; ?>
          <?php } ?>
          <div class="pagamento-forma">
            <div class="pagamento-img">
              <img src="/adegamodelo/assets/images/dinheiro.png" alt="">
            </div>
            <div class="pagamento-nome">
              Dinheiro
            </div>
            <div class="pagamento-form">
              <input type="text" id="final_form_pgto_dinheiro" name="final_form_pgto_dinheiro" onkeyup="soma_final()" value="<?php echo htmlspecialchars( $dinheiro, ENT_COMPAT, 'UTF-8', FALSE ); ?>">
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
              <input type="text" id="final_form_pgto_pix" name="final_form_pgto_pix" onkeyup="soma_final()" value="<?php echo htmlspecialchars( $pix, ENT_COMPAT, 'UTF-8', FALSE ); ?>">
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
              <input type="text" id="final_form_pgto_debito" name="final_form_pgto_debito" onkeyup="soma_final()" value="<?php echo htmlspecialchars( $debito, ENT_COMPAT, 'UTF-8', FALSE ); ?>">
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
              <input type="text" id="final_form_pgto_credito" name="final_form_pgto_credito" onkeyup="soma_final()" value="<?php echo htmlspecialchars( $credito, ENT_COMPAT, 'UTF-8', FALSE ); ?>">
            </div>
          </div>
        
          <div class="pagamento-forma">
            <div class="pagamento-img">
              <img src="/adegamodelo/assets/images/4564998.png" alt="">
            </div>
            <div class="pagamento-nome">
              Total do Pedido<br>
              <span class="text-muted">Valor da compra (-) Descontos (+) Acréscimos</span>
            </div>
            <div class="pagamento-form">
              <span><b>R$ <?php echo formataValor($totalPedido); ?></b></span>
              <span class="valor_total" id="final_valor_total"><?php echo htmlspecialchars( $totalPedido, ENT_COMPAT, 'UTF-8', FALSE ); ?></b></span>
            </div>
          </div>
          <div class="pagamento-forma">
            <div class="pagamento-img">
              <img src="/adegamodelo/assets/images/troco.jpg" alt="">
            </div>
            <div class="pagamento-nome">
              Restante
            </div>
            <div class="pagamento-form">
              <input type="text" id="final_restante" name="final_restante" disabled>
            </div>
          </div>
  
        </div>     
  
    </div>  
    <div id="modal_footer_final" class="modal_footer">
      
    </div>
   
  
  </div>
  </form>

<script>  

  function soma() {
  
    total = document.getElementById("valor_total").innerHTML
    dinheiro = document.getElementById("form_pgto_dinheiro").value
    pix = document.getElementById("form_pgto_pix").value
    debito = document.getElementById("form_pgto_debito").value
    credito = document.getElementById("form_pgto_credito").value
    restante = total - dinheiro - pix - debito - credito

    if(restante >= 0) {

      var btn = document.querySelector("#modal_footer");
      btn.innerHTML = "<button type='submit' class='btn-salvar-adiantamento'>Salvar</button>";

    } else {

      var btn = document.querySelector("#modal_footer");
      btn.innerHTML = "<button class='invisivel'>Salvar</button>";

    }
    
    document.getElementById("restante").value = restante.toFixed(2);

}

soma();
 

</script>


<script>  

  function soma_final() {
  
    total = document.getElementById("final_valor_total").innerHTML
    dinheiro = document.getElementById("final_form_pgto_dinheiro").value
    pix = document.getElementById("final_form_pgto_pix").value
    debito = document.getElementById("final_form_pgto_debito").value
    credito = document.getElementById("final_form_pgto_credito").value
    restante = total - dinheiro - pix - debito - credito

    if(restante == 0) {

      var btn = document.querySelector("#modal_footer_final");
      btn.innerHTML = "<button type='submit' class='btn-salvar-adiantamento'>Encerrar Venda</button>";

    } else {

      var btn = document.querySelector("#modal_footer_final");
      btn.innerHTML = "<button class='invisivel'>Salvar</button>";

    }
    
    document.getElementById("final_restante").value = restante.toFixed(2);

}

soma_final();
 

</script>

