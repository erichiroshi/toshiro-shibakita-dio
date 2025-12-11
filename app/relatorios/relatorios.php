<?php
ini_set("display_errors", 1);
header('Content-Type: text/html; charset=utf-8');

$servername = "mysql";
$username = getenv("MYSQL_USER") ?: "root";
$password = getenv("MYSQL_PASSWORD") ?: "Senha123";
$database = getenv("MYSQL_DATABASE") ?: "meubanco";

$link = new mysqli($servername, $username, $password, $database);

if ($link->connect_error) {
    die("Erro de conexão: " . $link->connect_error);
}

$query = $link->prepare("SELECT * FROM dados ORDER BY created_at ASC;");
$query->execute();
$result = $query->get_result();
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Relatório de Registros</title>

    <!-- Bootstrap 5 -->
    <link 
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" 
        rel="stylesheet"
    >

    <style>
        body {
            background-color: #f4f6f9;
        }
        .container {
            margin-top: 40px;
        }
        .table thead {
            background-color: #343a40;
            color: white;
        }
        .card {
            border-radius: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="card shadow p-4">
            <h2 class="mb-4 text-center">Registros no Banco de Dados</h2>

            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Sobrenome</th>
                        <th>Endereço</th>
                        <th>Cidade</th>
                        <th>Host</th>
                    </tr>
                </thead>

                <tbody>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><?= htmlspecialchars($row["AlunoID"]) ?></td>
                            <td><?= htmlspecialchars($row["Nome"]) ?></td>
                            <td><?= htmlspecialchars($row["Sobrenome"]) ?></td>
                            <td><?= htmlspecialchars($row["Endereco"]) ?></td>
                            <td><?= htmlspecialchars($row["Cidade"]) ?></td>
                            <td><?= htmlspecialchars($row["Host"]) ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Bootstrap JS (opcional) -->
    <script 
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js">
    </script>
</body>

</html>
