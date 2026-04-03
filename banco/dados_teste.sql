-- =========================================================
-- DADOS DE TESTE
-- Sistema de Aproveitamento de Estudos
-- Compatível com o schema atual
-- =========================================================

-- =========================================================
-- LIMPEZA DAS TABELAS (ordem correta)
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
-- DADOS DE TESTE
-- Sistema de Aproveitamento de Estudos
-- PostgreSQL
-- =========================================================

-- =========================================================
-- CURSOS
-- =========================================================
INSERT INTO curso (id, nome, campus) VALUES
(1, 'Sistemas de Informação', 'Montes Claros'),
(2, 'Engenharia Elétrica', 'Montes Claros'),
(3, 'Administração', 'Pirapora'),
(4, 'Licenciatura em Matemática', 'Januária'),
(5, 'Tecnologia em Gestão Pública', 'Salinas');

-- =========================================================
-- COORDENADORES
-- Um por curso
-- =========================================================
INSERT INTO coordenador (id, nome, email, curso_id) VALUES
(1, 'Mariana Souza', 'mariana.souza@ifnmg.edu.br', 1),
(2, 'Carlos Henrique Lima', 'carlos.lima@ifnmg.edu.br', 2),
(3, 'Fernanda Rocha', 'fernanda.rocha@ifnmg.edu.br', 3),
(4, 'Ricardo Almeida', 'ricardo.almeida@ifnmg.edu.br', 4),
(5, 'Patrícia Oliveira', 'patricia.oliveira@ifnmg.edu.br', 5);

-- =========================================================
-- ESTUDANTES
-- =========================================================
INSERT INTO estudante (id, nome, matricula, email, curso_id) VALUES
(1, 'André Felipe Lopes', '2024001', 'andre.felipe@ifnmg.edu.br', 1),
(2, 'Beatriz Costa', '2024002', 'beatriz.costa@ifnmg.edu.br', 1),
(3, 'Lucas Pereira', '2024003', 'lucas.pereira@ifnmg.edu.br', 2),
(4, 'Camila Rodrigues', '2024004', 'camila.rodrigues@ifnmg.edu.br', 2),
(5, 'João Victor Mendes', '2024005', 'joao.mendes@ifnmg.edu.br', 3),
(6, 'Ana Clara Santos', '2024006', 'ana.santos@ifnmg.edu.br', 3),
(7, 'Gabriel Martins', '2024007', 'gabriel.martins@ifnmg.edu.br', 4),
(8, 'Larissa Fernandes', '2024008', 'larissa.fernandes@ifnmg.edu.br', 4),
(9, 'Rafael Dias', '2024009', 'rafael.dias@ifnmg.edu.br', 5),
(10, 'Juliana Ribeiro', '2024010', 'juliana.ribeiro@ifnmg.edu.br', 5);

-- =========================================================
-- DISCIPLINAS IFNMG
-- =========================================================
INSERT INTO disciplina_ifnmg (id, codigo, nome, carga_horaria, ementa, curso_id, pre_requisito_id) VALUES
-- Sistemas de Informação
(1, 'SIN101', 'Algoritmos e Programação', 80, 'Lógica de programação, algoritmos, estruturas básicas.', 1, NULL),
(2, 'SIN102', 'Banco de Dados I', 60, 'Modelagem conceitual, relacional e SQL.', 1, 1),
(3, 'SIN103', 'Estruturas de Dados', 80, 'Listas, pilhas, filas, árvores e grafos.', 1, 1),
(4, 'SIN104', 'Programação Web', 60, 'HTML, CSS, JavaScript, frameworks e MVC.', 1, 1),
(5, 'SIN105', 'Engenharia de Software', 60, 'Processos, requisitos, UML e qualidade.', 1, NULL),

-- Engenharia Elétrica
(6, 'ELE101', 'Circuitos Elétricos I', 80, 'Leis fundamentais, análise de circuitos.', 2, NULL),
(7, 'ELE102', 'Eletrônica Analógica', 60, 'Diodos, transistores e amplificadores.', 2, 6),
(8, 'ELE103', 'Instalações Elétricas', 60, 'Dimensionamento e normas técnicas.', 2, 6),

