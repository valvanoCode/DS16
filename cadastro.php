<?php
session_start();

function consultarCEP($cep) {
    $cep = preg_replace('/[^0-9]/', '', $cep);
    if (strlen($cep) != 8) return false;
    
    $url = "https://viacep.com.br/ws/{$cep}/xml/";
    $xml = @simplexml_load_file($url);
    
    if ($xml === false || isset($xml->erro)) return false;
    
    return $xml;
}

// Processar busca de CEP
if (isset($_POST['btnBuscarCEP'])) {
    $_SESSION['form_data'] = $_POST;
    
    $resultado = consultarCEP($_POST['cep']);
    if ($resultado) {
        $_SESSION['form_data']['logradouro'] = (string)$resultado->logradouro;
        $_SESSION['form_data']['bairro'] = (string)$resultado->bairro;
        $_SESSION['form_data']['cidade'] = (string)$resultado->localidade;
        $_SESSION['form_data']['uf'] = (string)$resultado->uf;
    } else {
        $_SESSION['error'] = "CEP não encontrado!";
    }
    
    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}

// Processar cadastro
if (isset($_POST['btnCadastrar'])) {
    // Aqui viria a lógica para salvar no banco de dados
    $dados = $_POST;
    
    // Limpar sessão após cadastro
    unset($_SESSION['form_data']);
    unset($_SESSION['error']);
    
    $_SESSION['success'] = "Cadastro realizado com sucesso!";
    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}

// Inicializar dados do formulário
if (!isset($_SESSION['form_data'])) {
    $_SESSION['form_data'] = [
        'nome' => '',
        'cep' => '',
        'logradouro' => '',
        'numero' => '',
        'complemento' => '',
        'bairro' => '',
        'cidade' => '',
        'uf' => '',
        'email' => '',
        'telefone' => ''
    ];
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Clientes - Enlatados Juarez</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .form-section { margin-bottom: 25px; }
        .required-field::after { content: " *"; color: red; }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Cadastro de Clientes</h2>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>
        
        <form method="post">
            <div class="form-section">
                <h4>Dados Pessoais</h4>
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="nome" class="form-label required-field">Nome Completo</label>
                        <input type="text" class="form-control" id="nome" name="nome" 
                               value="<?php echo htmlspecialchars($_SESSION['form_data']['nome']); ?>" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">E-mail</label>
                        <input type="email" class="form-control" id="email" name="email" 
                               value="<?php echo htmlspecialchars($_SESSION['form_data']['email']); ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="telefone" class="form-label">Telefone</label>
                        <input type="tel" class="form-control" id="telefone" name="telefone" 
                               value="<?php echo htmlspecialchars($_SESSION['form_data']['telefone']); ?>">
                    </div>
                </div>
            </div>
            
            <div class="form-section">
                <h4>Endereço</h4>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="cep" class="form-label required-field">CEP</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="cep" name="cep" 
                                   value="<?php echo htmlspecialchars($_SESSION['form_data']['cep']); ?>" required>
                            <button class="btn btn-primary" type="submit" name="btnBuscarCEP">Buscar</button>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8 mb-3">
                        <label for="logradouro" class="form-label required-field">Logradouro</label>
                        <input type="text" class="form-control" id="logradouro" name="logradouro" 
                               value="<?php echo htmlspecialchars($_SESSION['form_data']['logradouro']); ?>" required>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="numero" class="form-label required-field">Número</label>
                        <input type="text" class="form-control" id="numero" name="numero" 
                               value="<?php echo htmlspecialchars($_SESSION['form_data']['numero']); ?>" required>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="complemento" class="form-label">Complemento</label>
                        <input type="text" class="form-control" id="complemento" name="complemento" 
                               value="<?php echo htmlspecialchars($_SESSION['form_data']['complemento']); ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="bairro" class="form-label required-field">Bairro</label>
                        <input type="text" class="form-control" id="bairro" name="bairro" 
                               value="<?php echo htmlspecialchars($_SESSION['form_data']['bairro']); ?>" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="cidade" class="form-label required-field">Cidade</label>
                        <input type="text" class="form-control" id="cidade" name="cidade" 
                               value="<?php echo htmlspecialchars($_SESSION['form_data']['cidade']); ?>" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="uf" class="form-label required-field">Estado</label>
                        <input type="text" class="form-control" id="uf" name="uf" 
                               value="<?php echo htmlspecialchars($_SESSION['form_data']['uf']); ?>" required maxlength="2">
                    </div>
                </div>
            </div>
            
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <button type="submit" class="btn btn-primary" name="btnCadastrar">Cadastrar</button>
            </div>
        </form>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>