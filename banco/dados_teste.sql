-- =========================================================
-- DADOS DE TESTE
-- Sistema de Aproveitamento de Estudos
-- =========================================================

-- =========================================================
-- LIMPEZA DAS TABELAS
-- =========================================================
DELETE FROM log_acao;
DELETE FROM item_equivalencia;
DELETE FROM solicitacao_aproveitamento;
DELETE FROM disciplina_ifnmg;
DELETE FROM estudante;
DELETE FROM coordenador;
DELETE FROM curso;

-- Reiniciar sequências
ALTER SEQUENCE curso_id_seq RESTART WITH 1;
ALTER SEQUENCE coordenador_id_seq RESTART WITH 1;
ALTER SEQUENCE estudante_id_seq RESTART WITH 1;
ALTER SEQUENCE disciplina_ifnmg_id_seq RESTART WITH 1;
ALTER SEQUENCE solicitacao_aproveitamento_id_seq RESTART WITH 1;
ALTER SEQUENCE item_equivalencia_id_seq RESTART WITH 1;
ALTER SEQUENCE log_acao_id_seq RESTART WITH 1;

-- =========================================================
-- CURSOS
-- =========================================================
INSERT INTO curso (nome, campus) VALUES
('Bacharelado em Sistemas de Informação', 'Campus Januária'),
('Licenciatura em Matemática', 'Campus Januária'),
('Tecnologia em Gestão Pública', 'Campus Montes Claros');

-- =========================================================
-- COORDENADORES
-- Cada curso tem um único coordenador
-- =========================================================
INSERT INTO coordenador (nome, email, curso_id) VALUES
('Mariana Alves Souza', 'mariana.souza@ifnmg.edu.br', 1),
('Carlos Henrique Mendes', 'carlos.mendes@ifnmg.edu.br', 2),
('Fernanda Rocha Lima', 'fernanda.lima@ifnmg.edu.br', 3);

-- =========================================================
-- ESTUDANTES
-- =========================================================
INSERT INTO estudante (nome, matricula, email, curso_id) VALUES
('André Felipe Oliveira Lopes', '202400001', 'andre.felipe@aluno.ifnmg.edu.br', 1),
('João Pedro Santos', '202400002', 'joao.santos@aluno.ifnmg.edu.br', 1),
('Larissa Mendes Costa', '202400003', 'larissa.costa@aluno.ifnmg.edu.br', 2),
('Camila Rocha Nunes', '202400004', 'camila.nunes@aluno.ifnmg.edu.br', 3);

-- =========================================================
-- DISCIPLINAS IFNMG
-- =========================================================
INSERT INTO disciplina_ifnmg (codigo, nome, carga_horaria, ementa, curso_id, pre_requisito_id) VALUES
('INF101', 'Algoritmos e Lógica de Programação', 80, 'Introdução à lógica, algoritmos, estruturas básicas e resolução de problemas.', 1, NULL),
('INF102', 'Programação I', 80, 'Fundamentos de programação, tipos de dados, estruturas de controle e funções.', 1, 1),
('INF201', 'Banco de Dados I', 60, 'Modelagem conceitual, relacional, SQL e normalização.', 1, NULL),
('INF202', 'Estruturas de Dados', 80, 'Listas, pilhas, filas, árvores e análise de complexidade.', 1, 2),

('MAT101', 'Cálculo I', 80, 'Limites, derivadas e aplicações.', 2, NULL),
('MAT102', 'Geometria Analítica', 60, 'Vetores, retas, planos e cônicas.', 2, NULL),

('GEP101', 'Administração Pública', 60, 'Fundamentos da administração pública e organização do Estado.', 3, NULL),
('GEP102', 'Políticas Públicas', 60, 'Conceitos, formulação e avaliação de políticas públicas.', 3, 7);

