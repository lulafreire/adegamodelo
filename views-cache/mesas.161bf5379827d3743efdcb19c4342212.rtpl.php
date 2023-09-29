<?php if(!class_exists('Rain\Tpl')){exit;}?><!-- Mesas -->
<div class="mesas">
  <?php $counter1=-1;  if( isset($mesas) && ( is_array($mesas) || $mesas instanceof Traversable ) && sizeof($mesas) ) foreach( $mesas as $key1 => $value1 ){ $counter1++; ?>
  <?php if( $caixa!=0 ){ ?>
    <?php if( $caixa["0"]["status"]!=2 ){ ?><a href="mesa/<?php echo htmlspecialchars( $value1["id"], ENT_COMPAT, 'UTF-8', FALSE ); ?>"><?php }else{ ?><a href="#modal-reabrir-caixa"><?php } ?>
  <?php }else{ ?>
    <?php if( $caixa_aberto!=0 ){ ?><a href="#modal-caixa-aberto"><?php }else{ ?><a href="#modal-caixa"><?php } ?>
  <?php } ?>
    <div class="mesa" <?php if( $value1["status"]==0 ){ ?>style="background-color: rgb(165, 236, 213);"<?php }else{ ?>style="background-color: #FFFFFF;"<?php } ?>>
      <div class="mesa-numero">Mesa <?php echo htmlspecialchars( $value1["id"], ENT_COMPAT, 'UTF-8', FALSE ); ?></div>
      <div class="mesa-cliente"><?php if( $value1["cliente"]!='LIVRE' ){ ?><b><?php echo htmlspecialchars( $value1["cliente"], ENT_COMPAT, 'UTF-8', FALSE ); ?></b><?php }else{ ?><?php echo htmlspecialchars( $value1["cliente"], ENT_COMPAT, 'UTF-8', FALSE ); ?><?php } ?></div>
      <div class="mesa-valor"><?php if( $value1["valor_total"]!=0 ){ ?>R$ <?php echo formataValor($value1["valor_total"]); ?><?php } ?></div>
    </div>
  </a>
  <?php } ?>
  </div>

<!-- MODAL CAIXA -->
<div id="modal-caixa" class="modal-pequeno">
    
  <div class="modal-pequeno_content">
      
      <div class="modal_top">
          <h2>É necessário abrir o caixa de hoje para iniciar as vendas.</h2><span><a href="#" class="modal_close">&times;</a></span>
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

<!-- MODAL REABRIR CAIXA -->
<div id="modal-reabrir-caixa" class="modal-pequeno">
    
  <div class="modal-pequeno_content">      
      <div class="modal_top">
          <h2>Caixa Fechado!</h2><span><a href="#" class="modal_close">&times;</a></span>
      </div>
      <div class="caixa-msg">
                        
        O caixa de hoje já foi fechado. Para efetuar novas vendas é necessário reabrir.

    </div>                   
  </div> 
  <div class="modal_footer">
      <a href="reabrir_caixa"><button class='btn-salvar-adiantamento'>Reabrir Caixa</button></a>
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