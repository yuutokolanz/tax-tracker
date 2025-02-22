SET foreign_key_checks = 0;

DROP TABLE IF EXISTS accountants;
DROP TABLE IF EXISTS documents;
DROP TABLE IF EXISTS expenses;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS categories_expenses;
DROP TABLE IF EXISTS clients;
DROP TABLE IF EXISTS accountants_clients;
DROP TABLE IF EXISTS declarations;
DROP TABLE IF EXISTS roles;

CREATE TABLE roles (
    id INT PRIMARY KEY,
    role_name VARCHAR(50),
    CONSTRAINT unique_role_name UNIQUE (role_name)
);

INSERT INTO roles (id, role_name) VALUES (1, 'accountant');
INSERT INTO roles (id, role_name) VALUES (2, 'supervisor');
INSERT INTO roles (id, role_name) VALUES (3, 'admin');

CREATE TABLE accountants (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    encrypted_password VARCHAR(255),
    created_at DATE,
    supervisor_id INT,
    role_id INT,
    avatar_name VARCHAR(65),
    FOREIGN KEY (supervisor_id) REFERENCES accountants (id),
    FOREIGN KEY (role_id) REFERENCES roles (id)
);

CREATE TABLE clients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    cpf CHAR(11),
    email VARCHAR(100),
    created_at DATE
);

CREATE TABLE declarations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT,
    year INT,
    status VARCHAR(50),
    tax_due FLOAT,
    tax_return FLOAT,
    created_at DATE,
    updated_at DATE,
    FOREIGN KEY (client_id) REFERENCES clients (id) ON DELETE CASCADE
);

CREATE TABLE documents (
    id INT PRIMARY KEY,
    declaration_id INT,
    document_type VARCHAR(50),
    file_path VARCHAR(255),
    uploaded_at DATE,
    FOREIGN KEY (declaration_id) REFERENCES declarations (id) ON DELETE CASCADE
);

CREATE TABLE categories (
    id INT PRIMARY KEY,
    name VARCHAR(50)
);

CREATE TABLE expenses (
    id INT PRIMARY KEY,
    declaration_id INT,
    description VARCHAR(200),
    amount FLOAT,
    date DATE,
    category_id INT,
    FOREIGN KEY (declaration_id) REFERENCES declarations (id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories (id)
);

CREATE TABLE categories_expenses (
    expense_id INT,
    category_id INT,
    PRIMARY KEY (expense_id, category_id),
    FOREIGN KEY (expense_id) REFERENCES expenses (id),
    FOREIGN KEY (category_id) REFERENCES categories (id)
);

CREATE TABLE accountants_clients (
    accountant_id INT,
    client_id INT,
    PRIMARY KEY (accountant_id, client_id),
    FOREIGN KEY (accountant_id) REFERENCES accountants (id),
    FOREIGN KEY (client_id) REFERENCES clients (id)
);

SET foreign_key_checks = 1;
