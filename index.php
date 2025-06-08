<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta de CEP - Enlatados Juarez</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; }
        .form-group { margin-bottom: 15px; }
        input, button { padding: 8px; width: 100%; box-sizing: border-box; }
        button { background-color: #2c3e50; color: white; border: none; cursor: pointer; }
        .result { margin-top: 20px; padding: 15px; background-color: #f8f9fa; border-radius: 5px; }
    </style>
</head>
<body>
    <h2>Consulta de CEP</h2>
    <form action="consulta.php" method="post">
        <div class="form-group">
            <label for="cep">CEP:</label>
            <input type="text" id="cep" name="cep" placeholder="Digite o CEP (apenas números)" required>
        </div>
        <button type="submit">Consultar</button>
    </form>
</body>
</html>