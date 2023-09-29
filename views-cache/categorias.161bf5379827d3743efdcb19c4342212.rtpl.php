<?php if(!class_exists('Rain\Tpl')){exit;}?><div class="produtos">
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

    <section id="tab<?php echo htmlspecialchars( $value1["id_categoria"], ENT_COMPAT, 'UTF-8', FALSE ); ?>" class="produtos-box active">
      
      <div class="produtos-box">
        
        <?php $counter2=-1;  if( isset($produtos) && ( is_array($produtos) || $produtos instanceof Traversable ) && sizeof($produtos) ) foreach( $produtos as $key2 => $value2 ){ $counter2++; ?>
        
        <?php if( $categoriaID == $value2["categoria"] ){ ?>

        <?php $menor_preco = $value2["preco_custo"] * 1.2; ?>

        <div class="produtos-box">          
              <div class="produto">
                <div class="produto_select">
                  <a href="#" title="Menor preço: R$ <?php echo formataValor($menor_preco); ?>">
                  <div class="produto-preco">
                    R$ <?php echo formataValor($value2["preco_venda"]); ?>
                  </div>
                  </a>
                  <div class="quantidade">
                    <a href="add/<?php echo htmlspecialchars( $value2["id"], ENT_COMPAT, 'UTF-8', FALSE ); ?>">
                    <div class="quant_add">
                      <i class="las la-plus-circle"></i>
                    </div>
                    </a>
                    
                    <div class="quant_num">
                      2
                    </div>
                    <a href="remove/<?php echo htmlspecialchars( $value2["id"], ENT_COMPAT, 'UTF-8', FALSE ); ?>">
                    <div class="quant_remove">
                      <i class="las la-minus-circle"></i>
                    </div>
                    </a>
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