-- =========================================================
-- SOLICITAÇÕES DE APROVEITAMENTO
-- =========================================================
INSERT INTO solicitacao_aproveitamento (
    numero_protocolo,
    estudante_id,
    coordenador_id,
    status,
    resultado_final,
    data_criacao,
    data_envio,
    data_finalizacao
) VALUES
('APR-202604030001-101', 1, 1, 'EM_EDICAO', NULL, '2026-04-03 08:00:00', NULL, NULL),

('APR-202604030002-102', 2, 1, 'EM_ANALISE', NULL, '2026-04-03 08:10:00', '2026-04-03 08:20:00', NULL),

('APR-202604030003-103', 3, 2, 'FINALIZADA', 'DEFERIDO_TOTAL', '2026-04-03 08:30:00', '2026-04-03 08:40:00', '2026-04-03 09:00:00'),

('APR-202604030004-104', 4, 3, 'FINALIZADA', 'DEFERIDO_PARCIAL', '2026-04-03 09:10:00', '2026-04-03 09:20:00', '2026-04-03 09:50:00'),

('APR-202604030005-105', 1, 1, 'FINALIZADA', 'INDEFERIDO_TOTAL', '2026-04-03 10:00:00', '2026-04-03 10:10:00', '2026-04-03 10:40:00');

-- =========================================================
-- ITENS DE EQUIVALÊNCIA
-- =========================================================

-- Solicitação 1 (EM_EDICAO)
INSERT INTO item_equivalencia (
    solicitacao_id,
    disciplina_origem_nome,
    disciplina_origem_carga_horaria,
    disciplina_origem_ementa,
    instituicao_origem,
    disciplina_destino_id,
    parecer,
    justificativa,
    data_analise
) VALUES
(1, 'Introdução à Programação', 80, 'Lógica de programação, algoritmos e estruturas básicas.', 'Universidade Estadual do Norte', 1, 'PENDENTE', NULL, NULL),
(1, 'Banco de Dados', 60, 'Modelagem relacional, SQL e normalização.', 'Universidade Estadual do Norte', 3, 'PENDENTE', NULL, NULL);

-- Solicitação 2 (EM_ANALISE)
INSERT INTO item_equivalencia (
    solicitacao_id,
    disciplina_origem_nome,
    disciplina_origem_carga_horaria,
    disciplina_origem_ementa,
    instituicao_origem,
    disciplina_destino_id,
    parecer,
    justificativa,
    data_analise
) VALUES
(2, 'Fundamentos de Programação', 80, 'Tipos de dados, operadores, estruturas de decisão e repetição.', 'Faculdade do Vale', 2, 'DEFERIDO', NULL, '2026-04-03 08:35:00'),
(2, 'Estruturas Avançadas', 40, 'Pilhas, filas e listas.', 'Faculdade do Vale', 4, 'PENDENTE', NULL, NULL);

-- Solicitação 3 (FINALIZADA - DEFERIDO_TOTAL)
INSERT INTO item_equivalencia (
    solicitacao_id,
    disciplina_origem_nome,
    disciplina_origem_carga_horaria,
    disciplina_origem_ementa,
    instituicao_origem,
    disciplina_destino_id,
    parecer,
    justificativa,
    data_analise
) VALUES
(3, 'Cálculo Diferencial e Integral I', 80, 'Limites, derivadas e integrais básicas.', 'Instituto Federal do Sul', 5, 'DEFERIDO', NULL, '2026-04-03 08:50:00'),
(3, 'Geometria Analítica e Vetores', 60, 'Vetores, retas, planos e sistemas lineares.', 'Instituto Federal do Sul', 6, 'DEFERIDO', NULL, '2026-04-03 08:52:00');

