CREATE TABLE shows (
    id INT AUTO_INCREMENT PRIMARY KEY,

    title VARCHAR(255) NOT NULL,
    description TEXT,
    category VARCHAR(100),

    date DATE NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,

    venue VARCHAR(255),
    city VARCHAR(100),

    price INT NOT NULL,

    thumbnail VARCHAR(255),

    capacity INT NOT NULL,
    available_tickets INT NOT NULL,

    status VARCHAR(50) NOT NULL DEFAULT 'available',

    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
