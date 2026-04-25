-- ============================================
-- PasteNotes - Database Structure
-- Database Name: notes_platform
-- Created: April 25, 2026
-- ============================================

-- Drop existing database if it exists (optional - comment out if you want to preserve data)
-- DROP DATABASE IF EXISTS notes_platform;

-- Create Database
CREATE DATABASE IF NOT EXISTS notes_platform 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

-- Use the database
USE notes_platform;

-- ============================================
-- USERS TABLE (Authentication & Ownership)
-- ============================================
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    
    -- Profile Information
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    username VARCHAR(100) UNIQUE,
    
    -- Security
    password VARCHAR(255) NOT NULL,
    password_reset_token VARCHAR(255),
    password_reset_expires DATETIME,
    
    -- Account Status
    is_active BOOLEAN DEFAULT TRUE,
    email_verified BOOLEAN DEFAULT FALSE,
    email_verified_at DATETIME,
    
    -- Timestamps
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    -- Indexes
    UNIQUE INDEX idx_email (email),
    UNIQUE INDEX idx_username (username),
    INDEX idx_is_active (is_active)
);

-- ============================================
-- NOTES TABLE (Core Pastebin/Notes Logic)
-- ============================================
CREATE TABLE IF NOT EXISTS notes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    
    -- Identification
    unique_id VARCHAR(32) NOT NULL UNIQUE,
    
    -- Content
    title VARCHAR(255) DEFAULT 'Untitled',
    content LONGTEXT NOT NULL,
    
    -- Content Metadata
    character_count INT DEFAULT 0,
    line_count INT DEFAULT 0,
    language VARCHAR(50),
    
    -- Visibility & Access Control
    visibility ENUM('public','unlisted','private') DEFAULT 'public',
    user_id INT NULL,
    password VARCHAR(255) NULL,
    
    -- Expiration
    expiry DATETIME NULL,
    is_expired BOOLEAN DEFAULT FALSE,
    
    -- Statistics
    view_count INT DEFAULT 0,
    download_count INT DEFAULT 0,
    
    -- Timestamps
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    -- Foreign Keys
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    
    -- Indexes for Performance
    UNIQUE INDEX idx_unique_id (unique_id),
    INDEX idx_visibility (visibility),
    INDEX idx_user_id (user_id),
    INDEX idx_created_at (created_at),
    INDEX idx_view_count (view_count),
    INDEX idx_expiry (expiry)
);

-- ============================================
-- COMMENTS TABLE (For Future Collaboration)
-- ============================================
CREATE TABLE IF NOT EXISTS comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    
    -- References
    note_id INT NOT NULL,
    user_id INT NOT NULL,
    
    -- Content
    comment TEXT NOT NULL,
    
    -- Timestamps
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    -- Foreign Keys
    FOREIGN KEY (note_id) REFERENCES notes(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    
    -- Indexes
    INDEX idx_note_id (note_id),
    INDEX idx_user_id (user_id)
);

-- ============================================
-- TAGS TABLE (For Note Organization)
-- ============================================
CREATE TABLE IF NOT EXISTS tags (
    id INT AUTO_INCREMENT PRIMARY KEY,
    
    -- Tag Information
    name VARCHAR(100) NOT NULL UNIQUE,
    slug VARCHAR(100) UNIQUE,
    
    -- Timestamps
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    -- Indexes
    UNIQUE INDEX idx_name (name),
    INDEX idx_slug (slug)
);

-- ============================================
-- NOTE_TAGS TABLE (Many-to-Many Relationship)
-- ============================================
CREATE TABLE IF NOT EXISTS note_tags (
    id INT AUTO_INCREMENT PRIMARY KEY,
    
    -- References
    note_id INT NOT NULL,
    tag_id INT NOT NULL,
    
    -- Foreign Keys
    FOREIGN KEY (note_id) REFERENCES notes(id) ON DELETE CASCADE,
    FOREIGN KEY (tag_id) REFERENCES tags(id) ON DELETE CASCADE,
    
    -- Unique Constraint
    UNIQUE INDEX idx_note_tag (note_id, tag_id),
    
    -- Indexes
    INDEX idx_note_id (note_id),
    INDEX idx_tag_id (tag_id)
);

-- ============================================
-- VIEW HISTORY TABLE (For Analytics)
-- ============================================
CREATE TABLE IF NOT EXISTS view_history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    
    -- References
    note_id INT NOT NULL,
    
    -- Viewer Information
    user_id INT NULL,
    ip_address VARCHAR(45),
    user_agent VARCHAR(255),
    
    -- Timestamps
    viewed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    -- Foreign Keys
    FOREIGN KEY (note_id) REFERENCES notes(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    
    -- Indexes
    INDEX idx_note_id (note_id),
    INDEX idx_user_id (user_id),
    INDEX idx_viewed_at (viewed_at)
);

