CREATE TABLE catalogue_admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL
);

INSERT INTO catalogue_admin (username,password_hash) VALUES
('amita', '$2a$12$xORF9Hh2fcIrfyCTH6fqIexTTPzIJrsOPiRxHiMsPkUJNGodlfWZS'),
('komalpreet', '$2a$12$uqBWqtUeE9AohkeMQTJM1u.BM5EFCnLd9OmM2UT0s.7Q/eYMFD1YO'),
('zara', '$2a$12$a7MStzL9ZbKxGgiujzleo.0jR7fKSjc7kF71MNWZF5EzV8WyINZ7K'),
('shalom', '$2a$12$CaUY.lVz1WtzLKwS0W/Vj.WLaeGthgonwFcX6hlJcGTwG/a2dZqVa'),
('instructor', '$2a$12$.M4Ip/lj2VglTqwhpzCALeE6LXTwzlxTHfdyymKL82As6yIItpjUm');