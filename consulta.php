<?php
function consultarCEP($cep) {
    // Remove caracteres não numéricos
    $cep = preg_replace('/[^0-9]/', '', $cep);
    
    // Verifica se tem 8 dígitos
    if (strlen($cep) != 8) {
        return false;
    }
    
    // Faz a requisição ao ViaCEP
    $url = "https://viacep.com.br/ws/{$cep}/xml/";
    
    // Inicializa o cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    // Verifica se houve resposta
    if (!$response) {
        return false;
    }
    
    // Converte o XML para objeto
    $xml = simplexml_load_string($response);
    
    // Verifica se não há erro
    if (isset($xml->erro)) {
        return false;
    }
    
    return $xml;
}

// Processa a consulta
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cep'])) {
    $resultado = consultarCEP($_POST['cep']);
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultado da Consulta - Enlatados Juarez</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; }
        .result { margin-top: 20px; padding: 15px; background-color: #f8f9fa; border-radius: 5px; }
        .back-btn { display: inline-block; margin-top: 15px; padding: 8px 15px; background-color: #2c3e50; color: white; text-decoration: none; }
        .error { color: #e74c3c; }
    </style>
</head>
<body>
    <h2>Resultado da Consulta</h2>
    
    <?php if (isset($resultado) && $resultado !== false): ?>
        <div class="result">
            <p><strong>CEP:</strong> <?php echo $resultado->cep; ?></p>
            <p><strong>Logradouro:</strong> <?php echo $resultado->logradouro; ?></p>
            <p><strong>Complemento:</strong> <?php echo $resultado->complemento; ?></p>
            <p><strong>Bairro:</strong> <?php echo $resultado->bairro; ?></p>
            <p><strong>Cidade:</strong> <?php echo $resultado->localidade; ?></p>
            <p><strong>Estado:</strong> <?php echo $resultado->uf; ?></p>
        </div>
    <?php else: ?>
        <div class="result error">
            <p>CEP não encontrado ou formato inválido. Por favor, verifique e tente novamente.</p>
        </div>
    <?php endif; ?>
    
    <a href="index.php" class="back-btn">Nova Consulta</a>
</body>
</html>