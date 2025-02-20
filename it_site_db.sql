CREATE DATABASE it_portal DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(256) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'expert', 'user', 'consultant') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE professions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) UNIQUE NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE ratings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    expert_id INT NOT NULL,
    profession_id INT NOT NULL,
    rating INT CHECK (rating BETWEEN 1 AND 5),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (expert_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (profession_id) REFERENCES professions(id) ON DELETE CASCADE,
    UNIQUE (expert_id, profession_id)
);
CREATE TABLE IF NOT EXISTS messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sender_id INT NOT NULL,
    receiver_id INT NOT NULL,
    message TEXT NOT NULL,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sender_id) REFERENCES users(id),
    FOREIGN KEY (receiver_id) REFERENCES users(id)
);
CREATE TABLE reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    expert_id INT NOT NULL,
    profession_id INT NOT NULL,
    review TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (expert_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (profession_id) REFERENCES professions(id) ON DELETE CASCADE
);

ALTER TABLE reviews ADD COLUMN rating INT CHECK (rating BETWEEN 1 AND 5);

ALTER TABLE reviews ADD UNIQUE (expert_id, profession_id);

INSERT INTO professions (name, description) VALUES
('Разработчик', 'Создает веб-сайты и приложения.'),
('Аналитик данных', 'Анализирует данные и строит модели.'),
('Системный администратор', 'Обеспечивает работу IT-инфраструктуры.');