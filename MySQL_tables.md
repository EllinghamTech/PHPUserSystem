# The MySQL/MariaDB Create Tables
| Table | Purpose |
|---------------|----------------|
| users | Holds the user accounts |
| users_tokens | Holds temporary user tokens (e.g. forgot password token, two-factor auth token) |
| users_permissions | Holds the permissions for users |
| users_limits | Holds the user limits |
| users_preferences | Holds the user preferences |
| users_meta | Holds additional data for the users, etc additional email addresses, contact addresses |
| users_profiles | Holds public profile data for users |
| users_sessions | Holds session data when using DBSessions |

```mysql
CREATE TABLE IF NOT EXISTS users (
  user_id INT(5) PRIMARY KEY AUTO_INCREMENT,
  user_name VARCHAR(60),
  user_password VARCHAR(255),
  user_email VARCHAR(255),
  user_mobile VARCHAR(15),
  user_created BIGINT(10),
  user_active TINYINT(1) DEFAULT 1,
  user_twofactor_enabled TINYINT(1) DEFAULT 0, -- Future Use (planned: two factor auth)
  user_flags BIGINT(1) DEFAULT 0,
  
  UNIQUE (user_name),
  UNIQUE (user_email),
  INDEX iuser_email (user_email, user_id),
  INDEX iuser_name (user_name, user_id)
);

CREATE TABLE IF NOT EXISTS users_tokens (
  token VARCHAR(128) PRIMARY KEY,
  user_id INT(5) NOT NULL,
  token_type VARCHAR(30),
  token_expires BIGINT(10),
  token_expired TINYINT(1) DEFAULT 0,

  INDEX iuser_token (token, user_id),
  INDEX itoken_for_user (user_id, token),

  CONSTRAINT fk_users_tokens_on_users FOREIGN KEY (user_id) REFERENCES users (user_id)
    ON UPDATE CASCADE
    ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS users_permissions (
  user_id INT(5),
  permission_name VARCHAR(100),
  permission_value SMALLINT(1),
  permission_expires BIGINT(5), -- Future use (planned: permission expiry)
  permission_expires_to SMALLINT(1), -- Future use (planned: permission expiry)

  PRIMARY KEY (user_id, permission_name),

  CONSTRAINT fk_users_permissions_on_users FOREIGN KEY (user_id) REFERENCES users (user_id)
    ON UPDATE CASCADE
    ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS users_limits (
  user_id INT(5),
  limit_name VARCHAR(100),
  limit_value BIGINT(1),
  limit_refresh_value BIGINT(1),
  limit_refresh_when BIGINT(10),
  limit_refresh_interval INT(5),
  limit_refresh_interval_unit VARCHAR(30),

  PRIMARY KEY (user_id, limit_name),

  CONSTRAINT fk_user_id_on_users FOREIGN KEY (user_id) REFERENCES users (user_id)
    ON UPDATE CASCADE
    ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS users_preferences (
  user_id INT(5),
  preference_name VARCHAR(100),
  preference_value VARCHAR(100),

  PRIMARY KEY (user_id, preference_name),

  CONSTRAINT fk_users_preferences_on_users FOREIGN KEY (user_id) REFERENCES users (user_id)
    ON UPDATE CASCADE
    ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS users_meta (
  user_id INT(5),
  meta_name VARCHAR(100),
  meta_number TINYINT(1) DEFAULT 0, -- In this case, user_meta_name can be "email" but to hold multiple emails this field can be used
  meta_value VARCHAR(255),
  meta_sensitive TINYINT(1) DEFAULT 0, -- Future Use

  PRIMARY KEY (user_id, meta_name, meta_number),

  CONSTRAINT fk_users_meta_on_users FOREIGN KEY (user_id) REFERENCES users (user_id)
    ON UPDATE CASCADE
    ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS users_profiles (
    user_id INT(5) PRIMARY KEY,
    profile_id VARCHAR(50) UNIQUE,
    display_name VARCHAR(120),
    full_name VARCHAR(120),
    profile_summary TEXT,
    profile_image VARCHAR(120),
    
    CONSTRAINT fk_users_profiles_on_users FOREIGN KEY (user_id) REFERENCES users (user_id)
      ON UPDATE CASCADE
      ON DELETE CASCADE
);

-- Used with DB Sessions
CREATE TABLE IF NOT EXISTS users_sessions (
  session_id VARCHAR(128) PRIMARY KEY,
  user_id INT(5),
  session_expires BIGINT(10),
  session_active TINYINT(1) DEFAULT 1,

  CONSTRAINT fk_users_sessions_on_users FOREIGN KEY (user_id) REFERENCES users (user_id)
    ON UPDATE CASCADE
    ON DELETE CASCADE
);
```
