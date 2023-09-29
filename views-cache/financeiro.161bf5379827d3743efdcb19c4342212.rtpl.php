<?php if(!class_exists('Rain\Tpl')){exit;}?><div class="financeiro-container">
    <div class="financeiro-content">

        <div class="financeiro-caixa">
            <div class="financeiro-caixa-topo">
                <i class="las la-cash-register"></i> &nbsp;&nbsp;Movimento do Caixa
            </div>

            <?php if( $caixa!=0 ){ ?>
                <?php if( $caixa["0"]["status"]!=2 ){ ?>
                
                <!-- O caixa de hoje foi aberto e ainda não foi fechado -->
                <div class="caixa-content">
                
                    <div class="caixa-item">
                        
                        <div class="caixa-item-texto">
                            Saldo Inicial
                        </div>
                        <div class="caixa-item-valor">
                            R$ <?php echo formataValor($caixa["0"]["saldo_inicial"]); ?>
                        </div>
                        <div class="caixa-item-opcao">
                            <a href="#modal-valor-inicial"><i class="lar la-edit"></i></a>
                        </div>
    
                    </div>
    
                    <div class="caixa-item">
                        
                        <div class="caixa-item-texto">
                            <a href="#modal-editar-entradas">Entradas</a>
                        </div>
                        <div class="caixa-item-valor">
                            R$ <?php echo formataValor($caixa["0"]["entradas"]); ?>
                        </div>
                        <div class="caixa-item-opcao">
                            <a href="#modal-entrada"><i class="las la-plus-circle"></i></a>
                        </div>
    
                    </div>
    
                    <div class="caixa-item">
                        
                        <div class="caixa-item-texto">
                            <a href="#modal-editar-saidas">Saídas</a>
                        </div>
                        <div class="caixa-item-valor">
                            R$ <?php echo formataValor($caixa["0"]["saidas"]); ?>
                        </div>
                        <div class="caixa-item-opcao">
                            <a href="#modal-saida"><i class="las la-plus-circle"></i></a>
                        </div>
    
                    </div>
    
                    <div class="caixa-item">
                        
                        <div class="caixa-item-texto">
                            <b>Saldo Atual</b>
                        </div>
                        <div class="caixa-item-valor">
                            <b>R$ <?php echo formataValor($caixa["0"]["saldo_final"]); ?></b>
                        </div>
                        <div class="caixa-item-opcao">
                            
                        </div>
    
                    </div>
    
                    <div class="caixa-btn">
                        <a href="#modal-fechar-caixa"><button>Fechar Caixa</button></a>
                    </div>
    
                </div>
                <?php }else{ ?>
                
                <!-- O caixa de hoje já foi aberto e fechado. Deseja reabrir? -->
                <div class="caixa-content">
                
                    <div class="caixa-msg">
                        
                        O caixa de hoje já foi fechado.
    
                    </div> 
                    <div class="caixa-item">
                        
                        <div class="caixa-item-texto">
                            <b>Saldo Final</b>
                        </div>
                        <div class="caixa-item-valor">
                            <b>R$ <?php echo formataValor($caixa["0"]["saldo_final"]); ?></b>
                        </div>
    
                    </div>

                    <div class="caixa-item">
                        
                        <div class="caixa-item-texto">
                            <b>Diferença</b>
                        </div>
                        <div class="caixa-item-valor">
                            <b>R$ <?php echo formataValor($caixa["0"]["diferenca"]); ?></b>
                        </div>
    
                    </div>
    
                    <div class="caixa-btn">
                        <a href="reabrir_caixa"><button>Reabrir Caixa</button></a>
                    </div>
    
                </div>
                <?php } ?>
            <?php }else{ ?>
                <?php if( $caixa_aberto!=0 ){ ?>
                
                <!-- Existe um caixa aberto de outro dia. Deverá ser fechado!-->
                <div class="caixa-content">
                
                    <div class="caixa-msg">
                        
                        O caixa do dia <?php echo formataData($caixa_aberto["0"]["data_abertura"]); ?> ainda não foi fechado. É necessário fechá-lo para iniciar as vendas.
    
                    </div>                    
    
                    <div class="caixa-btn">
                        <a href="#modal-caixa-aberto"><button>Fechar Caixa do dia <?php echo formataData($caixa_aberto["0"]["data_abertura"]); ?></button></a>
                    </div>
    
                </div>
                
                <?php }else{ ?>
                
                <!-- Nenhuma sitação crítica. Abrir caixa do dia -->
                <div class="caixa-content">

                    <div class="caixa-btn">
                        <a href="#modal-caixa"><button>Abrir Caixa</button></a>
                    </div>                    

                </div>
                
                <?php } ?>
            <?php } ?>
            
            <div class="caixas-anteriores">
                <b>Últimos 3 Fechamentos</b>

                <?php if( $fechamentos !=0 ){ ?>
                    <?php $counter1=-1;  if( isset($fechamentos) && ( is_array($fechamentos) || $fechamentos instanceof Traversable ) && sizeof($fechamentos) ) foreach( $fechamentos as $key1 => $value1 ){ $counter1++; ?>
                    <div class="fechamento">
                        <div class="fechamento-texto">
                            <a href="https://192.168.0.118/adegamodelo/financeiro#modal-caixa-dia-anterior?dia_anterior=<?php echo reduzData($value1["data_abertura"]); ?>"><?php echo formataData($value1["data_abertura"]); ?></a>
                        </div>
                        <div class="fechamento-valor">
                            <b>R$ <?php echo formataValor($value1["saldo_final"]); ?></b>
                            <a title='Diferença: R$ <?php echo formataValor($value1["diferenca"]); ?>'><i class='las la-coins <?php if( $value1["diferenca"]<0 ){ ?>font-vermelha<?php }else{ ?>font-azul<?php } ?>'></i></a>
                        </div>
                    </div>                    
                    <?php } ?>

                <?php }else{ ?>

                <div class="caixa-msg">
                        
                   Nenhum movimento de caixa foi registrado nos dias anteriores.

                </div>
                
                <?php } ?>
            </div>
            
        </div>
       
    
        <div class="financeiro-detalhes">
            <div class="financeiro-detalhes-topo">
                <i class="las la-asterisk"></i> &nbsp;&nbsp;Detalhamento das Vendas
            </div>
            <div class="financeiro-detalhes-modalidades">
                
                <div class="modalidade">
                    <div class="modalidade-img">
                        <img src="/adegamodelo/assets/images/dinheiro.png" alt="">
                    </div>
                    <div class="modalidade-nome">
                        Dinheiro
                    </div>
                    <div class="modalidade-valor">
                        <b>R$ <?php echo formataValor($vendasDinheiro); ?></b>
                    </div>
                </div>

                <div class="modalidade">
                    <div class="modalidade-img">
                        <img src="/adegamodelo/assets/images/pix.png" alt="">
                    </div>
                    <div class="modalidade-nome">
                        Pix
                    </div>
                    <div class="modalidade-valor">
                        <b>R$ <?php echo formataValor($vendasPix); ?></b>
                    </div>
                </div>

                <div class="modalidade">
                    <div class="modalidade-img">
                        <img src="/adegamodelo/assets/images/debito.png" alt="">
                    </div>
                    <div class="modalidade-nome">
                        Débito
                    </div>
                    <div class="modalidade-valor">
                        <b>R$ <?php echo formataValor($vendasDebito); ?></b>
                    </div>
                </div>

                <div class="modalidade">
                    <div class="modalidade-img">
                        <img src="/adegamodelo/assets/images/credito.png" alt="">
                    </div>
                    <div class="modalidade-nome">
                        Crédito
                    </div>
                    <div class="modalidade-valor">
                        <b>R$ <?php echo formataValor($vendasCredito); ?></b>
                    </div>
                </div>

            </div>
            <div class="financeiro-detalhes-grafico">
                <canvas id="chart_Modalidades"></canvas>
            </div>
        </div>
        
        <div class="financeiro-resultados">
            <div class="financeiro-resultados-topo">
                <i class="las la-dollar-sign"></i> Resultado Financeiro (últimos 12 meses)
            </div>
            <div class="financeiro-resultados-grafico">
                <canvas id="chart_Resultados"></canvas>
            </div>  
            <div class="financeiro-resultados-top3">
                <div class="financeiro-resultados-top3-titulo">
                    <b>Principais Despesas (últimos 12 meses)</b>
                    <div class="despecas-opcoes">
                        <a href="#modal-despesa" title="Adicionar despesa"><i class="las la-plus-circle"></i></a>
                        <a href="despesas" title="Ver todas as despesas"><i class="lar la-file-alt"></i></a>
                    </div>
                </div>
                <div class="top-despesas">
                    <?php if( $top5despesas!=0 ){ ?>
                    <?php $counter1=-1;  if( isset($top5despesas) && ( is_array($top5despesas) || $top5despesas instanceof Traversable ) && sizeof($top5despesas) ) foreach( $top5despesas as $key1 => $value1 ){ $counter1++; ?>
                    <div class="despesa-box">
                        <div class="despesa-valor">
                            <?php echo formataValor($value1["valor"]); ?>
                        </div>                    
                        <div class="despesa-descricao">
                            <?php echo htmlspecialchars( $value1["descricao"], ENT_COMPAT, 'UTF-8', FALSE ); ?>
                        </div>
                    </div>
                    <?php } ?>
                    <?php }else{ ?>
                    <div class="caixa-msg">
                        Nenhuma despesa cadastrada nos últimos 12 meses.
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>       
        
    </div>
</div>

<script>
    const ctx = document.getElementById('chart_Resultados');
  
    new Chart(ctx, {
        data: {
        datasets: [{
            type: 'line',
            label: 'Faturamento',
            data: [
                <?php echo htmlspecialchars( $faturamentoMes01, ENT_COMPAT, 'UTF-8', FALSE ); ?>,
                <?php echo htmlspecialchars( $faturamentoMes02, ENT_COMPAT, 'UTF-8', FALSE ); ?>,
                <?php echo htmlspecialchars( $faturamentoMes03, ENT_COMPAT, 'UTF-8', FALSE ); ?>,
                <?php echo htmlspecialchars( $faturamentoMes04, ENT_COMPAT, 'UTF-8', FALSE ); ?>,
                <?php echo htmlspecialchars( $faturamentoMes05, ENT_COMPAT, 'UTF-8', FALSE ); ?>,
                <?php echo htmlspecialchars( $faturamentoMes06, ENT_COMPAT, 'UTF-8', FALSE ); ?>,
                <?php echo htmlspecialchars( $faturamentoMes07, ENT_COMPAT, 'UTF-8', FALSE ); ?>,
                <?php echo htmlspecialchars( $faturamentoMes08, ENT_COMPAT, 'UTF-8', FALSE ); ?>,
                <?php echo htmlspecialchars( $faturamentoMes09, ENT_COMPAT, 'UTF-8', FALSE ); ?>,
                <?php echo htmlspecialchars( $faturamentoMes10, ENT_COMPAT, 'UTF-8', FALSE ); ?>,
                <?php echo htmlspecialchars( $faturamentoMes11, ENT_COMPAT, 'UTF-8', FALSE ); ?>,
                <?php echo htmlspecialchars( $faturamentoMesAtual, ENT_COMPAT, 'UTF-8', FALSE ); ?>
            ]
        }, {
            type: 'line',
            label: 'Lucros',
            data: [
                <?php echo htmlspecialchars( $lucroMes01, ENT_COMPAT, 'UTF-8', FALSE ); ?>,
                <?php echo htmlspecialchars( $lucroMes02, ENT_COMPAT, 'UTF-8', FALSE ); ?>,
                <?php echo htmlspecialchars( $lucroMes03, ENT_COMPAT, 'UTF-8', FALSE ); ?>,
                <?php echo htmlspecialchars( $lucroMes04, ENT_COMPAT, 'UTF-8', FALSE ); ?>,
                <?php echo htmlspecialchars( $lucroMes05, ENT_COMPAT, 'UTF-8', FALSE ); ?>,
                <?php echo htmlspecialchars( $lucroMes06, ENT_COMPAT, 'UTF-8', FALSE ); ?>,
                <?php echo htmlspecialchars( $lucroMes07, ENT_COMPAT, 'UTF-8', FALSE ); ?>,
                <?php echo htmlspecialchars( $lucroMes08, ENT_COMPAT, 'UTF-8', FALSE ); ?>,
                <?php echo htmlspecialchars( $lucroMes09, ENT_COMPAT, 'UTF-8', FALSE ); ?>,
                <?php echo htmlspecialchars( $lucroMes10, ENT_COMPAT, 'UTF-8', FALSE ); ?>,
                <?php echo htmlspecialchars( $lucroMes11, ENT_COMPAT, 'UTF-8', FALSE ); ?>,
                <?php echo htmlspecialchars( $lucroMesAtual, ENT_COMPAT, 'UTF-8', FALSE ); ?>,
            ],
        }, {
            type: 'line',
            label: 'Despesas',
            data: [
                <?php echo htmlspecialchars( $despesaMes01, ENT_COMPAT, 'UTF-8', FALSE ); ?>,
                <?php echo htmlspecialchars( $despesaMes02, ENT_COMPAT, 'UTF-8', FALSE ); ?>,
                <?php echo htmlspecialchars( $despesaMes03, ENT_COMPAT, 'UTF-8', FALSE ); ?>,
                <?php echo htmlspecialchars( $despesaMes04, ENT_COMPAT, 'UTF-8', FALSE ); ?>,
                <?php echo htmlspecialchars( $despesaMes05, ENT_COMPAT, 'UTF-8', FALSE ); ?>,
                <?php echo htmlspecialchars( $despesaMes06, ENT_COMPAT, 'UTF-8', FALSE ); ?>,
                <?php echo htmlspecialchars( $despesaMes07, ENT_COMPAT, 'UTF-8', FALSE ); ?>,
                <?php echo htmlspecialchars( $despesaMes08, ENT_COMPAT, 'UTF-8', FALSE ); ?>,
                <?php echo htmlspecialchars( $despesaMes09, ENT_COMPAT, 'UTF-8', FALSE ); ?>,
                <?php echo htmlspecialchars( $despesaMes10, ENT_COMPAT, 'UTF-8', FALSE ); ?>,
                <?php echo htmlspecialchars( $despesaMes11, ENT_COMPAT, 'UTF-8', FALSE ); ?>,
                <?php echo htmlspecialchars( $despesaMesAtual, ENT_COMPAT, 'UTF-8', FALSE ); ?>,
            ],
        }],
        labels: ['<?php echo htmlspecialchars( $mes01, ENT_COMPAT, 'UTF-8', FALSE ); ?>', '<?php echo htmlspecialchars( $mes02, ENT_COMPAT, 'UTF-8', FALSE ); ?>', '<?php echo htmlspecialchars( $mes03, ENT_COMPAT, 'UTF-8', FALSE ); ?>', '<?php echo htmlspecialchars( $mes04, ENT_COMPAT, 'UTF-8', FALSE ); ?>','<?php echo htmlspecialchars( $mes05, ENT_COMPAT, 'UTF-8', FALSE ); ?>','<?php echo htmlspecialchars( $mes06, ENT_COMPAT, 'UTF-8', FALSE ); ?>','<?php echo htmlspecialchars( $mes07, ENT_COMPAT, 'UTF-8', FALSE ); ?>','<?php echo htmlspecialchars( $mes08, ENT_COMPAT, 'UTF-8', FALSE ); ?>','<?php echo htmlspecialchars( $mes09, ENT_COMPAT, 'UTF-8', FALSE ); ?>','<?php echo htmlspecialchars( $mes10, ENT_COMPAT, 'UTF-8', FALSE ); ?>','<?php echo htmlspecialchars( $mes11, ENT_COMPAT, 'UTF-8', FALSE ); ?>','<?php echo htmlspecialchars( $mes12, ENT_COMPAT, 'UTF-8', FALSE ); ?>']
    },    
      options: {
        scales: {
          y: {
            beginAtZero: true
          }
        }
      }
    });
  </script>

<script>
    const ctx2 = document.getElementById('chart_Modalidades');
  
    new Chart(ctx2, {
      type: 'bar',
      data: {
        labels: ['Dinheiro', 'Pix', 'Débito', 'Crédito'],
        datasets: [{
          label: 'Percentual das Vendas',
          data: [<?php echo htmlspecialchars( $percDinheiro, ENT_COMPAT, 'UTF-8', FALSE ); ?>, <?php echo htmlspecialchars( $percPix, ENT_COMPAT, 'UTF-8', FALSE ); ?>, <?php echo htmlspecialchars( $percDebito, ENT_COMPAT, 'UTF-8', FALSE ); ?>, <?php echo htmlspecialchars( $percCredito, ENT_COMPAT, 'UTF-8', FALSE ); ?>],
          borderWidth: 1,
          backgroundColor: '#909909',
        }]
      },
      options: {
        scales: {
          y: {
            beginAtZero: true
          }
        }
      }
    });
  </script>

<script>
    const ctx3 = document.getElementById('chart_Despesas');
  
    new Chart(ctx3, {
      type: 'bar',
      data: {
        labels: ['Dinheiro', 'Pix', 'Débito', 'Crédito'],
        datasets: [{
          label: 'Percentual das Vendas',
          data: [<?php echo htmlspecialchars( $percDinheiro, ENT_COMPAT, 'UTF-8', FALSE ); ?>, <?php echo htmlspecialchars( $percPix, ENT_COMPAT, 'UTF-8', FALSE ); ?>, <?php echo htmlspecialchars( $percDebito, ENT_COMPAT, 'UTF-8', FALSE ); ?>, <?php echo htmlspecialchars( $percCredito, ENT_COMPAT, 'UTF-8', FALSE ); ?>],
          borderWidth: 1,
          backgroundColor: '#909909',
        }]
      },
      options: {
        indexAxis: 'y',
        scales: {
          y: {
            beginAtZero: true
          }
        }
      }
    });
  </script>

<!-- MODAL CAIXA -->
<div id="modal-caixa" class="modal-pequeno">
    
    <div class="modal-pequeno_content">
        
        <div class="modal_top">
            <h2>ABRIR CAIXA</h2><span><a href="#" class="modal_close">&times;</a></span>
        </div>     
        <div class="desconto-opcoes">
          <div class="opcao">
            Valor Inicial
          </div>
          <div class="valor">            
            <form action="http://192.168.0.118/adegamodelo/abrir_caixa" method="get">
              <input type="text" size="5" name="valor_inicial" value="">             
          </div>        
        </div>        
    </div> 
    <div class="modal_footer">
        <button class='btn-salvar-adiantamento'>Abrir</button>
    </div>
    </form>
  
</div>

<!-- MODAL ALTERAR VALOR INICIAL -->
<div id="modal-valor-inicial" class="modal-pequeno">
    
    <div class="modal-pequeno_content">
        
        <div class="modal_top">
            <h2>ALTERAR SALDO INICIAL</h2><span><a href="#" class="modal_close">&times;</a></span>
        </div>     
        <div class="desconto-opcoes">
          <div class="opcao">
            Valor Inicial
          </div>
          <div class="valor">            
            <form action="http://192.168.0.118/adegamodelo/atualizar_caixa" method="get">
              <?php if( $caixa!=0 ){ ?><input type="text" size="5" name="valor" value="<?php echo htmlspecialchars( $caixa["0"]["saldo_inicial"], ENT_COMPAT, 'UTF-8', FALSE ); ?>"><?php } ?>
          </div>        
        </div>        
    </div> 
    <div class="modal_footer">
        <button class='btn-salvar-adiantamento'>Alterar</button>
    </div>
    </form>
  
</div>

<!-- MODAL ENTRADA -->
<div id="modal-entrada" class="modal-pequeno">
    
    <div class="modal-pequeno_content">
        
        <div class="modal_top">
            <h2>ENTRADA DE DINHEIRO</h2><span><a href="#" class="modal_close">&times;</a></span>
        </div>     
        <div class="desconto-opcoes">
          <div class="opcao">
            Valor R$
          </div>
          <div class="valor">            
            <form action="http://192.168.0.118/adegamodelo/caixa_entrada" method="get">
              <input type="text" size="5" name="valor" value="">             
          </div>                 
        </div>
        <div class="descricao">
            Descrição:
            <input type="text" name="descricao" size="50">
        </div>         
    </div> 
    <div class="modal_footer">
        <button class='btn-salvar-adiantamento'>Lançar</button>
    </div>
    </form>
  
</div>

<!-- ALERTA -->
<div id="alerta-excluir-entrada" class="modal-pequeno">
    
    <div class="modal-pequeno_content">
        
        <div class="modal_top">
            <h2>ALERTA!</h2><span><a href="#" class="modal_close">&times;</a></span>
        </div>

        <div class="caixa-msg">
                        
            Não é permitido excluir esse tipo de Entrada.<br>Caso necessário, cancele o pedido.
    
        </div>                
           
    </div>     
  
</div>

<!-- MODAL EDITAR ENTRADA -->
<div id="modal-editar-entradas" class="modal">
    
    <div class="modal_content">
        
        <div class="modal_top">
            <h2>ENTRADAS</h2><span><a href="#" class="modal_close">&times;</a></span>
        </div>
        <?php if( $entradas!=0 ){ ?>

        <?php $counter1=-1;  if( isset($entradas) && ( is_array($entradas) || $entradas instanceof Traversable ) && sizeof($entradas) ) foreach( $entradas as $key1 => $value1 ){ $counter1++; ?>

        <div class="caixa-item">
                        
            <div class="caixa-item-texto" style="width: 250px;">
                <?php echo htmlspecialchars( $value1["descricao"], ENT_COMPAT, 'UTF-8', FALSE ); ?>
            </div>
            <div class="caixa-item-valor">
                R$ <?php echo formataValor($value1["valor"]); ?>
            </div>
            
            <div class="caixa-item-opcao">
                <a href="excluir_entrada?valor=<?php echo htmlspecialchars( $value1["valor"], ENT_COMPAT, 'UTF-8', FALSE ); ?>&descricao=<?php echo htmlspecialchars( $value1["descricao"], ENT_COMPAT, 'UTF-8', FALSE ); ?>"><i class="lar la-trash-alt"></i></a>
            </div>

        </div>

        <?php } ?>

        <?php }else{ ?> 

        <div class="caixa-msg">
            Não foram registradas entradas no Caixa hoje.
        </div>

        <?php } ?>
                 
    </div>     
  
</div>

<!-- MODAL EDITAR SAÍDA -->
<div id="modal-editar-saidas" class="modal">
    
    <div class="modal_content">
        
        <div class="modal_top">
            <h2>SAÍDAS</h2><span><a href="#" class="modal_close">&times;</a></span>
        </div>
        <?php if( $saidas!=0 ){ ?>

        <?php $counter1=-1;  if( isset($saidas) && ( is_array($saidas) || $saidas instanceof Traversable ) && sizeof($saidas) ) foreach( $saidas as $key1 => $value1 ){ $counter1++; ?>

        <div class="caixa-item">
                        
            <div class="caixa-item-texto" style="width: 250px;">
                <?php echo htmlspecialchars( $value1["descricao"], ENT_COMPAT, 'UTF-8', FALSE ); ?>
            </div>
            <div class="caixa-item-valor">
                R$ <?php echo formataValor($value1["valor"]); ?>
            </div>
            <div class="caixa-item-opcao">
                <a href="excluir_saida?valor=<?php echo htmlspecialchars( $value1["valor"], ENT_COMPAT, 'UTF-8', FALSE ); ?>&descricao=<?php echo htmlspecialchars( $value1["descricao"], ENT_COMPAT, 'UTF-8', FALSE ); ?>"><i class="lar la-trash-alt"></i></a>
            </div>

        </div>

        <?php } ?>

        <?php }else{ ?> 

        <div class="caixa-msg">
            Não foram registradas saídas do Caixa hoje.
        </div>

        <?php } ?>
                 
    </div>     
  
</div>

<?php if( $caixa!=0 ){ ?>
<!-- MODAL SAÍDA  -->
<form action="http://192.168.0.118/adegamodelo/caixa_saida" method="get">
<div id="modal-saida" class="modal-pequeno">
    
    <div class="modal-pequeno_content">
        
        <div class="modal_top">
            <h2>RETIRADA DE DINHEIRO</h2><span><a href="#" class="modal_close">&times;</a></span>
        </div>     
        <div class="desconto-opcoes">
          <div class="opcao">
            Valor R$
          </div>
          <div class="valor">            
                <input type="hidden" id="saldo_final" value="<?php echo htmlspecialchars( $caixa["0"]["saldo_final"], ENT_COMPAT, 'UTF-8', FALSE ); ?>">
              <input type="text" size="5" name="valor" id="vlr_despesa" value="" onkeyup=" soma_despesa()" required>             
          </div>                 
        </div>
        <div class="despesa">
            Descrição:
            <select name="descricao" required>
                <option value="">Escolha...</option>
                <option value="Pagamento de Água">Pagamento de Água</option>
                <option value="Pagamento de Energia">Pagamento de Energia</option>
                <option value="Pagamento de Aluguel">Pagamento de Aluguel</option>
                <option value="Pagamento de Internet">Pagamento de Internet</option>
                <option value="Pagamento de Telefone">Pagamento de Telefone</option>
                <option value="Pagamento de Funcionário">Pagamento de Funcionário</option>
                <option value="Compra de Mercadoria">Compra de Mercadoria</option>
                <option value="Cofre">Cofre</option>
                <option value="Despesas Pessoais">Despesas Pessoais</option>
            </select>
        </div>         
    </div> 
    <div class="modal_footer">
        <button type="submit" id="btn-despesa" class="btn-salvar-adiantamento">Lançar Saída</button>
    </div>  
</div>
</form>
<?php } ?>

<!-- MODAL DESPESA  -->
<form action="http://192.168.0.118/adegamodelo/lanca_despesa" method="get">
    <div id="modal-despesa" class="modal-pequeno">
        
        <div class="modal-pequeno_content">
            
            <div class="modal_top">
                <h2>LANÇAMENTO DE DESPESA</h2><span><a href="#" class="modal_close">&times;</a></span>
            </div>     
            <div class="desconto-opcoes">
              <div class="opcao">
                Valor R$
              </div>
              <div class="valor">                                
                  <input type="text" size="5" name="valor" value="" required>             
              </div> 
              <div class="opcao">
                Data
              </div>
              <div class="valor">                                
                  <input type="date" name="data" id="data" value="" required>             
              </div>                
            </div>
            <div class="despesa">
                Descrição:
                <select name="descricao" required>
                    <option value="">Escolha...</option>
                    <option value="Pagamento de Água">Pagamento de Água</option>
                    <option value="Pagamento de Energia">Pagamento de Energia</option>
                    <option value="Pagamento de Aluguel">Pagamento de Aluguel</option>
                    <option value="Pagamento de Internet">Pagamento de Internet</option>
                    <option value="Pagamento de Telefone">Pagamento de Telefone</option>
                    <option value="Pagamento de Funcionário">Pagamento de Funcionário</option>                                        
                    <option value="Despesas Pessoais">Despesas Pessoais</option>
                </select>
            </div>         
        </div> 
        <div class="modal_footer">
            <button type="submit" id="btn-despesa" class="btn-salvar-adiantamento">Lançar Despesa</button>
        </div>  
    </div>
    </form>

<?php if( $caixa!=0 ){ ?>
<!-- MODAL CAIXA ABERTO -->
<div id="modal-fechar-caixa" class="modal">
    
  <div class="modal_content">
      
      <div class="modal_top">
          <h2>Fechar Caixa</h2><span><a href="#" class="modal_close">&times;</a></span>
      </div>           

      <div class="caixa-content">
                
        <div class="caixa-item">
            
            <div class="caixa-item-texto">
                Saldo Inicial
            </div>
            <div class="caixa-item-valor">
                R$ <?php echo formataValor($caixa["0"]["saldo_inicial"]); ?>
            </div>            

        </div>

        <div class="caixa-item">
            
            <div class="caixa-item-texto">
                Entradas
            </div>
            <div class="caixa-item-valor">
                R$ <?php echo formataValor($caixa["0"]["entradas"]); ?>
            </div>            

        </div>

        <div class="caixa-item">
            
            <div class="caixa-item-texto">
                Saídas
            </div>
            <div class="caixa-item-valor">
                R$ <?php echo formataValor($caixa["0"]["saidas"]); ?>
            </div>            

        </div>

        <div class="caixa-item">            
            <div class="caixa-item-texto">
                <b>Saldo Atual</b>
            </div>
            <div class="caixa-item-valor">
              <form action="http://192.168.0.118/adegamodelo/fechar_caixa" method="get">
              <input type="hidden" name="saldo_final" id="saldo_final" value="<?php echo htmlspecialchars( $caixa["0"]["saldo_final"], ENT_COMPAT, 'UTF-8', FALSE ); ?>">
                <b>R$ <?php echo formataValor($caixa["0"]["saldo_final"]); ?></b>
            </div>          

        </div>        
          <input type="hidden" name="data_abertura" value="<?php echo htmlspecialchars( $caixa["0"]["data_abertura"], ENT_COMPAT, 'UTF-8', FALSE ); ?>">
        <div class="caixa-item">
            
          <div class="caixa-item-texto">
              <b>Conferência</b>
          </div>
          <div class="caixa-item-valor">
              <input type="number" size="10" name="conferencia" id="conferencia" onkeyup=" soma_diferenca()">
          </div>          

      </div>

      <div class="caixa-item">
            
        <div class="caixa-item-texto">
            <b>Diferença</b>
        </div>
        <div class="caixa-item-valor" id="txt-diferenca">
          
        </div>          

    </div>

    <div class="caixa-item" style="height: 55px; margin-bottom: 0;">
            
      <div class="caixa-item-texto">
          <b>Observações</b>
      </div>
      <div class="caixa-item-valor" style="width: 400px;">
          <textarea name="observacao" id="" cols="30" rows="2" placeholder="Justifique se houver diferenças"></textarea>
      </div>          

  </div>

        <div class="caixa-btn">
            <button>Fechar Caixa</button>
        </div>

  </div> 
  </form>

</div>
<?php } ?>

<!-- MODAL CAIXA ANTERIOR -->
<div id="modal-caixa-anterior" class="modal">
    
    <div class="modal_content">
        
        <div class="modal_top">
            <h2>Caixa</h2><span><a href="#" class="modal_close">&times;</a></span>
        </div>           
  
        <div class="caixa-content">
                  
          <div class="caixa-item">
              
              <div class="caixa-item-texto">
                  Saldo Inicial
              </div>
              <div class="caixa-item-valor">
                  R$ 
              </div>            
  
          </div>
  
          <div class="caixa-item">
              
              <div class="caixa-item-texto">
                  Entradas
              </div>
              <div class="caixa-item-valor">
                  R$ 
              </div>            
  
          </div>
  
          <div class="caixa-item">
              
              <div class="caixa-item-texto">
                  Saídas
              </div>
              <div class="caixa-item-valor">
                  R$ 
              </div>            
  
          </div>
  
          <div class="caixa-item">            
              <div class="caixa-item-texto">
                  <b>Saldo Atual</b>
              </div>
              <div class="caixa-item-valor">
                <b>R$ </b>
              </div>                     
  
        </div>
  
        <div class="caixa-item">
              
          <div class="caixa-item-texto">
              <b>Diferença</b>
          </div>
          <div class="caixa-item-valor" id="txt-diferenca">
            
          </div>          
  
      </div>
  
      <div class="caixa-item" style="height: 55px; margin-bottom: 0;">
              
        <div class="caixa-item-texto">
            <b>Observações</b>
        </div>
        <div class="caixa-item-valor" style="width: 400px;">
            <textarea name="observacao" id="" cols="30" rows="2" placeholder="Justifique se houver diferenças"></textarea>
        </div>          
  
    </div>
  
          
  
    </div> 
    </form>
  
  </div>


<?php if( $caixa_aberto!=0 ){ ?>
<!-- MODAL CAIXA ABERTO -->
<div id="modal-caixa-aberto" class="modal">
    
  <div class="modal_content">
      
      <div class="modal_top">
          <h2><img src="/adegamodelo/assets/images/aviso.png" style="width: 32px;"> Alerta!</h2><span><a href="#" class="modal_close">&times;</a></span>
      </div>     
      <p>O caixa do dia <b><?php echo formataData($caixa_aberto["0"]["data_abertura"]); ?></b> ainda está aberto! É necessário fechá-lo para inicar as vendas.</p>        

      <div class="caixa-content">
                
        <div class="caixa-item">
            
            <div class="caixa-item-texto">
                Saldo Inicial
            </div>
            <div class="caixa-item-valor">
                R$ <?php echo formataValor($caixa_aberto["0"]["saldo_inicial"]); ?>
            </div>            

        </div>

        <div class="caixa-item">
            
            <div class="caixa-item-texto">
                Entradas
            </div>
            <div class="caixa-item-valor">
                R$ <?php echo formataValor($caixa_aberto["0"]["entradas"]); ?>
            </div>            

        </div>

        <div class="caixa-item">
            
            <div class="caixa-item-texto">
                Saídas
            </div>
            <div class="caixa-item-valor">
                R$ <?php echo formataValor($caixa_aberto["0"]["saidas"]); ?>
            </div>            

        </div>

        <div class="caixa-item">            
            <div class="caixa-item-texto">
                <b>Saldo Atual</b>
            </div>
            <div class="caixa-item-valor">
              <form action="http://192.168.0.118/adegamodelo/fechar_caixa" method="get">
              <input type="hidden" name="saldo_final" id="saldo_final" value="<?php echo htmlspecialchars( $caixa_aberto["0"]["saldo_final"], ENT_COMPAT, 'UTF-8', FALSE ); ?>">
                <b>R$ <?php echo formataValor($caixa_aberto["0"]["saldo_final"]); ?></b>
            </div>          

        </div>        
          <input type="hidden" name="data_abertura" value="<?php echo htmlspecialchars( $caixa_aberto["0"]["data_abertura"], ENT_COMPAT, 'UTF-8', FALSE ); ?>">
        <div class="caixa-item">
            
          <div class="caixa-item-texto">
              <b>Conferência</b>
          </div>
          <div class="caixa-item-valor">
              <input type="number" size="10" name="conferencia" id="conferencia" onkeyup=" soma_diferenca()">
          </div>          

      </div>

      <div class="caixa-item">
            
        <div class="caixa-item-texto">
            <b>Diferença</b>
        </div>
        <div class="caixa-item-valor" id="txt-diferenca">
          
        </div>          

    </div>

    <div class="caixa-item" style="height: 55px; margin-bottom: 0;">
            
      <div class="caixa-item-texto">
          <b>Observações</b>
      </div>
      <div class="caixa-item-valor" style="width: 400px;">
          <textarea name="observacao" id="" cols="30" rows="2" placeholder="Justifique se houver diferenças"></textarea>
      </div>          

  </div>

        <div class="caixa-btn">
            <button>Fechar Caixa</button>
        </div>

  </div> 
  </form>

</div>
<?php } ?>

<?php if( $caixa_dia_anterior!=0 ){ ?>
<!-- MODAL PESQUISA CAIXA DIA ANTERIOR -->
<div id="modal-caixa-dia-anterior" class="modal">
    
    <div class="modal_content">
        
        <div class="modal_top">
            <h2>Caixa Fechado</h2><span><a href="#" class="modal_close">&times;</a></span>
        </div>     
        <p><b><?php echo formataData($caixa_anterior["0"]["data_abertura"]); ?></b>.</p>        
                    
        <div class="caixa-item">
            
            <div class="caixa-item-texto">
                Saldo Inicial
            </div>
            <div class="caixa-item-valor">
                R$ <?php echo formataValor($caixa_anterior["0"]["saldo_inicial"]); ?>
            </div>            

        </div>

        <div class="caixa-item">
            
            <div class="caixa-item-texto">
                Entradas
            </div>
            <div class="caixa-item-valor">
                R$ <?php echo formataValor($caixa_anterior["0"]["entradas"]); ?>
            </div>            

        </div>

        <div class="caixa-item">
            
            <div class="caixa-item-texto">
                Saídas
            </div>
            <div class="caixa-item-valor">
                R$ <?php echo formataValor($caixa_anterior["0"]["saidas"]); ?>
            </div>            

        </div>

        <div class="caixa-item">            
            <div class="caixa-item-texto">
                <b>Saldo Final</b>
            </div>
            <div class="caixa-item-valor">
                <form action="http://192.168.0.118/adegamodelo/fechar_caixa" method="get">
                <input type="hidden" name="saldo_final" id="saldo_final" value="<?php echo htmlspecialchars( $caixa_aberto["0"]["saldo_final"], ENT_COMPAT, 'UTF-8', FALSE ); ?>">
                <b>R$ <?php echo formataValor($caixa_anterior["0"]["saldo_final"]); ?></b>
            </div>          

        </div>        
            
        <div class="caixa-item">
            
            <div class="caixa-item-texto">
                <b>Diferença</b>
            </div>
            <div class="caixa-item-valor">
            <b>R$ <?php echo formataValor($caixa_anterior["0"]["diferenca"]); ?></b>
            </div>          

        </div>      

        <div class="caixa-item" style="height: 55px; margin-bottom: 0;">
                
        <div class="caixa-item-texto">
            <b>Observações</b>
        </div>
        <div class="caixa-item-valor" style="width: 400px;">
            <?php echo htmlspecialchars( $caixa_anterior["0"]["obs"], ENT_COMPAT, 'UTF-8', FALSE ); ?>
        </div>          

        </div>

    </div>
</div>
<?php } ?>

<script>

    function soma_diferenca() {
      
      saldo_final = document.getElementById("saldo_final").value
      conferencia = document.getElementById("conferencia").value  
      vlr_diferenca = conferencia - saldo_final
    
      if(vlr_diferenca < 0) { 
    
        var btn = document.querySelector("#txt-diferenca");
        btn.innerHTML = "<span class='txt_vermelho'>R$ "+vlr_diferenca.toFixed(2)+"</span>";
    
      }
    
      if(vlr_diferenca > 0) { 
    
        var btn = document.querySelector("#txt-diferenca");
        btn.innerHTML = "<span class='txt_azul'>R$ "+vlr_diferenca.toFixed(2)+"</span>";
    
      }
    
      if(vlr_diferenca == 0) { 
    
        var btn = document.querySelector("#txt-diferenca");
        btn.innerHTML = "<span class='txt_verde'>Sem diferença!</span>";
    
      }
      
      document.getElementById("vlr_diferenca").value = vlr_diferenca.toFixed(2);
    
    }   
    
    </script>

    <script>
        function soma_despesa() {

            saldo_final = document.getElementById("saldo_final").value
            vlr_despesa = document.getElementById("vlr_despesa").value  
            vlr_diferenca = saldo_final - vlr_despesa

            if(vlr_diferenca < 0) {
                let el = document. getElementById('btn-despesa');
                el. classList. remove('btn-salvar-adiantamento');
                el. classList. add('invisivel');
            } else {
                let el = document. getElementById('btn-despesa');
                el. classList. remove('invisivel');
                el. classList. add('btn-salvar-adiantamento');
            }             

        }
    </script>