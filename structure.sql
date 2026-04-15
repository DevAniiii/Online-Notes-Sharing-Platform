-- USE notes_platform;

-- USERS TABLE (for login + ownership)
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- NOTES TABLE (core pastebin logic)
CREATE TABLE notes (
    id INT AUTO_INCREMENT PRIMARY KEY,

    unique_id VARCHAR(32) NOT NULL UNIQUE,

    title VARCHAR(255) DEFAULT 'Untitled',
    content LONGTEXT NOT NULL,

    visibility ENUM('public','unlisted','private') DEFAULT 'public',

    user_id INT NULL,

    password VARCHAR(255) NULL,

    expiry DATETIME NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- INDEXES (performance boost)
CREATE INDEX idx_visibility ON notes(visibility);
CREATE INDEX idx_unique_id ON notes(unique_id);