-- Administração
(9, 'ADM101', 'Introdução à Administração', 60, 'Teorias administrativas e funções gerenciais.', 3, NULL),
(10, 'ADM102', 'Gestão de Pessoas', 60, 'Recrutamento, seleção e desenvolvimento humano.', 3, 9),
(11, 'ADM103', 'Contabilidade Básica', 60, 'Conceitos contábeis e demonstrações financeiras.', 3, NULL),

-- Matemática
(12, 'MAT101', 'Cálculo I', 80, 'Limites, derivadas e aplicações.', 4, NULL),
(13, 'MAT102', 'Geometria Analítica', 60, 'Vetores, retas, planos e cônicas.', 4, NULL),
(14, 'MAT103', 'Álgebra Linear', 60, 'Matrizes, determinantes e espaços vetoriais.', 4, NULL),

-- Gestão Pública
(15, 'GEP101', 'Administração Pública', 60, 'Conceitos e fundamentos da administração pública.', 5, NULL),
(16, 'GEP102', 'Políticas Públicas', 60, 'Formulação, implementação e avaliação.', 5, 15),
(17, 'GEP103', 'Direito Administrativo', 60, 'Princípios e atos administrativos.', 5, 15);

-- =========================================================
-- SOLICITAÇÕES
-- Cenários variados:
-- - em edição
-- - em análise
-- - finalizada deferida total
-- - finalizada deferida parcial
-- - finalizada indeferida total
-- =========================================================
INSERT INTO solicitacao_aproveitamento (
    id, numero_protocolo, estudante_id, coordenador_id, status, resultado_final,
    data_criacao, data_envio, data_finalizacao
) VALUES
(1, 'APR-202604020001', 1, 1, 'EM_EDICAO', NULL, '2026-04-02 09:00:00', NULL, NULL),
(2, 'APR-202604020002', 2, 1, 'EM_ANALISE', NULL, '2026-04-02 09:30:00', '2026-04-02 10:00:00', NULL),
(3, 'APR-202604020003', 3, 2, 'FINALIZADA', 'DEFERIDO_TOTAL', '2026-04-02 08:00:00', '2026-04-02 08:30:00', '2026-04-02 11:00:00'),
(4, 'APR-202604020004', 4, 2, 'FINALIZADA', 'DEFERIDO_PARCIAL', '2026-04-02 08:15:00', '2026-04-02 08:45:00', '2026-04-02 11:10:00'),
(5, 'APR-202604020005', 5, 3, 'FINALIZADA', 'INDEFERIDO_TOTAL', '2026-04-02 08:20:00', '2026-04-02 08:50:00', '2026-04-02 11:20:00'),
(6, 'APR-202604020006', 6, 3, 'EM_EDICAO', NULL, '2026-04-02 09:40:00', NULL, NULL),
(7, 'APR-202604020007', 7, 4, 'EM_ANALISE', NULL, '2026-04-02 10:10:00', '2026-04-02 10:40:00', NULL),
(8, 'APR-202604020008', 8, 4, 'FINALIZADA', 'DEFERIDO_TOTAL', '2026-04-02 07:50:00', '2026-04-02 08:10:00', '2026-04-02 10:50:00'),
(9, 'APR-202604020009', 9, 5, 'EM_EDICAO', NULL, '2026-04-02 10:20:00', NULL, NULL),
(10, 'APR-202604020010', 10, 5, 'EM_ANALISE', NULL, '2026-04-02 10:25:00', '2026-04-02 10:55:00', NULL);

-- =========================================================
-- ITENS DE EQUIVALÊNCIA
-- =========================================================
INSERT INTO item_equivalencia (
    id, solicitacao_id, disciplina_origem_nome, disciplina_origem_carga_horaria,
    disciplina_origem_ementa, instituicao_origem, disciplina_destino_id,
    parecer, justificativa, data_analise
) VALUES

