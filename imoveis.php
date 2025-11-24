<?php
require_once "conexao.php";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $tipo_imovel = $_POST["tipo_imovel"];
    $finalidade = $_POST["finalidade"];
    $localizacao = $_POST["localizacao"];
    $area_construida = $_POST["area_construida"];
    $area_terreno = $_POST["area_terreno"];
    $quartos = $_POST["quartos"];
    $banheiros = $_POST["banheiros"];
    $vagas_garagem = $_POST["vagas_garagem"];
    $descricao = $_POST["descricao"];
    $cliente_id = $_POST["cliente_id"];

    if(!empty($tipo_imovel) && !empty($finalidade) && !empty($localizacao)){
        $sql = "INSERT INTO imoveis (tipo_imovel, finalidade, localizacao, area_construida, area_terreno, quartos, banheiros, vagas_garagem, descricao, cliente_id) 
                VALUES (:tipo_imovel, :finalidade, :localizacao, :area_construida, :area_terreno, :quartos, :banheiros, :vagas_garagem, :descricao, :cliente_id)";

        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(":tipo_imovel", $tipo_imovel);
        $stmt->bindParam(":finalidade", $finalidade);
        $stmt->bindParam(":localizacao", $localizacao);
        $stmt->bindParam(":area_construida", $area_construida);
        $stmt->bindParam(":area_terreno", $area_terreno);
        $stmt->bindParam(":quartos", $quartos);
        $stmt->bindParam(":banheiros", $banheiros);
        $stmt->bindParam(":vagas_garagem", $vagas_garagem);
        $stmt->bindParam(":descricao", $descricao);
        $stmt->bindParam(":cliente_id", $cliente_id);

        if ($stmt->execute()){
            $mensagem = "Imóvel cadastrado com sucesso!";
        } else {
            $mensagem = "Erro ao cadastrar imóvel.";
        }
    } else {
        $mensagem = "Os campos Tipo, Finalidade e Localização são obrigatórios!";
    }
}

// Buscar imóveis
$sql_imoveis = "SELECT i.*, c.nome as cliente_nome 
                FROM imoveis i 
                LEFT JOIN clientes c ON i.cliente_id = c.id 
                ORDER BY i.id DESC";
$stmt_imoveis = $pdo->query($sql_imoveis);
$imoveis = $stmt_imoveis->fetchAll();

// Buscar clientes para o select
$sql_clientes = "SELECT id, nome FROM clientes ORDER BY nome";
$stmt_clientes = $pdo->query($sql_clientes);
$clientes = $stmt_clientes->fetchAll();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Imóveis</title>
    <link rel="stylesheet" href="style.css">
    
</head>
<body>
   <div class="form-container">
        <h2>Cadastro de Imóvel</h2>
        <a href="http://localhost/breno/POO/banco-imobiliaria/cadastro.php"><h2>Cadastro de Clientes</h2></a>

        <button onclick=""> Voltar </button>

        <form method="POST">
            <label>Tipo de Imóvel:</label>
            <select name="tipo_imovel" required>
                <option value="">Selecione...</option>
                <option value="Casa">Casa</option>
                <option value="Apartamento">Apartamento</option>
                <option value="Terreno">Terreno</option>
                <option value="Kitnet">Kitnet</option>
                <option value="Sobrado">Sobrado</option>
                <option value="Chácara">Chácara</option>
                <option value="Fazenda">Fazenda</option>
                <option value="Comercial">Comercial</option>
            </select>

            <label>Finalidade:</label>
            <select name="finalidade" required>
                <option value="">Selecione...</option>
                <option value="Venda">Venda</option>
                <option value="Aluguel">Aluguel</option>
            </select>

            <label>Localização (Endereço):</label>
            <input type="text" name="localizacao" required>

            <label>Área Construída (m²):</label>
            <input type="number" step="0.01" name="area_construida">

            <label>Área do Terreno (m²):</label>
            <input type="number" step="0.01" name="area_terreno">

            <label>Quartos:</label>
            <input type="number" name="quartos" value="0" min="0">

            <label>Banheiros:</label>
            <input type="number" name="banheiros" value="0" min="0">

            <label>Vagas de Garagem:</label>
            <input type="number" name="vagas_garagem" value="0" min="0">

            <label>Descrição:</label>
            <textarea name="descricao" rows="4"></textarea>

            <label>Proprietário (Cliente):</label>
            <select name="cliente_id">
                <option value="">Selecione o proprietário...</option>
                <?php foreach ($clientes as $cliente) { ?>
                    <option value="<?= $cliente['id'] ?>"><?= htmlspecialchars($cliente['nome']) ?></option>
                <?php } ?>
            </select>

            <button type="submit">Cadastrar Imóvel</button>
        </form>

        <?php if (isset($mensagem)) { ?>
            <div class="mensagem <?= strpos($mensagem, 'sucesso') !== false ? 'success' : 'error' ?>">
                <?= $mensagem ?>
            </div>
        <?php } ?>
    </div>

    <?php if (!empty($imoveis)) { ?>
        <div class="tabela-container">
            <h3>Imóveis Cadastrados</h3>

            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tipo</th>
                        <th>Finalidade</th>
                        <th>Localização</th>
                        <th>Área Const.</th>
                        <th>Quartos</th>
                        <th>Banheiros</th>
                        <th>Garagem</th>
                        <th>Proprietário</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($imoveis as $imovel) { ?>
                        <tr>
                            <td><?= htmlspecialchars($imovel['id']) ?></td>
                            <td><?= htmlspecialchars($imovel['tipo_imovel']) ?></td>
                            <td><?= htmlspecialchars($imovel['finalidade']) ?></td>
                            <td><?= htmlspecialchars($imovel['localizacao']) ?></td>
                            <td><?= htmlspecialchars($imovel['area_construida'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($imovel['quartos']) ?></td>
                            <td><?= htmlspecialchars($imovel['banheiros']) ?></td>
                            <td><?= htmlspecialchars($imovel['vagas_garagem']) ?></td>
                            <td><?= htmlspecialchars($imovel['cliente_nome'] ?? 'Não informado') ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    <?php } ?>
    
</body>
</html>