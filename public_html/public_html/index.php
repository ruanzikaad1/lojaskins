<?php
require('config.php'); // Inclui as configurações do banco de dados

session_start(); // Inicia a sessão para acessar as variáveis

if (isset($_SESSION['steamID'])) {
    $steamID = $_SESSION['steamID'];
    $nomeUsuario = $_SESSION['nomeUsuario'];
    $avatarUsuario = $_SESSION['avatarUsuario'];
    $logado = isset($_SESSION['steamID']); // Verifica se o usuário está logado
    
    // Conectar ao banco de dados
    $conn = mysqli_connect($host, $user, $password, $database);

    if (!$conn) {
        die("Falha na conexão com o banco de dados: " . mysqli_connect_error());
    }

    // Consulta para buscar o saldo onde o steam_id for igual ao steamID
    $stmt = $conn->prepare("SELECT saldo FROM users WHERE steam_id = ?");
    $stmt->bind_param("s", $steamID); // "s" indica que o parâmetro é uma string
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Se o usuário for encontrado, recupera o saldo
        $row = $result->fetch_assoc();
        $saldo = $row['saldo'];

        
        // Exibir as informações do usuário
       // echo "Bem-vindo, $nomeUsuario!<br>";
       // echo "<img src='$avatarUsuario' alt='Avatar'><br>";
       // echo "Seu SteamID é: $steamID<br>";
       // echo "Seu saldo atual é: R$ $saldo<br>";
    } else {
       // echo "Usuário não encontrado no banco de dados.";
    }

    // Fechar a conexão com o banco de dados
    $stmt->close();
    $conn->close();
    
} else {
    //echo "Você não está logado.";
}
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <style>
        body{
            font-family: 'Poppins', sans-serif;
        }
        </style>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $nomeDoSite; ?> - Compre e Venda Skins de Counter Strike 2 </title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <!-- Estilos customizados -->
    <link href="css/index.css" rel="stylesheet">
</head>
<body>

   <!-- Overlay -->
   <div class="overlay" id="overlay"></div>

<!-- Modal centralizado -->
<div class="modal" id="modal" style="background-color: #030f1a; text-align: center; padding: 30px; width: 80%;">
    <div class="modal-header" style="text-align: center;">
        <h2 style="text-align: center; color: white;">Atualizar TradeLink</h2>
        <button style="color: white;" class="close-btn" id="closeModalBtn">&times;</button>
    </div>
    <div class="modal-body">
         <input type="url" style="width: 80%; height: 70px; background-color: none; border: 0px; border-radius: 10px;" id="tradelinkInput" placeholder="https://steamcommunity.com/tradeoffer/new/?partner=XXXX&token=XXXX" value="<?php echo isset($_SESSION['tradelink']) ? $_SESSION['tradelink'] : ''; ?>" required>
        <button class="update-btn" style="background-color: #00ced4; border-radius: 5px; height: 70px; width: 10%;" id="updateTradelinkBtn">Atualizar</button>
    </div>
            <p style="color: white;"><a style="text-decoration: underline; color: white;" href="https://steamcommunity.com/id/me/tradeoffers/privacy#trade_offer_access_url" target="_blank">Clique aqui</a> para encontrar o seu tradelink</p>

</div>

 

    <header>

   

    <nav class="navbar navbar-expand-lg navbar-dark navigation-bar">
    <div class="container-fluid">
        <a class="navbar-brand" href="#"><img style="width: 100px; height: auto; padding: 10px;" src="bleik.png"/></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div style="margin-left: 50px;" class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="#">   Comprar </a>
                </li>
                <li class="nav-item ">
                    <a class="nav-link" href="#"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cash-coin" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M11 15a4 4 0 1 0 0-8 4 4 0 0 0 0 8m5-4a5 5 0 1 1-10 0 5 5 0 0 1 10 0"/>
                    <path d="M9.438 11.944c.047.596.518 1.06 1.363 1.116v.44h.375v-.443c.875-.061 1.386-.529 1.386-1.207 0-.618-.39-.936-1.09-1.1l-.296-.07v-1.2c.376.043.614.248.671.532h.658c-.047-.575-.54-1.024-1.329-1.073V8.5h-.375v.45c-.747.073-1.255.522-1.255 1.158 0 .562.378.92 1.007 1.066l.248.061v1.272c-.384-.058-.639-.27-.696-.563h-.668zm1.36-1.354c-.369-.085-.569-.26-.569-.522 0-.294.216-.514.572-.578v1.1zm.432.746c.449.104.655.272.655.569 0 .339-.257.571-.709.614v-1.195z"/>
                    <path d="M1 0a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h4.083q.088-.517.258-1H3a2 2 0 0 0-2-2V3a2 2 0 0 0 2-2h10a2 2 0 0 0 2 2v3.528c.38.34.717.728 1 1.154V1a1 1 0 0 0-1-1z"/>
                    <path d="M9.998 5.083 10 5a2 2 0 1 0-3.132 1.65 6 6 0 0 1 3.13-1.567"/>
                    </svg> Vender</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-double-up" viewBox="0 0 16 16">
  <path fill-rule="evenodd" d="M7.646 2.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1-.708.708L8 3.707 2.354 9.354a.5.5 0 1 1-.708-.708z"/>
  <path fill-rule="evenodd" d="M7.646 6.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1-.708.708L8 7.707l-5.646 5.647a.5.5 0 0 1-.708-.708z"/>
</svg> Upgrade</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-seam" viewBox="0 0 16 16">
  <path d="M8.186 1.113a.5.5 0 0 0-.372 0L1.846 3.5l2.404.961L10.404 2zm3.564 1.426L5.596 5 8 5.961 14.154 3.5zm3.25 1.7-6.5 2.6v7.922l6.5-2.6V4.24zM7.5 14.762V6.838L1 4.239v7.923zM7.443.184a1.5 1.5 0 0 1 1.114 0l7.129 2.852A.5.5 0 0 1 16 3.5v8.662a1 1 0 0 1-.629.928l-7.185 2.874a.5.5 0 0 1-.372 0L.63 13.09a1 1 0 0 1-.63-.928V3.5a.5.5 0 0 1 .314-.464z"/>