-- =========================================================
-- SOLICITAÇÃO 1 (EM_EDICAO)
-- =========================================================
(1, 1, 'Lógica de Programação', 80,
 'Introdução à lógica, algoritmos e programação estruturada.',
 'Universidade Estadual Alfa', 1,
 'PENDENTE', NULL, NULL),

(2, 1, 'Banco de Dados', 60,
 'Modelagem relacional, SQL e normalização.',
 'Universidade Estadual Alfa', 2,
 'PENDENTE', NULL, NULL),

-- =========================================================
-- SOLICITAÇÃO 2 (EM_ANALISE)
-- Um item já analisado e outro pendente
-- =========================================================
(3, 2, 'Programação I', 80,
 'Algoritmos, tipos de dados e estruturas básicas.',
 'Faculdade Beta', 1,
 'DEFERIDO', NULL, '2026-04-02 10:20:00'),

(4, 2, 'Desenvolvimento Web', 40,
 'HTML, CSS e introdução ao JavaScript.',
 'Faculdade Beta', 4,
 'PENDENTE', NULL, NULL),

-- =========================================================
-- SOLICITAÇÃO 3 (FINALIZADA - DEFERIDO_TOTAL)
-- Todos deferidos
-- =========================================================
(5, 3, 'Circuitos Básicos', 80,
 'Análise de circuitos resistivos e leis fundamentais.',
 'Instituto Técnico Delta', 6,
 'DEFERIDO', NULL, '2026-04-02 09:00:00'),

(6, 3, 'Eletrônica I', 60,
 'Semicondutores, diodos e transistores.',
 'Instituto Técnico Delta', 7,
 'DEFERIDO', NULL, '2026-04-02 09:10:00'),

-- =========================================================
-- SOLICITAÇÃO 4 (FINALIZADA - DEFERIDO_PARCIAL)
-- Um deferido e um indeferido
-- =========================================================
(7, 4, 'Instalações Prediais', 60,
 'Projetos e dimensionamento de instalações.',
 'Centro Universitário Ômega', 8,
 'DEFERIDO', NULL, '2026-04-02 09:20:00'),

(8, 4, 'Circuitos Avançados', 40,
 'Circuitos complexos e aplicações práticas.',
 'Centro Universitário Ômega', 6,
 'INDEFERIDO', 'Carga horária insuficiente para equivalência integral.', '2026-04-02 09:30:00'),

-- =========================================================
-- SOLICITAÇÃO 5 (FINALIZADA - INDEFERIDO_TOTAL)
-- Todos indeferidos
-- =========================================================
(9, 5, 'Administração Empresarial', 40,
 'Fundamentos de gestão empresarial.',
 'Faculdade Sigma', 9,
 'INDEFERIDO', 'Carga horária inferior à disciplina de destino.', '2026-04-02 09:40:00'),

(10, 5, 'RH Estratégico', 30,
 'Conceitos básicos de gestão de pessoas.',
 'Faculdade Sigma', 10,
 'INDEFERIDO', 'Conteúdo programático insuficiente e CH inferior.', '2026-04-02 09:50:00'),

-- =========================================================
-- SOLICITAÇÃO 6 (EM_EDICAO)
-- =========================================================
(11, 6, 'Contabilidade Geral', 60,
 'Noções introdutórias de contabilidade.',
 'Universidade Gama', 11,
 'PENDENTE', NULL, NULL),

(12, 6, 'Introdução à Administração', 60,
 'Teorias da administração e planejamento.',
 'Universidade Gama', 9,
 'PENDENTE', NULL, NULL),

(13, 6, 'Gestão de Pessoas Aplicada', 60,
 'Processos de recrutamento e seleção.',
 'Universidade Gama', 10,
 'PENDENTE', NULL, NULL),

-- =========================================================
-- SOLICITAÇÃO 7 (EM_ANALISE)
-- Para testar análise item a item
-- =========================================================
(14, 7, 'Cálculo Diferencial', 80,
 'Limites, derivadas e aplicações.',
 'Universidade Horizonte', 12,
 'PENDENTE', NULL, NULL),

