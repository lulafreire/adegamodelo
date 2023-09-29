<?php if(!class_exists('Rain\Tpl')){exit;}?>
<div class="container-vendas">  
    <div class="lista-vendas">
      <div class="top-lista-vendas">
        <div class="top-vendas-txt">
          <h3><?php echo htmlspecialchars( $msg, ENT_COMPAT, 'UTF-8', FALSE ); ?></h3>
          <?php if( $valorPedidos!='' ){ ?><p>Total: R$ <?php echo formataValor($valorPedidos); ?> - Lucro: R$ <?php echo formataValor($lucroData); ?> (<?php echo htmlspecialchars( $percLucroData, ENT_COMPAT, 'UTF-8', FALSE ); ?>%)</p><?php } ?>
        </div>
        <div class="form-vendas">
          <i class="las la-search"></i> &nbsp;&nbsp; <a title="Pesquisar vendas por data" href="#pesquisa-data">Por Dia</a> | <a title="Pesquisar vendas por cliente" href="#pesquisa-cliente">Por Cliente</a> | <a title="Pesquisar vendas por número do Pedido" href="#pesquisa-pedido">Por Pedido</a> | <a title="Pesquisar vendas que incluam determinado produto" href="#pesquisa-produto">Por Produto</a>
        </div>
      </div>
        <table class="table table-hover">
            <thead>
              <tr>
                <th>#</th>
                <th>Pedido</th>
                <th>Cliente</th>
                <th>Data e Hora</th>
                <th>Valor R$</th>                
                <th>Detalhar</th>                
              </tr>
            </thead>
            <?php if( $pedidos!=0 ){ ?>            
            <tbody>              
              <?php $counter1=-1;  if( isset($pedidos) && ( is_array($pedidos) || $pedidos instanceof Traversable ) && sizeof($pedidos) ) foreach( $pedidos as $key1 => $value1 ){ $counter1++; ?>               
              <tr>
                <td><?php echo htmlspecialchars( $counter1 +1, ENT_COMPAT, 'UTF-8', FALSE ); ?></td>
                <td><?php echo htmlspecialchars( $value1["num_pedido"], ENT_COMPAT, 'UTF-8', FALSE ); ?></td>
                <td><?php echo htmlspecialchars( $value1["nome_cliente"], ENT_COMPAT, 'UTF-8', FALSE ); ?></td>
                <td><?php echo formataDateTime($value1["data_fim"]); ?></td>
                <td><?php echo formataValor($value1["pgto_total"]); ?></td>                
                <td><a href="pedido/<?php echo htmlspecialchars( $value1["num_pedido"], ENT_COMPAT, 'UTF-8', FALSE ); ?>"><i class="las la-file-invoice-dollar"></i></a></td>
              </tr>
              <?php } ?>                            
            </tbody>
            <?php }else{ ?>
              <tbody>
                <tr>
                  <td colspan="6">
                    <span class="msg_pedidos">Nenhum pedido localizado.</span>     
                  </td>
                </tr>
              </tbody>         
            <?php } ?>                        
          </table>          
    </div>

    <div class="resumo-vendas">
        
        <div class="vendas-resumo">
	
          <div class="vendas-dia">
      
            <div class="vendas-dia-icon">
              <i class="las la-money-bill"></i>
            </div>
      
            <div class="vendas-dia-texto">
              <div class="vendas-dia-title">
                Vendas de Hoje
              </div>
              <div class="vendas-dia-vlr">
                R$ <?php echo formataValor($vendasDoDia); ?>
              </div>
              <div class="lucro-dia">
                LUCRO DO DIA: &nbsp;<strong>R$ <?php echo formataValor($lucroDoDia); ?></strong>&nbsp;&nbsp; (<?php echo htmlspecialchars( $percLucroDoDia, ENT_COMPAT, 'UTF-8', FALSE ); ?>%)
              </div>
            </div>
      
          </div>
      
          <div class="vendas-mes">
      
            <div class="vendas-mes-icon">
              <i class="las la-calendar-alt"></i>
            </div>
      
            <div class="vendas-mes-texto">
              <div class="vendas-mes-title">
                Vendas no Mes
              </div>
              <div class="vendas-mes-vlr">
                R$ <?php echo formataValor($vendasDoMes); ?>
              </div>
              <div class="lucro-mes">
                LUCRO DO MÊS: &nbsp;<strong>R$ <?php echo formataValor($lucroDoMes); ?></strong>&nbsp;&nbsp; (<?php echo htmlspecialchars( $percLucroDoMes, ENT_COMPAT, 'UTF-8', FALSE ); ?>%)
              </div>
            </div>
      
          </div>
      
          <div class="vendas-top5">
            <h2><i style="color:rgb(236, 172, 9);" class="las la-star"></i><i style="color:rgb(236, 172, 9);" class="las la-star"></i><i style="color:rgb(236, 172, 9);" class="las la-star"></i><i style="color:rgb(236, 172, 9);" class="las la-star"></i><i style="color:rgb(236, 172, 9);" class="las la-star"></i> Top 5 Produtos</h2>
      
            <div class="top5-box">
              
              <?php $counter1=-1;  if( isset($top5) && ( is_array($top5) || $top5 instanceof Traversable ) && sizeof($top5) ) foreach( $top5 as $key1 => $value1 ){ $counter1++; ?>
              <?php $barra =  $value1["valor"] / $valorTop5 * 100; ?>
              <?php $width = $barra * 3; ?>
              <div class="top5-graph">
                <div class="top5-barra" style="width: <?php echo arredondar($width); ?>px;">
                  	<?php echo htmlspecialchars( $value1["quantidade"], ENT_COMPAT, 'UTF-8', FALSE ); ?>
                </div>
                <span>R$ <?php echo formataValor($value1["valor"]); ?></span>
              </div>				
              <div class="top-5-produto">
                <?php echo htmlspecialchars( $value1["produto"], ENT_COMPAT, 'UTF-8', FALSE ); ?>
              </div>
             <?php } ?>
      
              			
            </div>
          </div>
      
        </div>
    </div>

