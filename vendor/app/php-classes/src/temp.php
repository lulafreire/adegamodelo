// Caso não tenha pedido aberto (pedido = 0), cria o pedido e traz o número.
        if($pedido ==0){
            // Define um número para o pedido, baseado na data e hora
            $num_pedido = date('Ymdhis');

            // Cria o pedido
            $sql = (new Sql('pedidos'))->insert([
                'cliente'=>$cliente,
                'status'=>'0',
                'data_ini'=>$date,
                'data_fim'=>$date,
                'num_pedido'=>$num_pedido
            ]);

            // Grava o lançamento relacionando ao pedido
            $lancamento = (new Sql('lancamentos'))->insert([
                'num_pedido'=>$num_pedido,
                'produto'=>$produto,
                'data_hora'=>$date,
                'quantidade'=>'1',
            ]);

            // Define status 1 = ATIVO para a mesa
            $status = 1;

            // Atualiza a mesa informando o número do pedido e do cliente
            $updateMesa = Mesas::update($mesa, $cliente, $status, $num_pedido);
            

        } else {
            // Caso já exista pedido (pedido !=0), dá continuidade com o pedido selecionado.
            $sql = (new Sql('lancamentos'))->insert([
                'num_pedido'=>$pedido,
                'produto'=>$produto,
                'data_hora'=>$date,
                'quantidade'=>'1',
            ]);

            // Caso seja um novo lançamento, atualiza a data fim do pedido
            $update =  (new Sql('pedidos'))->update('num_pedido = '.$pedido,[
                'data_fim'=>$date
            ]);

        }  