
-- root@95d0842cd37b:/data# sqlite3 sqlite.db

CREATE TABLE users(
    id INT PRIMARY KEY NOT NULL UNIQUE,
    name TEXT NOT NULL ,
    age INT NOT NULL,
    email TEXT NOT NULL UNIQUE
);

INSERT INTO users(id, name, age, email) VALUES (1, 'Paloma dos Santos', 30, 'paloma@email.com');
INSERT INTO users(id, name, age, email) VALUES (2, 'Jaime dos Santos', 32, 'jaime@email.com');

SELECT * FROM users;

-- DROP TABLE users;