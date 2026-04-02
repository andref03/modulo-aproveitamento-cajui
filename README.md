# Módulo de Aproveitamento de Estudos – Protótipo (Yii2 + PostgreSQL)

##  Detalhes e Como Executar

### Descrição
Este projeto consiste em um protótipo funcional de um módulo para gerenciamento de solicitações de Aproveitamento de Estudos, conforme cenário proposto no teste prático do IFNMG.

O sistema permite:
- Cadastro de solicitações de aproveitamento vinculadas a um estudante;
- Inclusão de múltiplos itens de equivalência em uma mesma solicitação;
- Edição da solicitação enquanto estiver em rascunho;
- Envio da solicitação para análise;
- Análise individual de cada item por coordenador;
- Registro de parecer e justificativa;
- Finalização da solicitação com resultado consolidado.

### Tecnologias utilizadas
- PHP
- Yii2
- PostgreSQL
- Bootstrap (interface padrão do Yii2)
- JavaScript / jQuery (itens dinâmicos e persistência parcial)

### Estrutura do domínio
O sistema foi modelado com base nas seguintes entidades principais:
- Estudante
- Coordenador
- Curso
- Disciplina
- Solicitação de Aproveitamento
- Item de Equivalência
- Log de Ações

### Regras de negócio implementadas
- Não permitir envio de solicitação sem itens;
- Não permitir finalização da solicitação com itens sem parecer;
- Não permitir deferimento quando a carga horária da disciplina de origem for inferior a 75% da disciplina de destino;
- Cada item é analisado individualmente;
- Solicitações finalizadas não podem ser editadas.

### Estados da solicitação
- Em edição
- Enviada
- Em análise
- Finalizada

# Como executar

### 1. Clonar o projeto
```bash
git clone git@github.com:andref03/modulo-aproveitamento-cajui.git
cd modulo-aproveitamento-cajui
```

### 2. Instalar dependências
```bash
composer install
```

### 3. Configurar banco

Criar banco PostgreSQL e ajustar credenciais no arquivo:
```php
config/db.php
```

Exemplo:

```php
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'pgsql:host=localhost;port=5432;dbname=aproveitamento_estudos',
    'username' => 'postgres',
    'password' => 'sua_senha',
    'charset' => 'utf8',
];
```

### 4. Criar estrutura do banco

Executar o script:
```bash
psql -U postgres -d aproveitamento_estudos -f banco/schema.sql
```

### 5. Popular dados de teste
```bash
psql -U postgres -d aproveitamento_estudos -f banco/dados_teste.sql
```

### 6. Executar o projeto
```bash
php yii serve
```

Acessar:
```bash
http://localhost:8080
```