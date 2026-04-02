-- =========================================================
-- SCHEMA.SQL
-- Sistema de Aproveitamento de Estudos
-- PostgreSQL
-- =========================================================

-- Limpeza opcional
DROP TABLE IF EXISTS log_acao CASCADE;
DROP TABLE IF EXISTS item_equivalencia CASCADE;
DROP TABLE IF EXISTS solicitacao_aproveitamento CASCADE;
DROP TABLE IF EXISTS disciplina_ifnmg CASCADE;
DROP TABLE IF EXISTS estudante CASCADE;
DROP TABLE IF EXISTS coordenador CASCADE;
DROP TABLE IF EXISTS curso CASCADE;

-- =========================================================
-- TABELA: curso
-- Um curso possui:
-- - vários estudantes
-- - um único coordenador
-- - várias disciplinas
-- =========================================================
CREATE TABLE curso (
    id SERIAL PRIMARY KEY,
    nome VARCHAR(150) NOT NULL,
    campus VARCHAR(100) NOT NULL
);

-- =========================================================
-- TABELA: coordenador
-- Um coordenador está vinculado a um único curso
-- Como cada curso tem um único coordenador,
-- curso_id é UNIQUE
-- =========================================================
CREATE TABLE coordenador (
    id SERIAL PRIMARY KEY,
    nome VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    curso_id INT NOT NULL UNIQUE,
    
    CONSTRAINT fk_coordenador_curso
        FOREIGN KEY (curso_id)
        REFERENCES curso(id)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
);

-- =========================================================
-- TABELA: estudante
-- Um estudante pertence a um curso
-- =========================================================
CREATE TABLE estudante (
    id SERIAL PRIMARY KEY,
    nome VARCHAR(150) NOT NULL,
    matricula VARCHAR(30) NOT NULL UNIQUE,
    email VARCHAR(150) NOT NULL UNIQUE,
    curso_id INT NOT NULL,
    
    CONSTRAINT fk_estudante_curso
        FOREIGN KEY (curso_id)
        REFERENCES curso(id)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
);

-- =========================================================
-- TABELA: disciplina_ifnmg
-- Disciplinas da matriz curricular do IFNMG
-- Pode haver pré-requisito (auto-relacionamento)
-- =========================================================
CREATE TABLE disciplina_ifnmg (
    id SERIAL PRIMARY KEY,
    codigo VARCHAR(30) NOT NULL UNIQUE,
    nome VARCHAR(150) NOT NULL,
    carga_horaria INT NOT NULL,
    ementa TEXT,
    curso_id INT NOT NULL,
    pre_requisito_id INT NULL,

    CONSTRAINT chk_disciplina_carga_horaria
        CHECK (carga_horaria > 0),

    CONSTRAINT fk_disciplina_curso
        FOREIGN KEY (curso_id)
        REFERENCES curso(id)
        ON DELETE RESTRICT
        ON UPDATE CASCADE,

    CONSTRAINT fk_disciplina_pre_requisito
        FOREIGN KEY (pre_requisito_id)
        REFERENCES disciplina_ifnmg(id)
        ON DELETE SET NULL
        ON UPDATE CASCADE
);

-- =========================================================
-- TABELA: solicitacao_aproveitamento
-- Uma solicitação:
-- - pertence a um estudante
-- - pode ser analisada por um coordenador
-- - possui status e resultado final
-- =========================================================
CREATE TABLE solicitacao_aproveitamento (
    id SERIAL PRIMARY KEY,
    numero_protocolo VARCHAR(50) NOT NULL UNIQUE,
    estudante_id INT NOT NULL,
    coordenador_id INT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'EM_EDICAO',
    resultado_final VARCHAR(25) NULL,
    data_criacao TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    data_envio TIMESTAMP NULL,
    data_finalizacao TIMESTAMP NULL,

    CONSTRAINT chk_solicitacao_status
        CHECK (status IN ('EM_EDICAO', 'ENVIADA', 'EM_ANALISE', 'FINALIZADA')),

    CONSTRAINT chk_solicitacao_resultado
        CHECK (
            resultado_final IS NULL OR
            resultado_final IN ('DEFERIDO_TOTAL', 'DEFERIDO_PARCIAL', 'INDEFERIDO_TOTAL')
        ),

    CONSTRAINT fk_solicitacao_estudante
        FOREIGN KEY (estudante_id)
        REFERENCES estudante(id)
        ON DELETE RESTRICT
        ON UPDATE CASCADE,

    CONSTRAINT fk_solicitacao_coordenador
        FOREIGN KEY (coordenador_id)
        REFERENCES coordenador(id)
        ON DELETE SET NULL
        ON UPDATE CASCADE
);

-- =========================================================
-- TABELA: item_equivalencia
-- Cada item:
-- - pertence a uma solicitação
-- - aponta para uma disciplina do IFNMG
-- - armazena dados da disciplina de origem
-- - recebe parecer individual
-- =========================================================
CREATE TABLE item_equivalencia (
    id SERIAL PRIMARY KEY,
    solicitacao_id INT NOT NULL,

    -- disciplina de origem (informada pelo estudante)
    disciplina_origem_nome VARCHAR(150) NOT NULL,
    disciplina_origem_carga_horaria INT NOT NULL,
    disciplina_origem_ementa TEXT,
    instituicao_origem VARCHAR(150) NOT NULL,

    -- disciplina de destino (IFNMG)
    disciplina_destino_id INT NOT NULL,

    -- análise
    parecer VARCHAR(15) NOT NULL DEFAULT 'PENDENTE',
    justificativa TEXT,
    data_analise TIMESTAMP NULL,

    CONSTRAINT chk_item_carga_horaria
        CHECK (disciplina_origem_carga_horaria > 0),

    CONSTRAINT chk_item_parecer
        CHECK (parecer IN ('PENDENTE', 'DEFERIDO', 'INDEFERIDO')),

    CONSTRAINT fk_item_solicitacao
        FOREIGN KEY (solicitacao_id)
        REFERENCES solicitacao_aproveitamento(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    CONSTRAINT fk_item_disciplina_destino
        FOREIGN KEY (disciplina_destino_id)
        REFERENCES disciplina_ifnmg(id)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
);

-- =========================================================
-- TABELA: log_acao
-- Registra rastreabilidade mínima do processo
-- =========================================================
CREATE TABLE log_acao (
    id SERIAL PRIMARY KEY,
    solicitacao_id INT NOT NULL,
    descricao TEXT NOT NULL,
    usuario_nome VARCHAR(150) NOT NULL,
    data_hora TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_log_solicitacao
        FOREIGN KEY (solicitacao_id)
        REFERENCES solicitacao_aproveitamento(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

-- =========================================================
-- ÍNDICES ÚTEIS
-- =========================================================
CREATE INDEX idx_estudante_curso_id
    ON estudante(curso_id);

CREATE INDEX idx_disciplina_curso_id
    ON disciplina_ifnmg(curso_id);

CREATE INDEX idx_solicitacao_estudante_id
    ON solicitacao_aproveitamento(estudante_id);

CREATE INDEX idx_solicitacao_coordenador_id
    ON solicitacao_aproveitamento(coordenador_id);

CREATE INDEX idx_item_solicitacao_id
    ON item_equivalencia(solicitacao_id);

CREATE INDEX idx_item_disciplina_destino_id
    ON item_equivalencia(disciplina_destino_id);

CREATE INDEX idx_log_solicitacao_id
    ON log_acao(solicitacao_id);