(15, 7, 'Vetores e Geometria', 60,
 'Vetores, retas, planos e coordenadas.',
 'Universidade Horizonte', 13,
 'DEFERIDO', NULL, '2026-04-02 11:00:00'),

(16, 7, 'Álgebra Matricial', 40,
 'Matrizes, sistemas lineares e determinantes.',
 'Universidade Horizonte', 14,
 'PENDENTE', NULL, NULL),

-- =========================================================
-- SOLICITAÇÃO 8 (FINALIZADA - DEFERIDO_TOTAL)
-- =========================================================
(17, 8, 'Cálculo I', 80,
 'Limites e derivadas.',
 'Instituto Superior Lambda', 12,
 'DEFERIDO', NULL, '2026-04-02 09:15:00'),

(18, 8, 'Geometria Analítica', 60,
 'Retas, planos, vetores e cônicas.',
 'Instituto Superior Lambda', 13,
 'DEFERIDO', NULL, '2026-04-02 09:25:00'),

-- =========================================================
-- SOLICITAÇÃO 9 (EM_EDICAO)
-- =========================================================
(19, 9, 'Gestão Governamental', 60,
 'Princípios de gestão pública e políticas institucionais.',
 'Universidade Pública Norte', 15,
 'PENDENTE', NULL, NULL),

-- =========================================================
-- SOLICITAÇÃO 10 (EM_ANALISE)
-- =========================================================
(20, 10, 'Políticas Institucionais', 60,
 'Planejamento, execução e avaliação de políticas públicas.',
 'Universidade Pública Norte', 16,
 'PENDENTE', NULL, NULL),

(21, 10, 'Legislação Administrativa', 60,
 'Atos administrativos, princípios e estrutura do Estado.',
 'Universidade Pública Norte', 17,
 'PENDENTE', NULL, NULL);

-- =========================================================
-- LOGS DE AÇÃO
-- =========================================================
INSERT INTO log_acao (solicitacao_id, descricao, usuario_nome, data_hora) VALUES
(1, 'Solicitação criada.', 'Sistema', '2026-04-02 09:00:00'),
(1, 'Item de equivalência adicionado.', 'Sistema', '2026-04-02 09:02:00'),

(2, 'Solicitação criada.', 'Sistema', '2026-04-02 09:30:00'),
(2, 'Solicitação enviada para análise.', 'André Felipe Lopes', '2026-04-02 10:00:00'),
(2, 'Item #3 analisado como DEFERIDO.', 'Mariana Souza', '2026-04-02 10:20:00'),

(3, 'Solicitação criada.', 'Sistema', '2026-04-02 08:00:00'),
(3, 'Solicitação enviada para análise.', 'Lucas Pereira', '2026-04-02 08:30:00'),
(3, 'Todos os itens foram deferidos.', 'Carlos Henrique Lima', '2026-04-02 10:55:00'),
(3, 'Solicitação finalizada com resultado DEFERIDO_TOTAL.', 'Carlos Henrique Lima', '2026-04-02 11:00:00'),

(4, 'Solicitação criada.', 'Sistema', '2026-04-02 08:15:00'),
(4, 'Solicitação enviada para análise.', 'Camila Rodrigues', '2026-04-02 08:45:00'),
(4, 'Um item foi deferido e outro indeferido.', 'Carlos Henrique Lima', '2026-04-02 11:05:00'),
(4, 'Solicitação finalizada com resultado DEFERIDO_PARCIAL.', 'Carlos Henrique Lima', '2026-04-02 11:10:00'),

(5, 'Solicitação criada.', 'Sistema', '2026-04-02 08:20:00'),
(5, 'Solicitação enviada para análise.', 'João Victor Mendes', '2026-04-02 08:50:00'),
(5, 'Todos os itens foram indeferidos.', 'Fernanda Rocha', '2026-04-02 11:15:00'),
(5, 'Solicitação finalizada com resultado INDEFERIDO_TOTAL.', 'Fernanda Rocha', '2026-04-02 11:20:00');