</svg> Drop da Sorte</a>
                </li>
            </ul>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item meuCarrinho" style="text-align: center; vertical-align: center;">
                         <a class="nav-link base-button"> <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-cart3" viewBox="0 0 16 16">
                            <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .49.598l-1 5a.5.5 0 0 1-.465.401l-9.397.472L4.415 11H13a.5.5 0 0 1 0 1H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5M3.102 4l.84 4.479 9.144-.459L13.89 4zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4m7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4m-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2m7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2"/>
                            </svg>  <span style="margin-right: 20px;" class="cart-counter">0</span>  </a>
                </li>
                
                <li class="nav-item" id="logar-steam" <?php if ($logado) { echo 'style="display: none;"'; } ?>>
                    <a class="nav-link" style="color: white; background-color: #2f3331;" href="steamApi.php">
                    <svg aria-hidden="true" focusable="false" data-prefix="fab" data-icon="steam" class="svg-inline--fa fa-steam fa-w-16 text-[22px]" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 496 512" color="inherit"><path fill="currentColor" d="M496 256c0 137-111.2 248-248.4 248-113.8 0-209.6-76.3-239-180.4l95.2 39.3c6.4 32.1 34.9 56.4 68.9 56.4 39.2 0 71.9-32.4 70.2-73.5l84.5-60.2c52.1 1.3 95.8-40.9 95.8-93.5 0-51.6-42-93.5-93.7-93.5s-93.7 42-93.7 93.5v1.2L176.6 279c-15.5-.9-30.7 3.4-43.5 12.1L0 236.1C10.2 108.4 117.1 8 247.6 8 384.8 8 496 119 496 256zM155.7 384.3l-30.5-12.6a52.79 52.79 0 0 0 27.2 25.8c26.9 11.2 57.8-1.6 69-28.4 5.4-13 5.5-27.3.1-40.3-5.4-13-15.5-23.2-28.5-28.6-12.9-5.4-26.7-5.2-38.9-.6l31.5 13c19.8 8.2 29.2 30.9 20.9 50.7-8.3 19.9-31 29.2-50.8 21zm173.8-129.9c-34.4 0-62.4-28-62.4-62.3s28-62.3 62.4-62.3 62.4 28 62.4 62.3-27.9 62.3-62.4 62.3zm.1-15.6c25.9 0 46.9-21 46.9-46.8 0-25.9-21-46.8-46.9-46.8s-46.9 21-46.9 46.8c.1 25.8 21.1 46.8 46.9 46.8z"></path></svg>
                       Login
                    </a>
                </li>

                <div class="loggedIn" style="display: flex;" <?php if (!$logado) { echo 'style="display: none;"'; } ?>>

                    <li class="nav-item dropdown custom-menu" id="menu-usuario" style="padding: 15px;" <?php if (!$logado) { echo 'style="display: none;"'; } ?>>
                        <a class="nav-link custom-dropdown-toggle d-flex align-items-center" href="#" id="customNavbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="color: white; text-decoration: none;" <?php if (!$logado) { echo 'style="display: none;"'; } ?>>
                            <div <?php if (!$logado) { echo 'style="display: none;"'; } ?>><?php echo "<img src='$avatarUsuario' alt='Avatar'>" ?></div> &nbsp;&nbsp;
                            <h3 id="nome-usuario" class="nameUser" style="color: white; font-weight: bolder; font-size: 13pt; margin: 0;">
                                <span id="nomeUsuario"><?php echo $nomeUsuario; ?></span>
                            </h3>
                            &nbsp;
                            <div <?php if (!$logado) { echo 'style="display: none;"'; } ?>>
                            <svg  xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-down-square-fill" viewBox="0 0 16 16">
                                <path d="M0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm4 4a.5.5 0 0 0-.374.832l4 4.5a.5.5 0 0 0 .748 0l4-4.5A.5.5 0 0 0 12 6z"/>
                            </svg>
                             </div>
                        </a>
                        <ul class="custom-dropdown-menu" aria-labelledby="customNavbarDropdown" <?php if (!$logado) { echo 'style="display: none;"'; } ?>>
                            <li><a class="custom-dropdown-item" href="#"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                                <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
                                <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1"/>
                                </svg> Perfil</a></li>
                                                        <li><a class="custom-dropdown-item" href="#"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-wallet-fill" viewBox="0 0 16 16">
                                <path d="M1.5 2A1.5 1.5 0 0 0 0 3.5v2h6a.5.5 0 0 1 .5.5c0 .253.08.644.306.958.207.288.557.542 1.194.542s.987-.254 1.194-.542C9.42 6.644 9.5 6.253 9.5 6a.5.5 0 0 1 .5-.5h6v-2A1.5 1.5 0 0 0 14.5 2z"/>
                                <path d="M16 6.5h-5.551a2.7 2.7 0 0 1-.443 1.042C9.613 8.088 8.963 8.5 8 8.5s-1.613-.412-2.006-.958A2.7 2.7 0 0 1 5.551 6.5H0v6A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5z"/>
                                </svg> Adicionar Saldo</a></li>
                                                        <li><a class="custom-dropdown-item" href="#"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clock-history" viewBox="0 0 16 16">
                                <path d="M8.515 1.019A7 7 0 0 0 8 1V0a8 8 0 0 1 .589.022zm2.004.45a7 7 0 0 0-.985-.299l.219-.976q.576.129 1.126.342zm1.37.71a7 7 0 0 0-.439-.27l.493-.87a8 8 0 0 1 .979.654l-.615.789a7 7 0 0 0-.418-.302zm1.834 1.79a7 7 0 0 0-.653-.796l.724-.69q.406.429.747.91zm.744 1.352a7 7 0 0 0-.214-.468l.893-.45a8 8 0 0 1 .45 1.088l-.95.313a7 7 0 0 0-.179-.483m.53 2.507a7 7 0 0 0-.1-1.025l.985-.17q.1.58.116 1.17zm-.131 1.538q.05-.254.081-.51l.993.123a8 8 0 0 1-.23 1.155l-.964-.267q.069-.247.12-.501m-.952 2.379q.276-.436.486-.908l.914.405q-.24.54-.555 1.038zm-.964 1.205q.183-.183.35-.378l.758.653a8 8 0 0 1-.401.432z"/>
                                <path d="M8 1a7 7 0 1 0 4.95 11.95l.707.707A8.001 8.001 0 1 1 8 0z"/>
                                <path d="M7.5 3a.5.5 0 0 1 .5.5v5.21l3.248 1.856a.5.5 0 0 1-.496.868l-3.5-2A.5.5 0 0 1 7 9V3.5a.5.5 0 0 1 .5-.5"/>
                                </svg> Pedidos</a></li>
                                                        <li><a class="custom-dropdown-item" href="#"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cash-coin" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M11 15a4 4 0 1 0 0-8 4 4 0 0 0 0 8m5-4a5 5 0 1 1-10 0 5 5 0 0 1 10 0"/>
                                <path d="M9.438 11.944c.047.596.518 1.06 1.363 1.116v.44h.375v-.443c.875-.061 1.386-.529 1.386-1.207 0-.618-.39-.936-1.09-1.1l-.296-.07v-1.2c.376.043.614.248.671.532h.658c-.047-.575-.54-1.024-1.329-1.073V8.5h-.375v.45c-.747.073-1.255.522-1.255 1.158 0 .562.378.92 1.007 1.066l.248.061v1.272c-.384-.058-.639-.27-.696-.563h-.668zm1.36-1.354c-.369-.085-.569-.26-.569-.522 0-.294.216-.514.572-.578v1.1zm.432.746c.449.104.655.272.655.569 0 .339-.257.571-.709.614v-1.195z"/>
                                <path d="M1 0a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h4.083q.088-.517.258-1H3a2 2 0 0 0-2-2V3a2 2 0 0 0 2-2h10a2 2 0 0 0 2 2v3.528c.38.34.717.728 1 1.154V1a1 1 0 0 0-1-1z"/>
                                <path d="M9.998 5.083 10 5a2 2 0 1 0-3.132 1.65 6 6 0 0 1 3.13-1.567"/>
                                </svg> Vendas</a></li>
                                                        <li><a id="openModalBtn" class="custom-dropdown-item" href="#"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star-fill" viewBox="0 0 16 16">
                                <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                                </svg> TradeLink</a></li>
                                                        <div class="divider"></div>
                                                        <li><a class="custom-dropdown-item" href="logout.php"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-right" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0z"/>
                                <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z"/>
                                </svg> Sair</a>
                            </li>
                        </ul>
                    </li>
              

                
                <li class="nav-item carteira" id="carteira" <?php if (!$logado) { echo 'style="display: none;"'; } ?>>
                    <a class="nav-link">
                            <h4 style="color: white; vertical-align: middle; background-color: #2e4f60; padding: 15px; border-radius: 10px; font-size: 13pt;" id="saldo-valor"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-wallet-fill" viewBox="0 0 16 16">
                            <path d="M1.5 2A1.5 1.5 0 0 0 0 3.5v2h6a.5.5 0 0 1 .5.5c0 .253.08.644.306.958.207.288.557.542 1.194.542s.987-.254 1.194-.542C9.42 6.644 9.5 6.253 9.5 6a.5.5 0 0 1 .5-.5h6v-2A1.5 1.5 0 0 0 14.5 2z"/>
                            <path d="M16 6.5h-5.551a2.7 2.7 0 0 1-.443 1.042C9.613 8.088 8.963 8.5 8 8.5s-1.613-.412-2.006-.958A2.7 2.7 0 0 1 5.551 6.5H0v6A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5z"/>
                            </svg> R$ <span id="saldoValue"><?php echo $saldo; ?></span>&nbsp;&nbsp; <span style="background-color: #367ca1; padding: 5px; border-radius: 5px;">+</span>
                        </h4>
                    </a>
                </li>
            </div>
                 


            </ul>
        </div>
    </div>
