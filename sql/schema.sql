CREATE DATABASE taskforce_db
  DEFAULT CHARACTER SET utf8mb4;

USE taskforce_db;

CREATE TABLE users
(
  id               INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
  status           VARCHAR(45)                    NOT NULL, # todo возможно таблица отдельная
  name             VARCHAR(255)                   NOT NULL,
  email            VARCHAR(255)                   NOT NULL UNIQUE,
  password         VARCHAR(64)                    NOT NULL,
  created_at       DATETIME     DEFAULT NOW()     NOT NULL,
  last_action_time DATETIME     DEFAULT NOW()     NOT NULL,

  avatar_name      VARCHAR(255) DEFAULT NULL UNIQUE,
  date_of_birth    DATETIME     DEFAULT NULL,
  phone            VARCHAR(11)  DEFAULT NULL UNIQUE,
  telegram         VARCHAR(64)  DEFAULT NULL UNIQUE,
  about            TEXT         DEFAULT NULL,

  city_id          INT UNSIGNED                   NOT NULL,
  role_id          TINYINT UNSIGNED               NOT NULL,

  FOREIGN KEY (city_id) REFERENCES cities (id) ON DELETE CASCADE
);

CREATE TABLE cities
(
  id    INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
  title VARCHAR(255)                   NOT NULL UNIQUE
);

CREATE TABLE tasks
(
  id           INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
  status       TINYINT UNSIGNED               NOT NULL,
  created_at   DATETIME       DEFAULT NOW()   NOT NULL,
  title        VARCHAR(255)                   NOT NULL,
  description  TEXT                           NOT NULL,
  lat          DECIMAL(10, 8) DEFAULT NULL,             # todo дабл смотреть можно ли 8 цифр после запятой
  lng          DECIMAL(10, 8) DEFAULT NULL,
  price        INT UNSIGNED   DEFAULT NULL,
  deadline     DATETIME       DEFAULT NULL,

  category_id  INT UNSIGNED                   NOT NULL,
  customer_id  INT UNSIGNED                   NOT NULL, # todo проверить что остается в поле при удалении
  city_id      INT UNSIGNED   DEFAULT NULL,
  performer_id INT UNSIGNED   DEFAULT NULL,

  FOREIGN KEY (city_id) REFERENCES cities (id) ON DELETE NO ACTION,
  FOREIGN KEY (category_id) REFERENCES categories (id) ON DELETE RESTRICT,
  FOREIGN KEY (customer_id) REFERENCES users (id) ON DELETE NO ACTION,
  FOREIGN KEY (performer_id) REFERENCES users (id) ON DELETE NO ACTION
);

CREATE TABLE refusal_reasons
(
  id      INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
  comment TEXT DEFAULT NULL,

  task_id INT UNSIGNED                   NOT NULL,
  user_id INT UNSIGNED                   NOT NULL,

  FOREIGN KEY (task_id) REFERENCES tasks (id) ON DELETE CASCADE,
  FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE NO ACTION
);

CREATE TABLE user_reviews
(
  id          INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
  review      TEXT                           NOT NULL,
  rating      TINYINT UNSIGNED               NOT NULL,
  created_at  DATETIME DEFAULT NOW()         NOT NULL,

  reviewer_id INT UNSIGNED                   NOT NULL,
  task_id     INT UNSIGNED                   NOT NULL,

  FOREIGN KEY (reviewer_id) REFERENCES users (id) ON DELETE NO ACTION,
  FOREIGN KEY (task_id) REFERENCES tasks (id) ON DELETE CASCADE
);

CREATE TABLE categories
(
  id    INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
  title VARCHAR(255)                   NOT NULL UNIQUE
);

CREATE TABLE user_specializations
(
  id          INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
  user_id     INT UNSIGNED                   NOT NULL,
  category_id INT UNSIGNED                   NOT NULL,

  FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE,
  FOREIGN KEY (category_id) REFERENCES categories (id) ON DELETE CASCADE
);

CREATE TABLE task_attachments
(
  id              INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
  attachment_path VARCHAR(255)                   NOT NULL UNIQUE,
  task_id         INT UNSIGNED                   NOT NULL,

  FOREIGN KEY (task_id) REFERENCES tasks (id) ON DELETE CASCADE
);

CREATE TABLE task_responses
(
  id         INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
  comment    TEXT         DEFAULT NULL,
  created_at DATETIME     DEFAULT NOW()     NOT NULL,
  price      INT UNSIGNED DEFAULT NULL,

  task_id    INT UNSIGNED                   NOT NULL,
  user_id    INT UNSIGNED                   NOT NULL,

  FOREIGN KEY (task_id) REFERENCES tasks (id) ON DELETE CASCADE,
  FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE
);

# Про чат ничего не нашел
CREATE TABLE messages
(
  id           INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
  message      TEXT                           NOT NULL,
  created_at   DATETIME DEFAULT NOW()         NOT NULL,
  recipient_id INT                            NOT NULL,
  sender_id    INT                            NOT NULL,

  FOREIGN KEY (recipient_id) REFERENCES users (id) ON DELETE CASCADE,
  FOREIGN KEY (sender_id) REFERENCES users (id) ON DELETE CASCADE
);

# Про подписки/избранное ничего не нашел
CREATE TABLE subscriptions
(
  id          INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
  follower_id INT UNSIGNED                   NOT NULL,
  user_id     INT UNSIGNED                   NOT NULL,

  FOREIGN KEY (follower_id) REFERENCES users (id) ON DELETE CASCADE,
  FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE
);

# Про уведомления ничего не нашел

CREATE TABLE user_settings
(
  id                        INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
  contacts_to_customer_only BOOL DEFAULT FALSE,
  user_id                   INT UNSIGNED                   NOT NULL,

  FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE
)