-- Solicitação 4 (FINALIZADA - DEFERIDO_PARCIAL)
INSERT INTO item_equivalencia (
    solicitacao_id,
    disciplina_origem_nome,
    disciplina_origem_carga_horaria,
    disciplina_origem_ementa,
    instituicao_origem,
    disciplina_destino_id,
    parecer,
    justificativa,
    data_analise
) VALUES
(4, 'Administração Pública', 60, 'Conceitos fundamentais da administração pública.', 'Centro Universitário Nacional', 7, 'DEFERIDO', NULL, '2026-04-03 09:30:00'),
(4, 'Introdução às Políticas Governamentais', 40, 'Noções introdutórias de políticas públicas.', 'Centro Universitário Nacional', 8, 'INDEFERIDO', 'Carga horária inferior à disciplina de destino.', '2026-04-03 09:35:00');

-- Solicitação 5 (FINALIZADA - INDEFERIDO_TOTAL)
INSERT INTO item_equivalencia (
    solicitacao_id,
    disciplina_origem_nome,
    disciplina_origem_carga_horaria,
    disciplina_origem_ementa,
    instituicao_origem,
    disciplina_destino_id,
    parecer,
    justificativa,
    data_analise
) VALUES
(5, 'Informática Básica', 30, 'Noções introdutórias de uso de computador.', 'Faculdade Livre do Cerrado', 1, 'INDEFERIDO', 'Conteúdo e carga horária insuficientes.', '2026-04-03 10:20:00'),
(5, 'Noções de Banco de Dados', 30, 'Conceitos básicos de armazenamento de dados.', 'Faculdade Livre do Cerrado', 3, 'INDEFERIDO', 'Carga horária insuficiente para equivalência.', '2026-04-03 10:22:00');

-- =========================================================
-- LOGS DE AÇÃO
-- =========================================================
INSERT INTO log_acao (solicitacao_id, descricao, usuario_nome, data_hora) VALUES
(1, 'Solicitação criada pelo estudante.', 'André Felipe Oliveira Lopes', '2026-04-03 08:00:00'),
(1, 'Item de equivalência adicionado.', 'André Felipe Oliveira Lopes', '2026-04-03 08:02:00'),
(1, 'Segundo item de equivalência adicionado.', 'André Felipe Oliveira Lopes', '2026-04-03 08:04:00'),

(2, 'Solicitação criada pelo estudante.', 'João Pedro Santos', '2026-04-03 08:10:00'),
(2, 'Solicitação enviada para análise.', 'João Pedro Santos', '2026-04-03 08:20:00'),
(2, 'Coordenador iniciou análise dos itens.', 'Mariana Alves Souza', '2026-04-03 08:30:00'),

(3, 'Solicitação criada pelo estudante.', 'Larissa Mendes Costa', '2026-04-03 08:30:00'),
(3, 'Solicitação enviada para análise.', 'Larissa Mendes Costa', '2026-04-03 08:40:00'),
(3, 'Todos os itens foram deferidos.', 'Carlos Henrique Mendes', '2026-04-03 08:55:00'),
(3, 'Solicitação finalizada com deferimento total.', 'Carlos Henrique Mendes', '2026-04-03 09:00:00'),

(4, 'Solicitação criada pelo estudante.', 'Camila Rocha Nunes', '2026-04-03 09:10:00'),
(4, 'Solicitação enviada para análise.', 'Camila Rocha Nunes', '2026-04-03 09:20:00'),
(4, 'Um item deferido e um item indeferido.', 'Fernanda Rocha Lima', '2026-04-03 09:35:00'),
(4, 'Solicitação finalizada com deferimento parcial.', 'Fernanda Rocha Lima', '2026-04-03 09:50:00'),

(5, 'Solicitação criada pelo estudante.', 'André Felipe Oliveira Lopes', '2026-04-03 10:00:00'),
(5, 'Solicitação enviada para análise.', 'André Felipe Oliveira Lopes', '2026-04-03 10:10:00'),
(5, 'Todos os itens foram indeferidos.', 'Mariana Alves Souza', '2026-04-03 10:25:00'),
(5, 'Solicitação finalizada com indeferimento total.', 'Mariana Alves Souza', '2026-04-03 10:40:00');