</div>


<!-- MODAL Pesquisa por Dia -->
<div id="pesquisa-data" class="modal-pequeno">
    
  <div class="modal-pequeno_content">
      
      <div class="modal_top">
          <h2>PESQUISAR POR DIA</h2><span><a href="#" class="modal_close">&times;</a></span>
      </div>     
      <div class="desconto-opcoes">
        <div class="opcao">
          Informe o Dia
        </div>
        <div class="valor">          
          <form action="http://192.168.0.118/adegamodelo/pesquisar" method="get">
            <input type="date" name="data">           
        </div>        
      </div>      
  </div> 
  <div class="modal_footer">
    <button class="pesquisar-btn-azul">Pesquisar</button>
  </div>
  </form>

</div>

<!-- MODAL Pesquisa por Cliente  -->
<div id="pesquisa-cliente" class="modal-pequeno">
    
  <div class="modal-pequeno_content">
      
      <div class="modal_top">
          <h2>PESQUISAR POR CLIENTE</h2><span><a href="#" class="modal_close">&times;</a></span>
      </div>     
      <div class="desconto-opcoes">
        <div class="opcao">
          Informe o Nome do Cliente
        </div>
        <div class="valor">          
          <form action="http://192.168.0.118/adegamodelo/pesquisar" method="get">
            <input type="text" name="cliente">           
        </div>        
      </div>      
  </div> 
  <div class="modal_footer">
    <button class="pesquisar-btn-azul">Pesquisar</button>
  </div>
  </form>

</div>

<!-- MODAL Pesquisa por Pedido  -->
<div id="pesquisa-pedido" class="modal-pequeno">
    
  <div class="modal-pequeno_content">
      
      <div class="modal_top">
          <h2>PESQUISAR POR Nº DO PEDIDO</h2><span><a href="#" class="modal_close">&times;</a></span>
      </div>     
      <div class="desconto-opcoes">
        <div class="opcao">
          Informe o Número do Pedido
        </div>
        <div class="valor">          
          <form action="http://192.168.0.118/adegamodelo/pesquisar" method="get">
            <input type="text" name="pedido">           
        </div>        
      </div>      
  </div> 
  <div class="modal_footer">
    <button class="pesquisar-btn-azul">Pesquisar</button>
  </div>
  </form>

</div>

<!-- MODAL Pesquisa por Produto  -->
<div id="pesquisa-produto" class="modal-pequeno">
    
  <div class="modal-pequeno_content">
      
      <div class="modal_top">
          <h2>PESQUISAR POR PRODUTO</h2><span><a href="#" class="modal_close">&times;</a></span>
      </div>     
      <div class="desconto-opcoes">        
        <div class="valor">          
          <form action="http://192.168.0.118/adegamodelo/pesquisar" method="get">
            <label for="produto">Escolha um Produto:</label>
            <select name="produto" id="produto">
              <?php $counter1=-1;  if( isset($produtos) && ( is_array($produtos) || $produtos instanceof Traversable ) && sizeof($produtos) ) foreach( $produtos as $key1 => $value1 ){ $counter1++; ?>
              <option value="<?php echo htmlspecialchars( $value1["id"], ENT_COMPAT, 'UTF-8', FALSE ); ?>"><?php echo htmlspecialchars( $value1["nome"], ENT_COMPAT, 'UTF-8', FALSE ); ?></option>
              <?php } ?>              
            </select>          
        </div>        
      </div>      
  </div> 
  <div class="modal_footer">
    <button class="pesquisar-btn-azul">Pesquisar</button>
  </div>
  </form>

</div>

<a class="float-btn" href="https://192.168.0.118/adegamodelo/vendas"><i class="las la-dollar-sign"></i></a>