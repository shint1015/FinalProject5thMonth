CREATE TABLE audit_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    action VARCHAR(50) NOT NULL,  -- create update deleate
    entity VARCHAR(50) NOT NULL,       
    entity_id INT NOT NULL, -- ID        
    timestamp DATETIME DEFAULT CURRENT_TIMESTAMP,
    ip VARCHAR(45) DEFAULT NULL,
    details TEXT,
    PRIMARY KEY (id)
);