</nav>

<nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #10171a;">
    <div class="container-fluid">
         
        <div class="collapse navbar-collapse" id="navbarSec">
            <ul class="navbar-nav mx-auto">
            <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" style="font-weight: bolder;" href="#" id="navbarDropdownFacas" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="icons/knife_t.svg" width="30px" height="30px" style="transform: rotate(-12deg);" /> FACAS
            </a>
            <ul class="dropdown-menu collapsed-design" aria-labelledby="navbarDropdownFacas">
                <div class="dropdown-column">
                    <a class="dropdown-item text-menu-knife" href="#"><img src="icons/knife_push.svg" width="30px" height="30px" style="transform: rotate(-12deg);" /> ADAGAS SOMBRIAS</a>
                    <a class="dropdown-item text-menu-knife" href="#"><img src="icons/bayonet.svg" width="30px" height="30px" style="transform: rotate(-12deg);" /> BAIONETA</a>
                    <a class="dropdown-item text-menu-knife" href="#"><img src="icons/knife_m9_bayonet.svg" width="30px" height="30px" style="transform: rotate(-12deg);" /> BAIONETA M9</a>
                    <a class="dropdown-item text-menu-knife" href="#"><img src="icons/knife_flip.svg" width="30px" height="30px" style="transform: rotate(-12deg);" /> CANIVETE</a>
                </div>
                <div class="dropdown-column">
                    <a class="dropdown-item text-menu-knife" href="#"><img src="icons/knife_butterfly.svg" width="30px" height="30px" style="transform: rotate(-12deg);" /> CANIVETE BORBOLETA</a>
                    <a class="dropdown-item text-menu-knife" href="#"><img src="icons/knife_falchion.svg" width="30px" height="30px" style="transform: rotate(-12deg);" /> CANIVETE FALCHION</a>
                    <a class="dropdown-item text-menu-knife" href="#"><img src="icons/knife_bowie.svg" width="30px" height="30px" style="transform: rotate(-12deg);" /> FACA BOWIE</a>
                    <a class="dropdown-item text-menu-knife" href="#"><img src="icons/knife_css.svg" width="30px" height="30px" style="transform: rotate(-12deg);" /> FACA CLÁSSICA</a>
                </div>
                <div class="dropdown-column">
                    <a class="dropdown-item text-menu-knife" href="#"><img src="icons/knife_cord.svg" width="30px" height="30px" style="transform: rotate(-12deg);" /> FACA DE CORDAME</a>
                    <a class="dropdown-item text-menu-knife" href="#"><img src="icons/knife_tactical.svg" width="30px" height="30px" style="transform: rotate(-12deg);" /> FACA DO CAÇADOR</a>
                    <a class="dropdown-item text-menu-knife" href="#"><img src="icons/knife_skeleton.svg" width="30px" height="30px" style="transform: rotate(-12deg);" /> FACA ESQUELETO</a>
                    <a class="dropdown-item text-menu-knife" href="#"><img src="icons/knife_gut.svg" width="30px" height="30px" style="transform: rotate(-12deg);" /> FACA GUT HOOK</a>
                </div>
                <div class="dropdown-column">
                    <a class="dropdown-item text-menu-knife" href="#"><img src="icons/knife_kukri.svg" width="30px" height="30px" style="transform: rotate(-12deg);" /> FACA KUKRI</a>
                    <a class="dropdown-item text-menu-knife" href="#"><img src="icons/knife_gypsy_jackknife.svg" width="30px" height="30px" style="transform: rotate(-12deg);" /> FACA NAVAJA</a>
                    <a class="dropdown-item text-menu-knife" href="#"><img src="icons/knife_outdoor.svg" width="30px" height="30px" style="transform: rotate(-12deg);" /> FACA NÔMADE</a>
                    <a class="dropdown-item text-menu-knife" href="#"><img src="icons/knife_canis.svg" width="30px" height="30px" style="transform: rotate(-12deg);" /> FACA DE SOBREVIVÊNCIA</a>
                </div>
                <div class="dropdown-column">
                    <a class="dropdown-item text-menu-knife" href="#"><img src="icons/knife_stiletto.svg" width="30px" height="30px" style="transform: rotate(-12deg);" /> FACA STILETTO</a>
                    <a class="dropdown-item text-menu-knife" href="#"><img src="icons/knife_widowmaker.svg" width="30px" height="30px" style="transform: rotate(-12deg);" /> FACA TALON</a>
                    <a class="dropdown-item text-menu-knife" href="#"><img src="icons/knife_ursus.svg" width="30px" height="30px" style="transform: rotate(-12deg);" /> FACA URSUS</a>
                    <a class="dropdown-item text-menu-knife" href="#"><img src="icons/knife_karambit.svg" width="30px" height="30px" style="transform: rotate(-12deg);" /> KARAMBIT</a>
                </div>

                        </ul>
        </li>



                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" style="font-weight: bolder;" href="#" id="navbarDropdownFacas" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="icons/luva.png" width="30px" height="30px" style="transform: rotate(-34deg);" /> LUVAS
                    </a>
                    <ul class="dropdown-menu collapsed-design" aria-labelledby="navbarDropdownFacas">
                <div class="dropdown-column">
                    <a class="dropdown-item text-menu-knife" href="#"><img src="icons/faixas.png" width="30px" height="30px" style="transform: rotate(-12deg);" /> FAIXAS</a>
                    <a class="dropdown-item text-menu-knife" href="#"><img src="icons/cao.png" width="30px" height="30px" style="transform: rotate(-12deg);" /> LUVAS DO CÃO DE CAÇA</a>
                    <a class="dropdown-item text-menu-knife" href="#"><img src="icons/presa.png" width="30px" height="30px" style="transform: rotate(-12deg);" /> LUVAS DE PRESA QUEBRADA</a>
                    <a class="dropdown-item text-menu-knife" href="#"><img src="icons/motorista.png" width="30px" height="30px" style="transform: rotate(-12deg);" /> LUVAS DE MOTORISTA</a>
                </div>
                <div class="dropdown-column">
                    <a class="dropdown-item text-menu-knife" href="#"><img src="icons/hidra.png" width="30px" height="30px" style="transform: rotate(-12deg);" /> LUVAS DA HIDRA</a>
                    <a class="dropdown-item text-menu-knife" href="#"><img src="icons/motociclismo.png" width="30px" height="30px" style="transform: rotate(-12deg);" /> LUVAS DE MOTOCICLISMO</a>
                    <a class="dropdown-item text-menu-knife" href="#"><img src="icons/luva.png" width="30px" height="30px" style="transform: rotate(-12deg);" /> LUVAS DE ESPECIALISTA</a>
                    <a class="dropdown-item text-menu-knife" href="#"><img src="icons/esportivas.png" width="30px" height="30px" style="transform: rotate(-12deg);" /> LUVAS ESPORTIVAS</a>
                </div>
             

                        </ul>

              
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" style="font-weight: bolder;" href="#" id="navbarDropdownFacas" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="icons/awp.svg" width="30px" height="30px" style="transform: rotate(-12deg);" /> SNIPER RIFLES
                    </a>
                    <ul class="dropdown-menu collapsed-design" aria-labelledby="navbarDropdownFacas">
                <div class="dropdown-column">
                    <a class="dropdown-item text-menu-knife" href="#"><img src="icons/awp.svg" width="30px" height="30px" style="transform: rotate(-12deg);" /> AWP</a>
                    <a class="dropdown-item text-menu-knife" href="#"><img src="icons/bayonet.svg" width="30px" height="30px" style="transform: rotate(-12deg);" /> G3SG1</a>
                    <a class="dropdown-item text-menu-knife" href="#"><img src="icons/scar20.svg" width="30px" height="30px" style="transform: rotate(-12deg);" /> SCAR-20</a>
                    <a class="dropdown-item text-menu-knife" href="#"><img src="icons/ssg08.svg" width="30px" height="30px" style="transform: rotate(-12deg);" /> SSG08</a>
                </div>
              
             

                        </ul>
              
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle " style="font-weight: bolder;" href="#" id="navbarDropdownFacas" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="icons/ak47.svg" width="30px" height="30px" style="transform: rotate(-12deg);" /> RIFLES
                    </a>
                    <ul class="dropdown-menu collapsed-design" aria-labelledby="navbarDropdownFacas">
                <div class="dropdown-column">
                    <a class="dropdown-item text-menu-knife" href="#"><img src="icons/ak47.svg" width="30px" height="30px" style="transform: rotate(-12deg);" /> AK-47</a>
                    <a class="dropdown-item text-menu-knife" href="#"><img src="icons/aug.svg" width="30px" height="30px" style="transform: rotate(-12deg);" /> AUG</a>
                    <a class="dropdown-item text-menu-knife" href="#"><img src="icons/famas.svg" width="30px" height="30px" style="transform: rotate(-12deg);" /> FAMAS</a>
                    <a class="dropdown-item text-menu-knife" href="#"><img src="icons/galilar.svg" width="30px" height="30px" style="transform: rotate(-12deg);" /> GALIL AR</a>
                </div>
                <div class="dropdown-column">
                    <a class="dropdown-item text-menu-knife" href="#"><img src="icons/m4a1_silencer.svg" width="30px" height="30px" style="transform: rotate(-12deg);" /> M4A1-S</a>
                    <a class="dropdown-item text-menu-knife" href="#"><img src="icons/m4a1.svg" width="30px" height="30px" style="transform: rotate(-12deg);" /> M4A4</a>
                    <a class="dropdown-item text-menu-knife" href="#"><img src="icons/sg556.svg" width="30px" height="30px" style="transform: rotate(-12deg);" /> SG 556</a>
                 </div>
             

                        </ul>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" style="font-weight: bolder;" href="#" id="navbarDropdownFacas" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="icons/usp_silencer.svg" width="30px" height="30px" style="transform: rotate(-12deg);" /> PISTOLAS
                    </a>
                    <ul class="dropdown-menu collapsed-design" aria-labelledby="navbarDropdownFacas">
                <div class="dropdown-column">
                    <a class="dropdown-item text-menu-knife" href="#"><img src="icons/elite.svg" width="30px" height="30px" style="transform: rotate(-12deg);" /> BERETTAS DUPLAS</a>
                    <a class="dropdown-item text-menu-knife" href="#"><img src="icons/cz75a.svg" width="30px" height="30px" style="transform: rotate(-12deg);" /> CZ75-AUTO</a>
                    <a class="dropdown-item text-menu-knife" href="#"><img src="icons/deagle.svg" width="30px" height="30px" style="transform: rotate(-12deg);" /> DESERT EAGLE</a>
                    <a class="dropdown-item text-menu-knife" href="#"><img src="icons/fiveseven.svg" width="30px" height="30px" style="transform: rotate(-12deg);" /> FIVE SEVEN</a>
                </div>
                <div class="dropdown-column">
                    <a class="dropdown-item text-menu-knife" href="#"><img src="icons/glock.svg" width="30px" height="30px" style="transform: rotate(-12deg);" /> GLOCK-18</a>
                    <a class="dropdown-item text-menu-knife" href="#"><img src="icons/p2000.svg" width="30px" height="30px" style="transform: rotate(-12deg);" /> P2000</a>
                    <a class="dropdown-item text-menu-knife" href="#"><img src="icons/p250.svg" width="30px" height="30px" style="transform: rotate(-12deg);" /> P250</a>
                    <a class="dropdown-item text-menu-knife" href="#"><img src="icons/revolver.svg" width="30px" height="30px" style="transform: rotate(-12deg);" /> REVÓLVER R8</a>
                </div>

                <div class="dropdown-column">
                    <a class="dropdown-item text-menu-knife" href="#"><img src="icons/tec9.svg" width="30px" height="30px" style="transform: rotate(-12deg);" /> TEC-9</a>
                    <a class="dropdown-item text-menu-knife" href="#"><img src="icons/usp_silencer.svg" width="30px" height="30px" style="transform: rotate(-12deg);" /> USP-S</a>
                </div>
             
             

                        </ul>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" style="font-weight: bolder;" href="#" id="navbarDropdownFacas" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="icons/mp9.svg" width="30px" height="30px" style="transform: rotate(-12deg);" /> SMGS
                    </a>
                    <ul class="dropdown-menu collapsed-design" aria-labelledby="navbarDropdownFacas">
                <div class="dropdown-column">
                    <a class="dropdown-item text-menu-knife" href="#"><img src="icons/mac10.svg" width="30px" height="30px" style="transform: rotate(-12deg);" /> MAC-10</a>
                    <a class="dropdown-item text-menu-knife" href="#"><img src="icons/mp5sd.svg" width="30px" height="30px" style="transform: rotate(-12deg);" /> MP5-SD</a>
                    <a class="dropdown-item text-menu-knife" href="#"><img src="icons/mp7.svg" width="30px" height="30px" style="transform: rotate(-12deg);" /> MP7</a>
                    <a class="dropdown-item text-menu-knife" href="#"><img src="icons/mp9.svg" width="30px" height="30px" style="transform: rotate(-12deg);" /> MP9</a>
                </div>
                <div class="dropdown-column">
                    <a class="dropdown-item text-menu-knife" href="#"><img src="icons/p90.svg" width="30px" height="30px" style="transform: rotate(-12deg);" /> P90</a>
                    <a class="dropdown-item text-menu-knife" href="#"><img src="icons/bizon.svg" width="30px" height="30px" style="transform: rotate(-12deg);" /> PP-BIZON</a>
                    <a class="dropdown-item text-menu-knife" href="#"><img src="icons/ump45.svg" width="30px" height="30px" style="transform: rotate(-12deg);" /> UMP-45</a>
                 </div>
             

                        </ul>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" style="font-weight: bolder;" href="#" id="navbarDropdownFacas" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-knife"></i> MAIS
                    </a>
                    <ul class="dropdown-menu collapsed-design" aria-labelledby="navbarDropdownFacas">
                <div class="dropdown-column">
                    <a class="dropdown-item text-menu-knife" href="#"><img src="icons/xm1014.svg" width="30px" height="30px" style="transform: rotate(-12deg);" /> ESCOPETAS</a>
                    <a class="dropdown-item text-menu-knife" href="#"><img src="icons/negev.svg" width="30px" height="30px" style="transform: rotate(-12deg);" /> METRALHADORAS</a>
                    <a class="dropdown-item text-menu-knife" href="#"><img src="icons/helmet.svg" width="30px" height="30px" style="transform: rotate(-12deg);" /> AGENTES</a>
                    <a class="dropdown-item text-menu-knife" href="#"><img src="icons/planted_c4_survival.svg" width="30px" height="30px" style="transform: rotate(-12deg);" /> ADESIVOS</a>
                </div>
                <div class="dropdown-column">
                    <a class="dropdown-item text-menu-knife" href="#"><img src="icons/disco.svg" width="30px" height="30px" style="transform: rotate(-12deg);" /> TRILHAS SONORAS</a>
                    <a class="dropdown-item text-menu-knife" href="#"><img src="icons/zone_repulsor.svg" width="30px" height="30px" style="transform: rotate(-12deg);" /> EMBLEMAS</a>
                    <a class="dropdown-item text-menu-knife" href="#"><img src="icons/key.svg" width="30px" height="30px" style="transform: rotate(-12deg);" /> CHAVES</a>
                    <a class="dropdown-item text-menu-knife" href="#"><img src="icons/flair0.svg" width="30px" height="30px" style="transform: rotate(-12deg);" /> BROCHES</a>
                </div>
                <div class="dropdown-column">
                    <a class="dropdown-item text-menu-knife" href="#"><img src="icons/spray0.svg" width="30px" height="30px" style="transform: rotate(-12deg);" /> GRAFITES</a>
                    <a class="dropdown-item text-menu-knife" href="#"><img src="icons/taser.svg" width="30px" height="30px" style="transform: rotate(-12deg);" /> ZEUS</a>
                    <a class="dropdown-item text-menu-knife" href="#"><img src="icons/defuser.svg" width="30px" height="30px" style="transform: rotate(-12deg);" /> OUTROS</a>
                 </div>
               
             

                        </ul>
                </li>

             </ul>
        </div>
    </div>
