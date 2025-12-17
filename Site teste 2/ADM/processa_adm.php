<?php
session_start();
// Caminho corrigido para subir um nível e encontrar a conexão
include '../conexao.php';

// 1. VERIFICAÇÃO DE SEGURANÇA CRUCIAL: APENAS ADMINISTRADORES
if (!isset($_SESSION['usuario_id']) || $_SESSION['perfil'] !== 'administrador') {
    header("Location: ../LOGIN/login.php");
    exit;
}

// Função para lidar com o upload de imagens (Reutilizável)
function handleUpload($file, $imagem_antiga = null) {
    if (isset($file) && $file['error'] === UPLOAD_ERR_OK) {
        
        $temp_name = $file['tmp_name'];
        $extensao = pathinfo($file['name'], PATHINFO_EXTENSION);
        $imagem_nome = uniqid() . '.' . $extensao;
        $upload_path = '../INICIO/imagens/' . $imagem_nome;
        
        if (!move_uploaded_file($temp_name, $upload_path)) {
            return ['status' => false, 'nome' => 'erro_upload'];
        }
        
        // Se houver imagem antiga, deleta ela
        if ($imagem_antiga) {
            $caminho_antigo = '../INICIO/imagens/' . $imagem_antiga;
            if (file_exists($caminho_antigo)) {
                unlink($caminho_antigo);
            }
        }
        
        return ['status' => true, 'nome' => $imagem_nome];
    }
    
    // Retorna a imagem antiga se não houver novo upload
    return ['status' => false, 'nome' => $imagem_antiga];
}

// =================================================================
// 2. PROCESSAMENTO DE AÇÕES VIA POST (Adicionar, Editar Produto, Editar Status Pedido)
// =================================================================
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $acao = isset($_POST['acao']) ? $_POST['acao'] : '';

    // Coleta de dados comum para Produtos
    if (in_array($acao, ['adicionar_produto', 'editar_produto'])) {
        $nome = $conn->real_escape_string(trim($_POST['nome']));
        $descricao = $conn->real_escape_string(trim($_POST['descricao']));
        $categoria = $conn->real_escape_string(trim($_POST['categoria']));
        $preco = floatval($_POST['preco']);
        $estoque = intval($_POST['estoque']);
        $produto_id = isset($_POST['id']) ? intval($_POST['id']) : 0; 
        $destaque = isset($_POST['destaque']) ? 1 : 0;
    }

    // AÇÃO 2.1: ADICIONAR NOVO PRODUTO (CREATE)
    if ($acao === 'adicionar_produto') {
        
        $upload_resultado = handleUpload($_FILES['imagem']);
        
        if (!$upload_resultado['status']) {
             header("Location: adm.php?secao=produtos&acao=adicionar&status=erro");
             exit;
        }
        $imagem_nome = $upload_resultado['nome'];

        $sql_inserir = "INSERT INTO produtos (nome, descricao, preco, categoria, estoque, imagem_url, data_cadastro, destaque) 
            VALUES ('$nome', '$descricao', '$preco', '$categoria', '$estoque', '$imagem_nome', NOW(), '$destaque')";
        if ($conn->query($sql_inserir) === TRUE) {
            header("Location: adm.php?secao=produtos&status=sucesso");
            exit;
        } else {
            unlink('../INICIO/imagens/' . $imagem_nome); 
            header("Location: adm.php?secao=produtos&acao=adicionar&status=erro");
            exit;
        }
    }
    
    // AÇÃO 2.2: EDITAR PRODUTO (UPDATE)
    if ($acao === 'editar_produto') {
        
        $imagem_antiga = $conn->real_escape_string($_POST['imagem_antiga']);
        $imagem_nome = $imagem_antiga;

        if ($_FILES['nova_imagem']['error'] === UPLOAD_ERR_OK) {
            $upload_resultado = handleUpload($_FILES['nova_imagem'], $imagem_antiga);
            if ($upload_resultado['status']) {
                $imagem_nome = $upload_resultado['nome'];
            } else {
                 header("Location: adm.php?secao=produtos&acao=editar&id={$produto_id}&status=erro");
                 exit;
            }
        }

                $sql_editar = "UPDATE produtos SET 
                      nome = '$nome', 
                      descricao = '$descricao', 
                      categoria = '$categoria', 
                      preco = '$preco', 
                      estoque = '$estoque', 
                      imagem_url = '$imagem_nome',
                      destaque = '$destaque' 
                      WHERE id = $produto_id";

        if ($conn->query($sql_editar) === TRUE) {
            header("Location: adm.php?secao=produtos&status=sucesso");
            exit;
        } else {
            header("Location: adm.php?secao=produtos&acao=editar&id={$produto_id}&status=erro");
            exit;
        }
    }
    
    // AÇÃO 2.3: EDITAR STATUS DO PEDIDO (NOVO)
    if ($acao === 'editar_status_pedido') {
        
        $pedido_id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        $novo_status = $conn->real_escape_string(trim($_POST['status']));

        if ($pedido_id > 0) {
            
            $sql_update = "UPDATE pedidos SET status = '$novo_status' WHERE id = $pedido_id";
            
            if ($conn->query($sql_update) === TRUE) {
                header("Location: adm.php?secao=pedidos&status=sucesso");
                exit;
            } else {
                header("Location: adm.php?secao=pedidos&status=erro");
                exit;
            }
        }
        
        header("Location: adm.php?secao=pedidos&status=erro");
        exit;
    }


