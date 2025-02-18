-- Criar um schema para organização (opcional)
CREATE SCHEMA store;

-- Criação da tabela de usuários para autenticação JWT
CREATE TABLE store.users (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password_hash TEXT NOT NULL, -- Senha armazenada com hash seguro
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Criar índice para busca rápida por email
CREATE INDEX idx_users_email ON store.users(email);

-- Criar tabela de permissões 
CREATE TABLE store.user_roles (
    id SERIAL PRIMARY KEY,
    user_id INT NOT NULL,
    role VARCHAR(50) NOT NULL, -- Exemplo: 'admin', 'user'
    FOREIGN KEY (user_id) REFERENCES store.users(id) ON DELETE CASCADE
);

-- Criar tabela de refresh tokens 
CREATE TABLE store.refresh_tokens (
    id SERIAL PRIMARY KEY,
    user_id INT NOT NULL,
    token TEXT NOT NULL,
    expires_at TIMESTAMP NOT NULL,
    FOREIGN KEY (user_id) REFERENCES store.users(id) ON DELETE CASCADE
);

-- Criar as tabelas originais dentro do schema "store"
CREATE TABLE store.categories (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) UNIQUE NOT NULL
);

CREATE TABLE store.tags (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) UNIQUE NOT NULL
);

CREATE TABLE store.products (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price NUMERIC(10,2) NOT NULL,
    category_id INT NOT NULL,
    likes INT NOT NULL DEFAULT 0,
    dislikes INT NOT NULL DEFAULT 0,
    FOREIGN KEY (category_id) REFERENCES store.categories(id) ON DELETE RESTRICT
);

CREATE TABLE store.product_tags (
    product_id INT NOT NULL,
    tag_id INT NOT NULL,
    PRIMARY KEY (product_id, tag_id),
    FOREIGN KEY (product_id) REFERENCES store.products(id) ON DELETE CASCADE,
    FOREIGN KEY (tag_id) REFERENCES store.tags(id) ON DELETE CASCADE
);

-- Índices para melhor desempenho
CREATE INDEX idx_products_name ON store.products(name);
CREATE INDEX idx_tags_name ON store.tags(name);
CREATE INDEX idx_categories_name ON store.categories(name);

-- Inserção de dados de exemplo (usuários)
INSERT INTO store.users (name, email, password_hash) 
VALUES ('Admin User', 'admin@example.com', '$2y$10$EXAMPLEHASH1234567890')
        ('jose araujo', 'testeFinal@example.com', '$2y$12$t78U0GIer2M0sF/Ddu.K6.gRo4WNilRPl90psd.Vvw675KVJcNB6G');

INSERT INTO store.user_roles (user_id, role) VALUES (1, 'admin');

-- Inserção de dados de exemplo (categorias)
INSERT INTO store.categories (name) VALUES ('Eletronicos'), ('Livros'), ('roupas');

-- Inserção de dados de exemplo (tags)
INSERT INTO store.tags (name) VALUES ('Promoções'), ('Novos'), ('Relevantes');

-- Inserção de produtos
INSERT INTO store.products (name, description, price, category_id)
VALUES 
    ('Smartphone', 'modelo mais custo beneficio', 2999.99, 1),
    ('Notbook', 'modelo de ultima geração', 4999.99, 1),
    ('ps4', 'video game', 79.90, 3);

-- Associação de produtos a tags
INSERT INTO store.product_tags (product_id, tag_id) VALUES 
    (1, 1), -- Smartphone com "Promoção"
    (2, 2), -- Notbook com "Novo"
    (3, 3); --ps4 com "Relevante"