</nav>



    </header>

    <div id="slideshowContainer" class="slideshow-container">
    <!-- Slides -->
    <div class="mySlide fade">
        <img src="imgs/ban-top-1920px.png" style="width:100%"> <!-- Substitua com seu próprio link de imagem -->
    </div>
    <div class="mySlide fade">
        <img src="imgs/ban2-top-1920px.png" style="width:100%"> <!-- Substitua com seu próprio link de imagem -->
    </div>

    <!-- Botões de Navegação -->
    <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
    <a class="next" onclick="plusSlides(1)">&#10095;</a>
</div>

<main class="main-content" style="margin-top: 10px;">
<div class="container-fluid">
    <div class="row">
 
        <!-- Coluna de Filtros -->
        <div class="col-md-3">
            <div class="filter-section">
                <h4>Preço</h4>
                <!-- Range para preço -->
                <input type="range" class="form-range" min="0" max="100000" step="500">
                <div>
                    <p style="float: left;">0</p>
                    <p style="float: right;">900K</p>

                </div>
                
                <div class="holder-inputs">
                    
                    <input value="0" class="values-inputs"></input>
                    <input value="100.000" class="values-inputs"></input><button class="btn-ok">OK</button>
                </div>

                <h4>Bloqueio</h4>
                <!-- Lista de opções para desgaste -->
                <select class="form-select">
                    <option>Desbloqueado</option>
                    <option>1 Dia</option>
                    <option>2 Dia</option>
                    <option>3 Dia</option>
                    <option>4 Dia</option>
                    <option>5 Dia</option>
                    <option>6 Dia</option>
                    <option>7 Dia</option>
 
                </select>

                <div class="divider"></div>


                <h4>Cores</h4>
                <!-- Lista de opções para desgaste -->
                <select class="form-select">
                    <option>Branco</option>
                    <option>Preto</option>
                    <option>Azul</option>
                    <option>Verde</option>
                    <option>Amarelo</option>
                    <option>Rosa</option>
                    <option>Roxo</option>
                    <option>Laranja</option>
                    <option>Cinza</option>

                </select>

                <div class="divider"></div>


                <h4>Desgaste</h4>
                <!-- Lista de opções para desgaste -->
                <select class="form-select">
                    <option>Factory New (FN)</option>
                    <option>Minimal Wear (MW)</option>
                    <option>Field Tested (FT)</option>
                    <option>Well Worn (WW)</option>
                    <option>Battle Scarred (BS)</option>
                    <option>Vanilla</option>

                </select>

                <div class="divider"></div>


                <h4>Categoria</h4>
                <!-- Lista de opções para desgaste -->
                <select class="form-select">
                    <option>StatTrack</option>
                    <option>Souvenir</option>
                </select>

               

 
            </div>
        </div>
        

        <!-- Coluna de Produtos -->
        <div class="col-md-9">
            
            <div class="product-grid">
        
            <!-- Cada produto em um card -->
            <div class="card product-card">
                <div class="card-header">
                    <span class="delivery-tag"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" class="bi bi-star-fill" viewBox="0 0 16 16">
  <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
