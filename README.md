# Módulo de Aproveitamento de Estudos – Protótipo (Yii2 + PostgreSQL)

> Este projeto pode ser acessado neste [repositório](https://github.com/andref03/modulo-aproveitamento-cajui/tree/login-perfis).

## Sobre o projeto
Protótipo funcional do módulo de Aproveitamento de Estudos no Sistema Cajuí, conforme estudo de caso técnico.

O sistema contempla:
- criação de solicitações por estudante;
- inclusão de múltiplos itens de equivalência por solicitação;
- envio para análise;
- análise item a item por coordenador;
- finalização com resultado consolidado;
- rastreabilidade mínima por log de ações.

## Onde estão os arquivos pedidos no edital
- Diagrama relacional elaborado: `banco/DER_aproveitamento_estudos.png`
- Arquivo de criação do banco: `banco/schema.sql`
- Arquivo de dump/dados de teste: `banco/dados_teste.sql`
- Arquivo de vídeo demonstrativo: `video/link-video.txt`

## Regras de negócio implementadas (resumo)
- não permite enviar solicitação sem itens;
- não permite finalizar com itens pendentes;
- não permite deferir equivalência quando a carga horária da origem for inferior à da disciplina de destino;
- não permite deferir disciplina de destino que possui pré-requisito;
- após finalização, a solicitação não é mais editável.

## Pré-requisitos
- PHP 8.1+ (recomendado 8.2/8.3)
- Composer 2+
- PostgreSQL 12+
- Extensões PHP comuns do Yii2 (`pdo`, `pdo_pgsql`, `mbstring`, `intl`, `json`, etc.)

## Como executar (do zero, só com estes arquivos)
### 1. Receber e descompactar os arquivos
Descompacte o projeto em uma pasta local, por exemplo:
- Linux: `/home/usuario/modulo-aproveitamento-cajui`
- Windows: `C:\projetos\modulo-aproveitamento-cajui`

### 2. Entrar na pasta do projeto
```bash
cd modulo-aproveitamento-cajui
```

### 3. Instalar dependências PHP
Se a pasta `vendor/` não estiver presente, execute:
```bash
composer install
```

### 4. Criar banco e importar dados
Para evitar erros de permissão ou senha, siga estas recomendações:

- Crie o banco usando seu usuário PostgreSQL local, por exemplo via `psql` ou algum gerenciador gráfico como pgAdmin ou DBeaver.
- Nome sugerido: `aproveitamento_estudos`
- Configure a conexão no Yii2 em `config/db.php` com host, porta, banco, usuário e senha:

```php
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'pgsql:host=localhost;port=5432;dbname=aproveitamento_estudos',
    'username' => 'seu_usuario',
    'password' => 'sua_senha',
    'charset' => 'utf8',
];
```

- Importe os arquivos SQL em qualquer gerenciador de banco ou via `psql` com o usuário correto, garantindo que ele tenha permissão de leitura dos arquivos:
- Estrutura do banco: `banco/schema.sql`
- Dados de teste: `banco/dados_teste.sql`

### 5. Subir a aplicação
```bash
php yii serve --port=8080
```

### 6. Acessar no navegador
- `http://localhost:8080`

## Usuários de teste
- Admin: `admin@cajui.com` / `admin123`
- Aluno: `andre.felipe@ifnmg.edu.br` / `aluno123`
- Coordenadora: `mariana.souza@ifnmg.edu.br` / `coord123`

## Estrutura principal do domínio
- Curso
- Coordenador
- Estudante
- Disciplina IFNMG
- Solicitação de Aproveitamento
- Item de Equivalência
- Log de Ação
