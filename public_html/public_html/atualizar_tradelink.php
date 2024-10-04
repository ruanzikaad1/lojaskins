<?php
session_start();
require('config.php'); // Arquivo de configuração do banco de dados

if (isset($_POST['tradelink']) && isset($_SESSION['steamID'])) {
    $tradelink = $_POST['tradelink'];
    $steamID = $_SESSION['steamID'];

    // Conectar ao banco de dados
    $conn = mysqli_connect($host, $user, $password, $database);

    if (!$conn) {
        die('Falha na conexão com o banco de dados: ' . mysqli_connect_error());
    }

    // Atualizar o tradelink onde o steam_id for igual ao steamID
    $stmt = $conn->prepare("UPDATE users SET tradelink = ? WHERE steam_id = ?");
    $stmt->bind_param("ss", $tradelink, $steamID);

    if ($stmt->execute()) {
        echo 'Success';
    } else {
        echo 'Error';
    }

    $stmt->close();
    $conn->close();
} else {
    echo 'Invalid Request';
}
?>
