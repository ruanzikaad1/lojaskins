<?php
ini_set('display_errors', 1); // Habilita a exibição de erros
ini_set('display_startup_errors', 1); // Habilita a exibição de erros de inicialização
error_reporting(E_ALL); // Define o nível de relatório de erros para mostrar todos os erros


session_start(); // Inicia a sessão para armazenar dados

require 'vendor/autoload.php'; // Certifique-se de que o autoload do Composer está incluído
require 'config.php'; // Arquivo de configuração com os dados do banco de dados

$openid = new LightOpenID($dominio); // Substitua pelo seu domínio

if (!$openid->mode) {
    $openid->identity = 'https://steamcommunity.com/openid';
    header('Location: ' . $openid->authUrl());
} elseif ($openid->mode == 'cancel') {
    echo 'Login cancelado pelo usuário';
} else {
    if ($openid->validate()) {
        $id = $openid->identity;
        $steamID = str_replace('https://steamcommunity.com/openid/id/', '', $id);

        // Agora você pode usar o Steam Web API para buscar informações adicionais do usuário
        $apiKey = $apiKey; // Substitua pela sua API Key
        $url = "https://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=$apiKey&steamids=$steamID";
        $json = file_get_contents($url);
        $data = json_decode($json, true);

        // Informações do usuário
        $userInfo = $data['response']['players'][0];
        $nomeUsuario = $userInfo['personaname'];
        $avatarUsuario = $userInfo['avatar'];

        // Conectar ao banco de dados usando MySQLi
        $conn = mysqli_connect($host, $user, $password, $database);

        if (!$conn) {
            die("Falha na conexão com o banco de dados: " . mysqli_connect_error());
        }

        // Verificar se o steamID já está registrado no banco de dados
        $stmt = $conn->prepare("SELECT tradelink, saldo FROM users WHERE steam_id = ?");
        $stmt->bind_param("s", $steamID); // "s" indica que o parâmetro é uma string
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Se o usuário já existe, recupera tradelink e saldo
            $row = $result->fetch_assoc();
            $tradelink = $row['tradelink'];
            $saldo = $row['saldo'];

            // Armazenar as informações na sessão
            $_SESSION['steamID'] = $steamID;
            $_SESSION['nomeUsuario'] = $nomeUsuario;
            $_SESSION['avatarUsuario'] = $avatarUsuario;
            $_SESSION['tradelink'] = $tradelink;
            $_SESSION['saldo'] = $saldo;

        } else {
            // Se o usuário não existe, insere um novo registro
            $stmt = $conn->prepare("INSERT INTO users (steam_id, nome, avatar) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $steamID, $nomeUsuario, $avatarUsuario); // "sss" indica 3 strings
            $stmt->execute();

            // Definir valores padrão para tradelink e saldo para um novo usuário
            $_SESSION['tradelink'] = "";
            $_SESSION['saldo'] = 0.00;
        }

        // Fechar a conexão com o banco de dados
        $stmt->close();
        $conn->close();

        // Redirecionar para a index.php após o login
        header('Location: index.php');
        exit();
    } else {
        echo 'Falha na autenticação.';
    }
}
?>