</svg> Entrega Imediata</span>
                    <h5 class="price-real">R$28.606,00</h5>
                    <p class="price-steam">Preço Steam: R$32.398,00</p>
                </div>
                <img src="imgs/facas/39448476285_1-thumbnail.webp" alt="Canivete Borboleta" class="product-image">
                <div class="card-body">
                    <h5 class="product-section">Canivete Borboleta</h5>
                    <p class="product-name">Gamma Doppler Phase 2 Gama</p>
                    <p class="product-condition">Nova de Fábrica</p>
                    <div class="h-4 w-[85%] z-10"><div class="flex flex-col w-full"><div class="w-full rounded-sm sm:min-h-[5px]" style="height: 5px; background-image: linear-gradient(to right, rgb(186, 255, 174), rgb(155, 255, 139), rgb(250, 255, 0), rgb(255, 165, 81), rgb(250, 95, 95));"></div><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="caret-up" class="svg-inline--fa fa-caret-up fa-w-10 " role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" width="12" style="position: relative; left: 2.1123%; top: -5px;"><path fill="currentColor" d="M288.662 352H31.338c-17.818 0-26.741-21.543-14.142-34.142l128.662-128.662c7.81-7.81 20.474-7.81 28.284 0l128.662 128.662c12.6 12.599 3.676 34.142-14.142 34.142z"></path></svg><p class="font-sans text-text-light-gray text-xs mt-[-10px] sm:text-[10px] w-full text-center float-number">0.02347</p></div></div>
                    <button class="btn btn-primary btn-add-to-cart">Adicionar ao Carrinho</button>
                </div>
            </div>
            <!-- Cada produto em um card -->

              <!-- Cada produto em um card -->
              <div class="card product-card">
                <div class="card-header">
                    <span class="delivery-tag"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" class="bi bi-star-fill" viewBox="0 0 16 16">
  <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
