<p align="center">
  <img width="30%" src="logo eric hiroshi.png" alt="Backend Brasil Logo">
</p>

<p align="center">
  <img src="https://img.shields.io/badge/PHP-8.1-777BB4?style=for-the-badge&logo=php&logoColor=white" />
  <img src="https://img.shields.io/badge/Composer-2.x-885630?style=for-the-badge&logo=composer&logoColor=white" />
  <img src="https://img.shields.io/badge/FakerPHP-1.x-FF6F00?style=for-the-badge&logo=php&logoColor=white" />
  <img src="https://img.shields.io/badge/Apache-2.4-D22128?style=for-the-badge&logo=apache&logoColor=white" />
  <img src="https://img.shields.io/badge/MySQL-latest-4479A1?style=for-the-badge&logo=mysql&logoColor=white" />
  <img src="https://img.shields.io/badge/Docker-Compose-2496ED?style=for-the-badge&logo=docker&logoColor=white" />
  <img src="https://img.shields.io/badge/Nginx-LoadBalancer-009639?style=for-the-badge&logo=nginx&logoColor=white" />
</p>

<h3 align="center">Desafio DIO - Accenture - Desenvolvimento Java & Cloud
Projeto Toshiro Shibakita – Versão Atualizada (Docker + PHP + MySQL + NGINX)
</h3>

Este repositório é um **fork aprimorado** do projeto original publicado por Denilson Bonatti, com o objetivo de modernizar a estrutura, padronizar o ambiente de execução via Docker, melhorar a organização das pastas, adicionar logs estruturados e tornar o projeto mais robusto para fins de estudo ou demonstração. Desenvolvido durante o bootcamp Accenture - Desenvolvimento Java & Cloud em parceria com a DIO.

Repositório original:  
https://github.com/denilsonbonatti/toshiro-shibakita

Repositório atualizado (este):  
https://github.com/erichiroshi/toshiro-shibakita-dio

---

## 1. Objetivo

Criar um ambiente totalmente automatizado com **Docker** para rodar uma aplicação PHP que simula:

- Inserção automática de registros falsos usando **Faker**
- Persistência em banco MySQL
- Logs estruturados em JSON
- Exibição de relatórios organizados
- Servidor NGINX servindo o front-end PHP
- Build isolado da aplicação com Composer na etapa de imagem

O projeto final funciona como um pequeno pipeline de dados demonstrando:
1. Aplicação PHP geradora de dados  
2. Banco MySQL armazenando registros  
3. Página de relatório exibindo os dados ordenados  
4. Logs estruturados registrando cada requisição  

---

## 2. O que foi modificado em relação ao projeto original

O fork recebeu uma série de melhorias técnicas para torná-lo mais moderno e profissional.

### 2.1 Dockerização completa
- Criação de um **Dockerfile** otimizado usando multi-stage build.
- Adição do **docker-compose.yml** com três serviços:
  - `app` (PHP + Composer)
  - `nginx` (servidor web)
  - `mysql` (banco de dados)
- Volumes organizados e mapeamentos seguros.
- Suporte a múltiplas instâncias do serviço PHP (`app`) com load balancing via Nginx.

### 2.2 Estrutura de pastas reorganizada
- `app/` isolado contendo toda a aplicação PHP.
- `db/` contendo scripts SQL para inicialização automática.
- Separação limpa entre código-fonte, configuração e infraestrutura.

### 2.3 Composer e dependências
- Adicionado suporte ao **Composer** (multi-stage image).
- Inclusão da biblioteca **Faker** para geração realista de dados.

### 2.4 Código PHP refatorado
- Função profissional de log (`registrar_log`).
- Logs estruturados em JSON contendo:
  - timestamp
  - host
  - remote IP
  - mensagem
  - payload de dados
- Melhor tratamento de erros.
- Geração de dados aprimorada.
- Leitura de variáveis de ambiente.

### 2.5 Relatórios
- Página de relatório mais organizada.
- Ordenação correta via coluna `created_at`.

### 2.6 Scripts SQL
- Criação automática da tabela `dados` via `db/banco.sql`.
- Ajustada estrutura da tabela.

### 2.7 `.gitignore` profissional
- Ignora vendors
- Ignora volumes
- Ignora artefatos de build
- Mantém somente arquivos necessários no repositório

