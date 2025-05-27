DROP DATABASE IF EXISTS estoque;
CREATE DATABASE estoque;
USE estoque;

CREATE TABLE pessoatipo (
    pest_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    pest_descricao VARCHAR(100) NOT NULL
);

INSERT INTO pessoatipo (pest_descricao) VALUES
('Administrador'),
('Cliente'),
('Fornecedor'),
('Vendedor');

CREATE TABLE PESSOA ( 
    pes_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    pes_nome VARCHAR(250),
    pes_datacadastro DATE,
    pes_observacao VARCHAR(250),
    pes_ativo INT(1),
    pest_id INT,
    CONSTRAINT fk_pes_pest FOREIGN KEY (pest_id) REFERENCES pessoatipo(pest_id)
);

CREATE TABLE USUARIO (
    usu_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    usu_email VARCHAR(200) UNIQUE,
    usu_senha VARCHAR(250),
    usu_cadastro DATE,
    usu_ativo INT(1),
    usu_observacao VARCHAR(250),
    pes_id INT,
    CONSTRAINT FK_USU_PES FOREIGN KEY (pes_id) REFERENCES PESSOA(pes_id)
);

INSERT INTO pessoa (pes_nome, pes_datacadastro, pes_observacao, pes_ativo, pest_id)
VALUES ('admin', '0001-01-01', '', 1, 1);

INSERT INTO usuario (usu_email, usu_senha, usu_cadastro, usu_ativo, usu_observacao, pes_id)
VALUES ('admin@admin.com', '123', '0001-01-01', 1, '', 1);

CREATE TABLE PRODUTOCATEGORIA (
    procat_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    procat_descricao VARCHAR(250),
    procat_observacao VARCHAR(250)
);

CREATE TABLE PRODUTOUNIDADEMEDIDA (
    proum_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    proum_descricao VARCHAR(250),
    proum_observacao VARCHAR(250)
);

CREATE TABLE PRODUTO (
    pro_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    pro_descricao VARCHAR(250),
    pro_datacadastro DATE,
    pro_observacao VARCHAR(250),
    pro_ativo INT(1), 
    procat_id INT,
    proum_id INT,
    CONSTRAINT FK_PRO_PROCAT FOREIGN KEY (procat_id) REFERENCES PRODUTOCATEGORIA(procat_id),
    CONSTRAINT FK_PRO_PROUM FOREIGN KEY (proum_id) REFERENCES PRODUTOUNIDADEMEDIDA(proum_id)
);

CREATE TABLE ARMAZEM (
    arm_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    pro_id INT,
    arm_quantidade INT,
    arm_datacadastro DATE,
    arm_observacao VARCHAR(250),
    arm_ativo INT(1),
    CONSTRAINT FK_ARM_PRO FOREIGN KEY (pro_id) REFERENCES PRODUTO(pro_id)
);

CREATE TABLE MOVIMENTOTIPO (
    movt_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    mov_descricao VARCHAR(250),
    mov_observacao VARCHAR(250)
);

CREATE TABLE MOVIMENTO (
    mov_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    mov_data DATE,
    movt_id INT,
    pro_id INT,
    usu_id INT,
    mov_observacao VARCHAR(250),
    CONSTRAINT FK_MOV_MOVT FOREIGN KEY (movt_id) REFERENCES MOVIMENTOTIPO(movt_id),
    CONSTRAINT FK_MOV_PRO FOREIGN KEY (pro_id) REFERENCES PRODUTO(pro_id),
    CONSTRAINT FK_MOV_USU FOREIGN KEY (usu_id) REFERENCES USUARIO(usu_id)
);

ALTER TABLE movimento ADD COLUMN mov_fornecedor VARCHAR(255) NULL;
ALTER TABLE movimento ADD COLUMN mov_cliente VARCHAR(255) NULL;