</svg> Entrega Imediata</span>
                    <h5 class="price-real">R$28.606,00</h5>
                    <p class="price-steam">Preço Steam: R$32.398,00</p>
                </div>
                <img src="imgs/facas/39448476285_1-thumbnail.webp" alt="Canivete Borboleta" class="product-image">
                <div class="card-body">
                    <h5 class="product-section">Canivete Borboleta</h5>
                    <p class="product-name">Gamma Doppler Phase 2 Gama</p>
                    <p class="product-condition">Nova de Fábrica</p>
                    <div class="h-4 w-[85%] z-10"><div class="flex flex-col w-full"><div class="w-full rounded-sm sm:min-h-[5px]" style="height: 5px; background-image: linear-gradient(to right, rgb(186, 255, 174), rgb(155, 255, 139), rgb(250, 255, 0), rgb(255, 165, 81), rgb(250, 95, 95));"></div><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="caret-up" class="svg-inline--fa fa-caret-up fa-w-10 " role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" width="12" style="position: relative; left: 2.1123%; top: -5px;"><path fill="currentColor" d="M288.662 352H31.338c-17.818 0-26.741-21.543-14.142-34.142l128.662-128.662c7.81-7.81 20.474-7.81 28.284 0l128.662 128.662c12.6 12.599 3.676 34.142-14.142 34.142z"></path></svg><p class="font-sans text-text-light-gray text-xs mt-[-10px] sm:text-[10px] w-full text-center float-number">0.02347</p></div></div>
                    <button class="btn btn-primary btn-add-to-cart">Adicionar ao Carrinho</button>
                </div>
            </div>
            <!-- Cada produto em um card -->

              <!-- Cada produto em um card -->
              <div class="card product-card">
                <div class="card-header">
                    <span class="delivery-tag"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" class="bi bi-star-fill" viewBox="0 0 16 16">
  <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
