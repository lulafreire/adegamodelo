<?php if(!class_exists('Rain\Tpl')){exit;}?><!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <title>Menu responsivo</title>
</head>
<body>
    <nav>
        <div class="logo">PDV Adega Modelo</div>

        <div class="menu-btn">
            <i class="fa fa-bars fa-2x" onclick="menuShow()"></i>
        </div>

        <ul>
            <li> <a href="vendas" class="active"> Vendas </a> </li>
            <li> <a href="caixa"> Caixa</a> </li>
            <li> <a href="produtos"> Produtos</a> </li>
            <li> <a href="clientes"> Clientes</a> </li>
            <li> <a href="relatórios"> Relatórios</a> </li>
            <li> <a href="https://web.whatsapp.com"> WhatsApp</a> </li>
        </ul>
    </nav>

    <!-- Mesas -->
    <div class="mesas">
      <div class="mesa">
        <div class="mesa-numero">Mesa 1</div>
        <div class="mesa-cliente">Meirateje</div>
        <div class="mesa-valor">R$ 50,00</div>
      </div>
      <div class="mesa">
        <div class="mesa-numero">Mesa 2</div>
        <div class="mesa-cliente">LIVRE</div>
        <div class="mesa-valor">R$ 0,00</div>
      </div>
      <div class="mesa">
        <div class="mesa-numero">Mesa 3</div>
        <div class="mesa-cliente">LIVRE</div>
        <div class="mesa-valor">R$ 0,00</div>
      </div>
      <div class="mesa">
        <div class="mesa-numero">Mesa 4</div>
        <div class="mesa-cliente">LIVRE</div>
        <div class="mesa-valor">R$ 0,00</div>
      </div>
      <div class="mesa">
        <div class="mesa-numero">Mesa 5</div>
        <div class="mesa-cliente">LIVRE</div>
        <div class="mesa-valor">R$ 0,00</div>
      </div>
      <div class="mesa">
        <div class="mesa-numero">Mesa 6</div>
        <div class="mesa-cliente">LIVRE</div>
        <div class="mesa-valor">R$ 0,00</div>
      </div>
      <div class="mesa">
        <div class="mesa-numero">Mesa 7</div>
        <div class="mesa-cliente">LIVRE</div>
        <div class="mesa-valor">R$ 0,00</div>
      </div>
      <div class="mesa">
        <div class="mesa-numero">Mesa 8</div>
        <div class="mesa-cliente">LIVRE</div>
        <div class="mesa-valor">R$ 0,00</div>
      </div>
    </div>



    <script src="../assets/js/main.js"></script>
</body>
</html>