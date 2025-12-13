<?php
// 1. Inicia a sessão (necessário para poder acessá-la e destruí-la)
session_start();

// 2. Limpa todas as variáveis de sessão (nome, id, etc.)
session_unset();

// 3. Destrói a sessão completamente no servidor
session_destroy();

// 4. Redireciona o usuário para a tela de Login
// Como estamos na pasta INICIO, subimos um nível (../) e entramos em LOGIN
header("Location:index.php");
exit;
?>