</svg> Entrega Imediata</span>
                    <h5 class="price-real">R$28.606,00</h5>
                    <p class="price-steam">Preço Steam: R$32.398,00</p>
                </div>
                <img src="imgs/facas/39448476285_1-thumbnail.webp" alt="Canivete Borboleta" class="product-image">
                <div class="card-body">
                    <h5 class="product-section">Canivete Borboleta</h5>
                    <p class="product-name">Gamma Doppler Phase 2 Gama</p>
                    <p class="product-condition">Nova de Fábrica</p>
                    <div class="h-4 w-[85%] z-10"><div class="flex flex-col w-full"><div class="w-full rounded-sm sm:min-h-[5px]" style="height: 5px; background-image: linear-gradient(to right, rgb(186, 255, 174), rgb(155, 255, 139), rgb(250, 255, 0), rgb(255, 165, 81), rgb(250, 95, 95));"></div><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="caret-up" class="svg-inline--fa fa-caret-up fa-w-10 " role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" width="12" style="position: relative; left: 2.1123%; top: -5px;"><path fill="currentColor" d="M288.662 352H31.338c-17.818 0-26.741-21.543-14.142-34.142l128.662-128.662c7.81-7.81 20.474-7.81 28.284 0l128.662 128.662c12.6 12.599 3.676 34.142-14.142 34.142z"></path></svg><p class="font-sans text-text-light-gray text-xs mt-[-10px] sm:text-[10px] w-full text-center float-number">0.02347</p></div></div>
                    <button class="btn btn-primary btn-add-to-cart">Adicionar ao Carrinho</button>
                </div>
            </div>
            <!-- Cada produto em um card -->

              <!-- Cada produto em um card -->
              <div class="card product-card">
                <div class="card-header">
                    <span class="delivery-tag"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" class="bi bi-star-fill" viewBox="0 0 16 16">
  <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
</svg> Entrega Imediata</span>
                    <h5 class="price-real">R$28.606,00</h5>
                    <p class="price-steam">Preço Steam: R$32.398,00</p>
                </div>
                <img src="imgs/facas/39448476285_1-thumbnail.webp" alt="Canivete Borboleta" class="product-image">
                <div class="card-body">
                    <h5 class="product-section">Canivete Borboleta</h5>
                    <p class="product-name">Gamma Doppler Phase 2 Gama</p>
                    <p class="product-condition">Nova de Fábrica</p>
                    <div class="h-4 w-[85%] z-10"><div class="flex flex-col w-full"><div class="w-full rounded-sm sm:min-h-[5px]" style="height: 5px; background-image: linear-gradient(to right, rgb(186, 255, 174), rgb(155, 255, 139), rgb(250, 255, 0), rgb(255, 165, 81), rgb(250, 95, 95));"></div><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="caret-up" class="svg-inline--fa fa-caret-up fa-w-10 " role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" width="12" style="position: relative; left: 2.1123%; top: -5px;"><path fill="currentColor" d="M288.662 352H31.338c-17.818 0-26.741-21.543-14.142-34.142l128.662-128.662c7.81-7.81 20.474-7.81 28.284 0l128.662 128.662c12.6 12.599 3.676 34.142-14.142 34.142z"></path></svg><p class="font-sans text-text-light-gray text-xs mt-[-10px] sm:text-[10px] w-full text-center float-number">0.02347</p></div></div>
                    <button class="btn btn-primary btn-add-to-cart">Adicionar ao Carrinho</button>
                </div>
            </div>
            <!-- Cada produto em um card -->

              <!-- Cada produto em um card -->
              <div class="card product-card">
                <div class="card-header">
                    <span class="delivery-tag"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" class="bi bi-star-fill" viewBox="0 0 16 16">
  <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
</svg> Entrega Imediata</span>
                    <h5 class="price-real">R$28.606,00</h5>
                    <p class="price-steam">Preço Steam: R$32.398,00</p>
                </div>
                <img src="imgs/facas/39448476285_1-thumbnail.webp" alt="Canivete Borboleta" class="product-image">
                <div class="card-body">
                    <h5 class="product-section">Canivete Borboleta</h5>
                    <p class="product-name">Gamma Doppler Phase 2 Gama</p>
                    <p class="product-condition">Nova de Fábrica</p>
                    <div class="h-4 w-[85%] z-10"><div class="flex flex-col w-full"><div class="w-full rounded-sm sm:min-h-[5px]" style="height: 5px; background-image: linear-gradient(to right, rgb(186, 255, 174), rgb(155, 255, 139), rgb(250, 255, 0), rgb(255, 165, 81), rgb(250, 95, 95));"></div><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="caret-up" class="svg-inline--fa fa-caret-up fa-w-10 " role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" width="12" style="position: relative; left: 2.1123%; top: -5px;"><path fill="currentColor" d="M288.662 352H31.338c-17.818 0-26.741-21.543-14.142-34.142l128.662-128.662c7.81-7.81 20.474-7.81 28.284 0l128.662 128.662c12.6 12.599 3.676 34.142-14.142 34.142z"></path></svg><p class="font-sans text-text-light-gray text-xs mt-[-10px] sm:text-[10px] w-full text-center float-number">0.02347</p></div></div>
                    <button class="btn btn-primary btn-add-to-cart">Adicionar ao Carrinho</button>
                </div>
            </div>
            <!-- Cada produto em um card -->
             
               
            </div>
        </div>
    </div>
</div>

</main>

