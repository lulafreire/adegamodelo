<?php if(!class_exists('Rain\Tpl')){exit;}?><div class="pedido">


    <div class="comanda">
        <div class="topo-comanda">
                <span class="mesa_num">MESA <?php echo htmlspecialchars( $mesa_num, ENT_COMPAT, 'UTF-8', FALSE ); ?></span>
                <span class="mesa_cliente"><i class="las la-user"></i><?php if( $cliente!='LIVRE' ){ ?><?php echo htmlspecialchars( $cliente, ENT_COMPAT, 'UTF-8', FALSE ); ?><?php }else{ ?>CLIENTE NÃO IDENTIFICADO<?php } ?></span>
            </div>
        <div class="scrollbox">            
            <div class="scrollbox-inner">                
                <div class="corpo-comanda">
                    <?php if( ($num_pedido!=0) ){ ?> <span class="num_pedido"><a href="print/<?php echo htmlspecialchars( $num_pedido, ENT_COMPAT, 'UTF-8', FALSE ); ?>">PEDIDO Nº&nbsp;&nbsp;<b><?php echo htmlspecialchars( $num_pedido, ENT_COMPAT, 'UTF-8', FALSE ); ?>&nbsp;&nbsp;<i class="las la-print"></i></b></a></span><?php } ?>
                    <div class="venda">
                        <?php $counter1=-1;  if( isset($pedidos) && ( is_array($pedidos) || $pedidos instanceof Traversable ) && sizeof($pedidos) ) foreach( $pedidos as $key1 => $value1 ){ $counter1++; ?>
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
                    </div>
                </div>
            </div>
        </div>
        <div class="rodape-comanda">
            <div class="valor-comanda">
                <span>VALOR DA MESA</span> <span>R$ <?php echo formataValor($mesa_valor); ?></span>
            </div>
            <div class="valor-comanda">
                <span>(-) DESCONTOS</span> <span>R$ <?php echo formataValor($mesa_valor); ?></span>
            </div>
            <div class="valor-comanda">
                <span>(+) ACRÉSCIMOS</span> <span>R$ <?php echo formataValor($mesa_valor); ?></span>
            </div>
            <div class="valor-comanda">
                <span>(-) ADIANTAMENTOS</span> <span>R$ <?php echo formataValor($mesa_valor); ?></span>
            </div>
            <div class="valor-comanda">
                <span><b>(=) TOTAL A PAGAR</b></span> <span><b>R$ <?php echo formataValor($mesa_valor); ?></b></span>
            </div>
                
            <div class="btn-comanda">
                <a class="btn-salvar-mesa" href="salvar_mesa/<?php echo htmlspecialchars( $mesa_num, ENT_COMPAT, 'UTF-8', FALSE ); ?>">GRAVAR ALTERAÇÕES</a>
                <a class="btn-encerrar-mesa" href="encerrar_mesa/<?php echo htmlspecialchars( $mesa_num, ENT_COMPAT, 'UTF-8', FALSE ); ?>">ENCERRAR VENDA</a>
            </div>
        </div>
    </div>
    <div class="produtos">
        <div class="categorias">
            Categorias
        </div>
        <div class="mercadorias">
            Mercadorias
        </div>
    </div>
</div>