-- ============================================
-- SESSION TABLE (For User Sessions)
-- ============================================
CREATE TABLE IF NOT EXISTS sessions (
    id VARCHAR(128) PRIMARY KEY,
    
    -- User Reference
    user_id INT NOT NULL,
    
    -- Session Data
    data LONGTEXT,
    
    -- Timestamps
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    expires_at DATETIME NOT NULL,
    
    -- Foreign Keys
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    
    -- Indexes
    INDEX idx_user_id (user_id),
    INDEX idx_expires_at (expires_at)
);

-- ============================================
-- AUDIT LOG TABLE (For Security & Compliance)
-- ============================================
CREATE TABLE IF NOT EXISTS audit_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    
    -- User Reference
    user_id INT NULL,
    
    -- Audit Information
    action VARCHAR(100) NOT NULL,
    entity_type VARCHAR(100),
    entity_id INT,
    changes JSON,
    
    -- Request Details
    ip_address VARCHAR(45),
    user_agent VARCHAR(255),
    
    -- Timestamps
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    -- Indexes
    INDEX idx_user_id (user_id),
    INDEX idx_action (action),
    INDEX idx_created_at (created_at),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- ============================================
-- SETTINGS TABLE (For Platform Configuration)
-- ============================================
CREATE TABLE IF NOT EXISTS settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    
    -- Setting Key-Value
    setting_key VARCHAR(100) UNIQUE NOT NULL,
    setting_value JSON,
    
    -- Type Information
    setting_type VARCHAR(50),
    description TEXT,
    
    -- Timestamps
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    -- Indexes
    UNIQUE INDEX idx_setting_key (setting_key)
);

-- ============================================
-- INSERT SAMPLE DATA (Optional)
-- ============================================

-- Sample Settings
INSERT IGNORE INTO settings (setting_key, setting_value, setting_type, description) VALUES
('platform_name', '"PasteNotes"', 'string', 'Application name'),
('max_paste_size', '10485760', 'integer', 'Maximum paste size in bytes (10MB default)'),
('default_expiry_days', '30', 'integer', 'Default expiration days for notes'),
('enable_public_pastes', 'true', 'boolean', 'Allow public paste creation'),
('enable_user_registration', 'true', 'boolean', 'Allow user registration'),
('maintenance_mode', 'false', 'boolean', 'Enable maintenance mode');

-- Sample Tags
INSERT IGNORE INTO tags (name, slug) VALUES
('PHP', 'php'),
('JavaScript', 'javascript'),
('Python', 'python'),
('SQL', 'sql'),
('HTML', 'html'),
('CSS', 'css'),
('Bug Report', 'bug-report'),
('Documentation', 'documentation'),
('Configuration', 'configuration'),
('Other', 'other');

-- ============================================
-- CREATE VIEWS (For Common Queries)
-- ============================================

-- View: Public Pastes (for homepage)
CREATE OR REPLACE VIEW public_pastes AS
SELECT 
    id,
    unique_id,
    title,
    character_count,
    line_count,
    view_count,
    user_id,
    created_at,
    (SELECT COUNT(*) FROM comments WHERE note_id = notes.id) as comment_count
FROM notes
WHERE visibility = 'public' AND is_expired = FALSE
ORDER BY created_at DESC;

-- View: User's Pastes
CREATE OR REPLACE VIEW user_pastes AS
SELECT 
    id,
    unique_id,
    title,
    visibility,
    character_count,
    view_count,
    created_at,
    updated_at
FROM notes
ORDER BY created_at DESC;

-- ============================================
-- PROCEDURES (For Common Operations)
-- ============================================

-- Update paste character count
DELIMITER //
CREATE PROCEDURE IF NOT EXISTS update_note_stats(IN note_id INT)
BEGIN
    UPDATE notes
    SET 
        character_count = CHAR_LENGTH(content),
        line_count = (LENGTH(content) - LENGTH(REPLACE(content, '\n', '')) + 1),
        updated_at = CURRENT_TIMESTAMP
    WHERE id = note_id;
END//
DELIMITER ;

-- Increment view count
DELIMITER //
CREATE PROCEDURE IF NOT EXISTS increment_view_count(IN note_id INT)
BEGIN
    UPDATE notes
    SET view_count = view_count + 1
    WHERE id = note_id;
END//
DELIMITER ;

-- Mark expired pastes
DELIMITER //
CREATE PROCEDURE IF NOT EXISTS mark_expired_notes()
BEGIN
    UPDATE notes
    SET is_expired = TRUE
    WHERE expiry IS NOT NULL AND expiry < NOW() AND is_expired = FALSE;
END//
DELIMITER ;

-- ============================================
-- END OF DATABASE STRUCTURE
-- ============================================