<footer class="bg-dark text-white pt-4 pb-4">
    <div class="container">
        <div class="row" style="text-decoration: none;">
            <div class="col-md-2">
            <h5>FAQ</h5>
                <br>
                 <ul class="list-unstyled">
                     <li><a style="text-decoration: none;" href="#" class="text-white">Sobre Nós</a></li>
                    <li><a style="text-decoration: none;" href="#" class="text-white">Horários de Atendimento </a></li>
                    <li><a style="text-decoration: none;" href="#" class="text-white">Termos de Uso</a></li>


                </ul>
            </div>
            <div class="col-md-2">
            <h5>Ajuda</h5>
            <br>
                 <ul class="list-unstyled">
                    <li><a style="text-decoration: none;" href="#" class="text-white">Encomendas</a></li>
                    <li><a style="text-decoration: none;" href="#" class="text-white">Upgrades</a></li>
                    <li><a style="text-decoration: none;" href="#" class="text-white">Como Vender Suas Skins</a></li>
                    <li><a style="text-decoration: none;" href="#" class="text-white">Fale Conosco</a></li>


                </ul>
            </div>
        
            <div class="col-md-2">
                <h5>Redes Sociais</h5>
                <br>
                      <a href="#" class="text-white"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-instagram" viewBox="0 0 16 16">
                        <path d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.9 3.9 0 0 0-1.417.923A3.9 3.9 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.9 3.9 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.9 3.9 0 0 0-.923-1.417A3.9 3.9 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599s.453.546.598.92c.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.5 2.5 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.5 2.5 0 0 1-.92-.598 2.5 2.5 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233s.008-2.388.046-3.231c.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92s.546-.453.92-.598c.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92m-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217m0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334"/>
                        </svg></a> 
                                        <a href="#" class="text-white"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-facebook" viewBox="0 0 16 16">
                        <path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951"/>
                        </svg></a> 

                        <a href="#" class="text-white"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-youtube" viewBox="0 0 16 16">
                        <path d="M8.051 1.999h.089c.822.003 4.987.033 6.11.335a2.01 2.01 0 0 1 1.415 1.42c.101.38.172.883.22 1.402l.01.104.022.26.008.104c.065.914.073 1.77.074 1.957v.075c-.001.194-.01 1.108-.082 2.06l-.008.105-.009.104c-.05.572-.124 1.14-.235 1.558a2.01 2.01 0 0 1-1.415 1.42c-1.16.312-5.569.334-6.18.335h-.142c-.309 0-1.587-.006-2.927-.052l-.17-.006-.087-.004-.171-.007-.171-.007c-1.11-.049-2.167-.128-2.654-.26a2.01 2.01 0 0 1-1.415-1.419c-.111-.417-.185-.986-.235-1.558L.09 9.82l-.008-.104A31 31 0 0 1 0 7.68v-.123c.002-.215.01-.958.064-1.778l.007-.103.003-.052.008-.104.022-.26.01-.104c.048-.519.119-1.023.22-1.402a2.01 2.01 0 0 1 1.415-1.42c.487-.13 1.544-.21 2.654-.26l.17-.007.172-.006.086-.003.171-.007A100 100 0 0 1 7.858 2zM6.4 5.209v4.818l4.157-2.408z"/>
                        </svg></a> 

             </div>

            <div class="col-md-2">
                     <h5>Pagamentos</h5>
                     <br>
                       <img width="60px" height="20px" src="icons/ame_payment.png"/> 
                      <img width="60px" height="20px" src="icons/Americam_Nesha.svg"/> 
                      <img width="60px" height="20px" src="icons/Boleto_Nesha.svg"/> 
                      <img width="60px" height="20px" src="icons/Dinners_Nesha.svg"/> 
                      <img width="60px" height="20px" src="icons/Elo_Nesha.svg"/> 
                      <img width="60px" height="20px" src="icons/Hypercard_Nesha.svg"/> 
                      <img width="60px" height="20px" src="icons/Master_Nesha.svg"/> 
                      <img width="60px" height="20px" src="icons/Mp_Nesha.svg"/> 
                      <img width="60px" height="20px" src="icons/Pix_Nesha.svg"/> 
                      <img width="60px" height="20px" src="icons/Visa_Nesha.svg"/> 

             </div>

             <div class="col-md-2">
             <h5>Certificados</h5>
             <br>

                 <img width="100%" height="auto" src="icons/google-safe-browsing.png"/> 


             </div>


 
         
        </div>
    </div><br>
    <div class="md-center" style="text-align: center;">
    <p>© 2024 <?php echo $nomeDoSite; ?> - Cnpj: <?php echo $cnpj; ?> -  Todos os direitos reservados.</p>

    </div>


</footer>

 

    <!-- Bootstrap JS com Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
        
        <script>
        
        let slideIndex = 1;
        showSlides(slideIndex);

        function plusSlides(n) {
        showSlides(slideIndex += n);
        }

        function showSlides(n) {
        let i;
        let slides = document.getElementsByClassName("mySlide");
        if (n > slides.length) {slideIndex = 1}
        if (n < 1) {slideIndex = slides.length}
        for (i = 0; i < slides.length; i++) {
            slides[i].style.display = "none";
            slides[i].style.opacity = 0; // Garante que comece invisível
        }
        slides[slideIndex-1].style.display = "block";
        fadeIn(slides[slideIndex-1], 1000); // Animação de fade-in
        }

        function fadeIn(element, time) {
        element.style.opacity = 0;

        let last = +new Date();
        let tick = function() {
            element.style.opacity = +element.style.opacity + (new Date() - last) / time;
            last = +new Date();

            if (+element.style.opacity < 1) {
            (window.requestAnimationFrame && requestAnimationFrame(tick)) || setTimeout(tick, 16);
            }
        };

        tick();
        }


        </script>

<script>
        // Abrir e fechar modal
        const openModalBtn = document.getElementById('openModalBtn');
        const closeModalBtn = document.getElementById('closeModalBtn');
        const overlay = document.getElementById('overlay');
        const modal = document.getElementById('modal');

        openModalBtn.addEventListener('click', function() {
            overlay.style.display = 'block';
            modal.style.display = 'block';
        });

        closeModalBtn.addEventListener('click', function() {
            overlay.style.display = 'none';
            modal.style.display = 'none';
        });

        overlay.addEventListener('click', function() {
            overlay.style.display = 'none';
            modal.style.display = 'none';
        });

        // Verificar e enviar o tradelink
        const updateTradelinkBtn = document.getElementById('updateTradelinkBtn');
        updateTradelinkBtn.addEventListener('click', function() {
            const tradelinkInput = document.getElementById('tradelinkInput').value;

            // Verificar se é uma URL válida
            const urlPattern = /^https:\/\/steamcommunity\.com\/tradeoffer\/new\/\?partner=\d+&token=\w+$/;
            if (!urlPattern.test(tradelinkInput)) {
                alert('Por favor, insira um TradeLink válido.');
                return;
            }

            // Enviar o TradeLink via AJAX
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'atualizar_tradelink.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (xhr.status === 200) {
                    alert('TradeLink atualizado com sucesso!');
                    overlay.style.display = 'none';
                    modal.style.display = 'none';
                } else {
                    alert('Ocorreu um erro ao atualizar o TradeLink.');
                }
            };
            xhr.send('tradelink=' + encodeURIComponent(tradelinkInput));
        });
    </script>


</body>


</html>
