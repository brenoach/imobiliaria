<?php
require_once "conexao.php";


if($_SERVER["REQUEST_METHOD"]== "POST"){
    $nome=$_POST["nome"];
    $cpf=$_POST["cpf"];
    $endereco=$_POST["endereco"];
    $bairro=$_POST["bairro"];
    $cidade=$_POST["cidade"];

    if(!empty($nome)){
        $sql = "INSERT INTO clientes(nome,cpf,endereco,bairro,cidade) VALUES (:nome, :cpf, :endereco, :bairro, :cidade)";

        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(":nome",$nome);
        $stmt->bindParam(":cpf", $cpf);
        $stmt->bindParam(":endereco", $endereco);
        $stmt->bindParam(":bairro", $bairro);
        $stmt->bindParam(":cidade", $cidade);

    if ($stmt->execute()){
        $mensagem = "Cliente cadastrado com sucesso";
    } else {
        $mensagem = "Erro ao cadastrar cliente.";
    }
    } else {
        $mensagem = "O campo nome é obrigatório!";
    }
}

$sql = "SELECT * FROM clientes ORDER BY id DESC";
$stmt = $pdo->query($sql);
$clientes = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro Clientes</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
   <div class="form-container">

        <h2>Cadastro de Cliente</h2>
        <a href="http://localhost/breno/POO/banco-imobiliaria/imoveis.php"><h2>Cadastro de Imóvel</h2></a>

        <form method="POST">
            <label>Nome:</label>
            <input type="text" name="nome" required>

            <label>CPF:</label>
            <input type="text" name="cpf">

            <label>Endereço:</label>
            <input type="text" name="endereco">

            <label>Bairro:</label>
            <input type="text" name="bairro">

            <label>Cidade:</label>
            <input type="text" name="cidade">

            <button type="submit">Cadastrar</button>
        </form>

        <?php if (isset($mensagem)) { ?>
            <div class="mensagem"><?= $mensagem ?></div>
        <?php } ?>

    </div>

    <?php if (!empty($clientes)) { ?>
        <div class="tabela-container">
            <h3>Clientes Cadastrados</h3>

            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Endereço</th>
                        <th>Bairro</th>
                        <th>Cidade</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($clientes as $cliente) { ?>
                        <tr>
                            <td><?= htmlspecialchars($cliente['id']) ?></td>
                            <td><?= htmlspecialchars($cliente['nome']) ?></td>
                            <td><?= htmlspecialchars($cliente['endereco']) ?></td>
                            <td><?= htmlspecialchars($cliente['bairro']) ?></td>
                            <td><?= htmlspecialchars($cliente['cidade']) ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    <?php } ?>
    
</body>
</html>