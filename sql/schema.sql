CREATE DATABASE taskforce_db
  DEFAULT CHARACTER SET utf8mb4;

USE taskforce_db;

CREATE TABLE cities
(
  id     INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
  title  VARCHAR(255)                            NOT NULL UNIQUE,
  lat    DECIMAL(13, 10)                          NOT NULL,
  `long` DECIMAL(13, 10)                          NOT NULL
);

CREATE TABLE categories
(
  id   INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
  name VARCHAR(255)                            NOT NULL UNIQUE,
  icon VARCHAR(255)                            NOT NULL UNIQUE
);

CREATE TABLE users
(
  id               INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
  status           VARCHAR(45)                             NOT NULL,
  name             VARCHAR(255)                            NOT NULL,
  email            VARCHAR(255)                            NOT NULL UNIQUE,
  password         VARCHAR(255)                            NOT NULL,
  created_at       DATETIME DEFAULT NOW()                  NOT NULL,
  last_action_time DATETIME DEFAULT NOW()                  NOT NULL,

  avatar_name      VARCHAR(255) UNIQUE,
  date_of_birth    DATETIME,
  phone            VARCHAR(11) UNIQUE,
  telegram         VARCHAR(64) UNIQUE,
  about            TEXT,

  city_id          INT UNSIGNED                            NOT NULL,
  role_id          TINYINT UNSIGNED                        NOT NULL,

  FOREIGN KEY (city_id) REFERENCES cities (id) ON DELETE CASCADE
);

CREATE TABLE tasks
(
  id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
  status       TINYINT UNSIGNED DEFAULT 1              NOT NULL,
  created_at   DATETIME         DEFAULT NOW()          NOT NULL,
  title        VARCHAR(255)                            NOT NULL,
  description  TEXT                                    NOT NULL,
  lat          DECIMAL(13, 10),
  lng          DECIMAL(13, 10),
  price        MEDIUMINT UNSIGNED,
  deadline     DATETIME,

  category_id  INT UNSIGNED                            NOT NULL,
  customer_id  INT UNSIGNED                            NOT NULL,
  city_id      INT UNSIGNED,
  performer_id INT UNSIGNED,

  FOREIGN KEY (city_id) REFERENCES cities (id) ON DELETE NO ACTION,
  FOREIGN KEY (category_id) REFERENCES categories (id) ON DELETE RESTRICT,
  FOREIGN KEY (customer_id) REFERENCES users (id) ON DELETE NO ACTION,
  FOREIGN KEY (performer_id) REFERENCES users (id) ON DELETE NO ACTION
);

CREATE TABLE refusal_reasons
(
  id      INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
  comment TEXT,

  task_id INT UNSIGNED                            NOT NULL,
  user_id INT UNSIGNED                            NOT NULL,

  FOREIGN KEY (task_id) REFERENCES tasks (id) ON DELETE CASCADE,
  FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE NO ACTION
);

CREATE TABLE user_reviews
(
  id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
  review      TEXT                                    NOT NULL,
  rating      TINYINT UNSIGNED                        NOT NULL,
  created_at  DATETIME DEFAULT NOW()                  NOT NULL,

  reviewer_id INT UNSIGNED                            NOT NULL,
  task_id     INT UNSIGNED                            NOT NULL,

  FOREIGN KEY (reviewer_id) REFERENCES users (id) ON DELETE NO ACTION,
  FOREIGN KEY (task_id) REFERENCES tasks (id) ON DELETE CASCADE
);

CREATE TABLE user_specializations
(
  id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
  user_id     INT UNSIGNED                            NOT NULL,
  category_id INT UNSIGNED                            NOT NULL,

  FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE,
  FOREIGN KEY (category_id) REFERENCES categories (id) ON DELETE CASCADE
);

CREATE TABLE task_attachments
(
  id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
  attachment_path VARCHAR(255)                            NOT NULL UNIQUE,
  type            TINYINT UNSIGNED                        NOT NULL,
  task_id         INT UNSIGNED                            NOT NULL,

  FOREIGN KEY (task_id) REFERENCES tasks (id) ON DELETE CASCADE
);

CREATE TABLE task_responses
(
  id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
  comment    TEXT,
  created_at DATETIME DEFAULT NOW()                  NOT NULL,
  price      INT UNSIGNED                            NOT NULL,

  task_id    INT UNSIGNED                            NOT NULL,
  user_id    INT UNSIGNED                            NOT NULL,

  FOREIGN KEY (task_id) REFERENCES tasks (id) ON DELETE CASCADE,
  FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE
);

CREATE TABLE user_settings
(
  id                        INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
  contacts_to_customer_only TINYINT(1) UNSIGNED DEFAULT 0,
  user_id                   INT UNSIGNED                            NOT NULL,

  FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE
)
