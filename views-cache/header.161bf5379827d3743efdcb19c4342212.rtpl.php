<?php if(!class_exists('Rain\Tpl')){exit;}?><!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/adegamodelo/assets/css/style.css">
     <!-- Icons8 Line Awesome -->
     <link rel="stylesheet" href="/adegamodelo/assets/icons/1.3.0/css/line-awesome.min.css">
     <!-- font awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <!-- slider JS -->
    <script src="/adegamodelo/assets/js/script.js" defer></script>
    <!-- slider CSS -->
    <link rel="stylesheet" href="/adegamodelo/assets/css/slider.css">
    <!-- Charts JS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        

    <title>:: PDV Adega Modelo ::</title>
</head>
<body>
    <nav>
        <div class="logo">PDV Adega Modelo</div>

        <div class="menu-btn">
            <i class="fa fa-bars fa-2x" onclick="menuShow()"></i>
        </div>       

        <ul>
            <li> <a href="http://192.168.0.118/adegamodelo/mesas" class="active"> Mesas </a> </li>
            <li> <a href="http://192.168.0.118/adegamodelo/vendas"> Vendas </a> </li>            
            <li> <a href="http://192.168.0.118/adegamodelo/produtos"> Produtos</a> </li>
            <li> <a href="http://192.168.0.118/adegamodelo/clientes"> Clientes</a> </li>
            <li> <a href="http://192.168.0.118/adegamodelo/financeiro"> Financeiro</a> </li>
            <li> <a href="http://192.168.0.118/adegamodelo/relatorios"> Relatórios</a> </li>
        </ul>
    </nav>