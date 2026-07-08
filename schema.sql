-- Aura Creator — Full App Schema
-- Run once on your MySQL DB (e.g. nnvrdjjh_auracreator)

CREATE TABLE IF NOT EXISTS aura_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    plan VARCHAR(30) NOT NULL DEFAULT 'free',   -- free, starter, business, pro, agency
    credits INT NOT NULL DEFAULT 3,             -- real credit balance, decremented on each generation
    plan_expires_at DATETIME DEFAULT NULL,
    razorpay_customer_id VARCHAR(100) DEFAULT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_email (email)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS aura_projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    name VARCHAR(150) NOT NULL,
    mode VARCHAR(30) NOT NULL,          -- Website, App, Design Studio, Video, Launch Kit
    prompt TEXT NOT NULL,
    html_content LONGTEXT NOT NULL,     -- the real generated HTML, only ever from a real AI response
    slug VARCHAR(150) DEFAULT NULL,     -- for real public share links
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES aura_users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS aura_plan_payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    plan_key VARCHAR(30) NOT NULL,
    amount DECIMAL(8,2) NOT NULL,
    razorpay_order_id VARCHAR(100) DEFAULT NULL,
    razorpay_payment_id VARCHAR(100) DEFAULT NULL,
    razorpay_signature VARCHAR(255) DEFAULT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'created', -- created, paid, failed
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES aura_users(id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS aura_download_payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    project_id INT NOT NULL,
    download_type VARCHAR(20) NOT NULL,  -- html, zip, apk
    amount DECIMAL(8,2) NOT NULL,
    razorpay_order_id VARCHAR(100) DEFAULT NULL,
    razorpay_payment_id VARCHAR(100) DEFAULT NULL,
    razorpay_signature VARCHAR(255) DEFAULT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'created', -- created, paid, failed
    download_token VARCHAR(64) DEFAULT NULL,  -- one-time real token issued only after payment verified
    downloaded_at DATETIME DEFAULT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES aura_users(id),
    FOREIGN KEY (project_id) REFERENCES aura_projects(id)
) ENGINE=InnoDB;
