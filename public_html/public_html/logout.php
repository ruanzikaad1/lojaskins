<?php
session_start();
session_destroy(); // Destroi todas as variáveis de sessão
header('Location: index.php'); // Redireciona o usuário de volta à página inicial
exit();
?>
