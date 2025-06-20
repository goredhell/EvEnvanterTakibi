CREATE TABLE konumlar (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ad VARCHAR(255) NOT NULL,
    ebeveyn_id INT DEFAULT NULL,
    token VARCHAR(64) NOT NULL UNIQUE,
    FOREIGN KEY (ebeveyn_id) REFERENCES konumlar(id)
);

CREATE TABLE urunler (
    id INT AUTO_INCREMENT PRIMARY KEY,
    konum_id INT,
    ad VARCHAR(255) NOT NULL,
    aciklama TEXT,
    FOREIGN KEY (konum_id) REFERENCES konumlar(id)
);

CREATE TABLE kullanicilar (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kullanici_adi VARCHAR(50) NOT NULL UNIQUE,
    sifre_hash VARCHAR(255) NOT NULL,
    admin BOOLEAN DEFAULT FALSE
);

-- Şifre: admin123
INSERT INTO kullanicilar (kullanici_adi, sifre_hash, admin)
VALUES ('admin', '$2y$10$vT6V1Chx4iQclR3tMxZPReCyVElWk8rp0EoJgr5g9F0Oa6LtKYu2S', TRUE);
