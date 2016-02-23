CREATE TABLE products(
  id_products INT(11) PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(45) NOT NULL,
  cost DECIMAL(10, 2) UNSIGNED NOT NULL,
  description TEXT,
  img_url TEXT
);

CREATE INDEX idx_products_cost
ON products(cost);