---

## 3. Arquitetura

```
├── app/                # Código PHP da aplicação
│ ├── index.php
│ ├──  Dockerfile
│ ├── relatorios/       # Scripts de relatório
│ │ └── registros.php
│ │ └── vendor/         # Dependências do composer
│ └── composer.json
│
├── db/
│ └── banco.sql         # Script de criação da tabela dados
│
├── nginx/
│ └── nginx.conf
│
├── docker-compose.yml
└── README.md

```

---

## 4. Como executar o projeto

### Pré-requisitos
- Docker 20+
- Docker Compose 2+

### Clonar repositório

```bash
git clone https://github.com/erichiroshi/toshiro-shibakita-dio
```

### Subir containers

```bash
docker compose up -d --build
```

Cria:
- 1 container do MySQL
- 1 container do PHPMyAdmin
- 1 container do app PHP
- 1 container do Nginx

### Subir múltiplas instâncias do app
Após o build inicial, é possível escalar o serviço app para múltiplas instâncias:
```bash
docker compose up --scale app=3 -d
```
Isso criará 3 containers independentes do app:

- app_1
- app_2
- app_3

O Nginx faz load balancing entre eles automaticamente.

### Acessar a aplicação
- Página principal (gera registros):  
  http://localhost:4500/

- Relatório de registros:  
  http://localhost:4500/relatorios/relatorios.php

- Verificar banco de dados - via PHPMyAdmin:  
  http://localhost:8081/

### Acessar o banco via CLI

```bash
docker exec -it mysql-container-dio mysql -u root -p
```

---

## 5. Funcionamento

### 5.1 Inserção automática
A página principal (`index.php`) usa Faker para gerar:

- Nome  
- Sobrenome  
- Endereço  
- Cidade  
- ID Aleatório  

E grava na tabela:

```bash
dados (AlunoID, Nome, Sobrenome, Endereco, Cidade, Host, created_at)
```

### 5.2 Logs
Todos os logs de inserção de dados são armazenados em:

```
/var/www/html/app.log
```

Cada inserção gera um log JSON em:

Exemplo:

```json
{
  "timestamp": "2025-02-10 14:22:11",
  "host": "app-container",
  "remote_ip": "172.20.0.1",
  "mensagem": "Dado criado com sucesso",
  "dados": {
    "nome": "Carlos",
    "sobrenome": "Silva",
    "cidade": "Curitiba",
    "host": "app-container"
  }
}
```

### 5.3 Relatório

A página relatorios/relatorios.php exibe os dados na ordem de sua inserção no banco:

ORDER BY created_at ASC

### 6. Como recriar o banco (reset)

Se o arquivo banco.sql não rodar automaticamente, o motivo é sempre o mesmo:
O volume já existia antes.

Para limpar tudo:

```bash
docker compose down -v  
docker compose up -d --build
```

### 7. Tecnologias Utilizadas

- PHP 8.x
- NGINX
- MySQL 8
- Docker & Docker Compose
- Faker (pt_BR)
- Logs JSON
 -Multi-stage Docker Build

## 8 Contribuições

Contribuições são sempre bem-vindas!  
Para contribuir:

1. Crie um fork do repositório.  
2. Crie uma branch de feature:  
   ```bash
   git checkout -b feature/nova-funcionalidade
   ```
3. Commit suas mudanças:  
   ```bash
   git commit -m "feat: nova funcionalidade"
   ```
4. Envie um Pull Request.  

📜 **Boas práticas**
- Adicione testes unitários.  
- Documente suas alterações no código.  
- Use mensagens de commit seguindo o padrão **Conventional Commits**.

---

### 9. Sobre o Fork

Este fork moderniza e profissionaliza o projeto original, trazendo práticas atuais de desenvolvimento, organização e infraestrutura.

---

## 🔗 Referências e Créditos

- Desafio original: [digitalinnovationone - DIO](https://github.com/denilsonbonatti/toshiro-shibakita)
- Desenvolvido por [**Eric Hiroshi**](https://github.com/erichiroshi)
- Licença: [MIT](LICENSE)

---

<p align="center">
  <em>“Código limpo é aquele que expressa a intenção com simplicidade e precisão.”</em>
</p>