// =================================================================
// 3. PROCESSAMENTO DE AÇÕES VIA GET (Apagar Produto e Cliente)
// =================================================================
} else if ($_SERVER["REQUEST_METHOD"] == "GET") {
    
    $acao = isset($_GET['acao']) ? $_GET['acao'] : '';
    
    // AÇÃO 3.1: APAGAR PRODUTO (DELETE)
    if ($acao === 'apagar_produto') {
        
        $produto_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        
        if ($produto_id > 0) {
            $sql_buscar_img = "SELECT imagem_url FROM produtos WHERE id = $produto_id";
            $resultado_img = $conn->query($sql_buscar_img);
            
            if ($resultado_img->num_rows > 0) {
                $produto = $resultado_img->fetch_assoc();
                $imagem_url = $produto['imagem_url'];
                
                $sql_deletar = "DELETE FROM produtos WHERE id = $produto_id";
                
                if ($conn->query($sql_deletar) === TRUE) {
                    $caminho_img = '../INICIO/imagens/' . $imagem_url;
                    if (file_exists($caminho_img)) {
                        unlink($caminho_img);
                    }
                    header("Location: adm.php?secao=produtos&status=sucesso");
                    exit;
                }
            }
        }
        
        header("Location: adm.php?secao=produtos&status=erro");
        exit;
    }
    
    // AÇÃO 3.2: APAGAR CLIENTE (DELETE)
    if ($acao === 'apagar_cliente') {
        
        $cliente_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        
        if ($cliente_id > 0) {
            
            $sql_perfil = "SELECT perfil FROM usuarios WHERE id = $cliente_id";
            $resultado_perfil = $conn->query($sql_perfil);
            
            if ($resultado_perfil->num_rows > 0) {
                $usuario = $resultado_perfil->fetch_assoc();

                // Só apaga se for realmente um 'cliente'
                if ($usuario['perfil'] === 'cliente') {
                    
                    $sql_deletar = "DELETE FROM usuarios WHERE id = $cliente_id";
                    
                    if ($conn->query($sql_deletar) === TRUE) {
                        header("Location: adm.php?secao=clientes&status=sucesso");
                        exit;
                    }
                }
            }
        }
        
        header("Location: adm.php?secao=clientes&status=erro");
        exit;
    }
}


$conn->close();
// Redireciona para o painel se a requisição não for reconhecida
header("Location: adm.php");
exit;
?>