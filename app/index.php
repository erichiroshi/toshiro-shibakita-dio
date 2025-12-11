<?php
require_once 'vendor/autoload.php';

// Configurações iniciais
ini_set("display_errors", 1);
header('Content-Type: text/html; charset=utf-8');

// Exibe versão do PHP
echo 'Versao Atual do PHP: ' . phpversion() . '<br>';
echo "<h3>Instância do APP: <strong>" . getenv("HOSTNAME") . "</strong></h3><hr><br>";

// -----------------------------------------------------------------------------
// Configurações do Banco
// -----------------------------------------------------------------------------
$servername = "mysql";
$username = getenv("MYSQL_USER") ?: "root";
$password = getenv("MYSQL_PASSWORD") ?: "Senha123";
$database = getenv("MYSQL_DATABASE") ?: "meubanco";

// Conexão com o banco
$link = new mysqli($servername, $username, $password, $database);

if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

// -----------------------------------------------------------------------------
// Gerar dados fake com FAKER
// -----------------------------------------------------------------------------
$faker       = Faker\Factory::create('pt_BR'); // dados brasileiros realistas

$alunoID     = $faker->unique()->numberBetween(1, 1000000);
$nome        = $link->real_escape_string($faker->firstName);
$sobrenome   = $link->real_escape_string($faker->lastName);
$endereco    = $link->real_escape_string($faker->streetAddress);
$cidade      = $link->real_escape_string($faker->city);
$host_name   = $link->real_escape_string(gethostname());

$query = "INSERT INTO dados (AlunoID, Nome, Sobrenome, Endereco, Cidade, Host)
          VALUES ($alunoID, '$nome', '$sobrenome', '$endereco', '$cidade', '$host_name')";

if ($link->query($query) === TRUE) {

      registrar_log("Dado criado com sucesso", [
        "nome"      => $nome,
        "sobrenome" => $sobrenome,
        "cidade"    => $cidade,
        "host"      => $host_name
    ]);

    echo "Dado criado com sucesso";

} else {

     registrar_log("Erro ao inserir dado", [
        "erro" => $link->error,
        "query" => $query
    ]);

    echo "Error: " . $link->error;
}

$link->close();

// -----------------------------------------------------------------------------
// Função de Log
// -----------------------------------------------------------------------------
function registrar_log($mensagem, $dados = []) {
    $logfile = "/var/www/html/app.log";
    
    $registro = [
        "timestamp" => date("Y-m-d H:i:s"),
        "host"      => gethostname(),
        "remote_ip" => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
        "mensagem"  => $mensagem,
        "dados"     => $dados,
        ];
        
        // Converter para JSON
        $linha = json_encode($registro, JSON_UNESCAPED_UNICODE) . PHP_EOL;
        
        // Registrar no log
        error_log($linha, 3, $logfile);
    }
    
?>