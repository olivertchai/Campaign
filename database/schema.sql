SET session_replication_role = replica;

DROP TABLE IF EXISTS campaigns;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(50) UNIQUE NOT NULL,
    encrypted_password VARCHAR(255) NOT NULL,
    role VARCHAR(50) NOT NULL DEFAULT 'manager_marketing',
    active BOOLEAN NOT NULL DEFAULT TRUE
);

CREATE TABLE campaigns (
    id SERIAL PRIMARY KEY,
    user_id INTEGER NOT NULL REFERENCES users(id) ON DELETE RESTRICT,
    title VARCHAR(100) NOT NULL,
    description TEXT,
    start_date DATE,
    end_date DATE,
    status VARCHAR(50),
    image_url VARCHAR(255)
);

SET session_replication_role = DEFAULT;