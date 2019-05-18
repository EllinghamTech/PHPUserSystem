CREATE TABLE IF NOT EXISTS users (
   user_id INTEGER PRIMARY KEY AUTOINCREMENT,
   user_name TEXT,
   user_password TEXT,
   user_email TEXT,
   user_mobile TEXT,
   user_created INTEGER,
   user_active INTEGER DEFAULT 1,
   user_twofactor_enabled INTEGER DEFAULT 0, -- Future Use (planned: two factor auth)
   user_flags INTEGER DEFAULT 0,

   UNIQUE (user_name),
   UNIQUE (user_email)
);

CREATE INDEX IF NOT EXISTS user_email ON users (user_email, user_id);
CREATE INDEX IF NOT EXISTS user_name ON users(user_name, user_id);

CREATE TABLE IF NOT EXISTS users_tokens (
  token TEXT PRIMARY KEY,
  user_id INTEGER NOT NULL,
  token_type TEXT,
  token_expires INTEGER,
  token_expired INTEGER DEFAULT 0,

  CONSTRAINT fk_users_tokens_on_users FOREIGN KEY (user_id) REFERENCES users (user_id)
    ON UPDATE CASCADE
    ON DELETE CASCADE
);

CREATE INDEX IF NOT EXISTS user_token ON users_tokens (token, user_id);
CREATE INDEX IF NOT EXISTS tokens_for_user ON users_tokens (user_id, token_type);

CREATE TABLE IF NOT EXISTS users_permissions (
  user_id INTEGER,
  permission_name TEXT,
  permission_value INTEGER,
  permission_expires INTEGER, -- Future use (planned: permission expiry)
  permission_expires_to INTEGER, -- Future use (planned: permission expiry)

  PRIMARY KEY (user_id, permission_name),

  CONSTRAINT fk_users_permissions_on_users FOREIGN KEY (user_id) REFERENCES users (user_id)
    ON UPDATE CASCADE
    ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS users_limits (
  user_id INTEGER,
  limit_name TEXT,
  limit_value INTEGER,
  limit_refresh_value INTEGER,
  limit_refresh_when INTEGER,
  limit_refresh_interval INTEGER,
  limit_refresh_interval_unit TEXT,

  PRIMARY KEY (user_id, limit_name),

  CONSTRAINT fk_user_id_on_users FOREIGN KEY (user_id) REFERENCES users (user_id)
    ON UPDATE CASCADE
    ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS users_preferences (
  user_id INTEGER,
  preference_name TEXT,
  preference_value TEXT,

  PRIMARY KEY (user_id, preference_name),

  CONSTRAINT fk_users_preferences_on_users FOREIGN KEY (user_id) REFERENCES users (user_id)
    ON UPDATE CASCADE
    ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS users_meta (
  user_id INTEGER,
  meta_name TEXT,
  meta_number INTEGER DEFAULT 0, -- In this case, user_meta_name can be "email" but to hold multiple emails this field can be used
  meta_value TEXT,
  meta_sensitive INTEGER DEFAULT 0, -- Future Use

  PRIMARY KEY (user_id, meta_name, meta_number),

  CONSTRAINT fk_users_meta_on_users FOREIGN KEY (user_id) REFERENCES users (user_id)
    ON UPDATE CASCADE
    ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS users_profiles (
    user_id INTEGER PRIMARY KEY,
    profile_id TEXT UNIQUE,
    display_name TEXT,
    full_name TEXT,
    profile_summary TEXT,
    profile_image TEXT,

    CONSTRAINT fk_users_profiles_on_users FOREIGN KEY (user_id) REFERENCES users (user_id)
      ON UPDATE CASCADE
      ON DELETE CASCADE
);

-- Used with DB Sessions
CREATE TABLE IF NOT EXISTS users_sessions (
  session_id TEXT PRIMARY KEY,
  user_id INTEGER,
  session_expires INTEGER,
  session_active INTEGER DEFAULT 1,

  CONSTRAINT fk_users_sessions_on_users FOREIGN KEY (user_id) REFERENCES users (user_id)
    ON UPDATE CASCADE
    ON DELETE CASCADE
);
