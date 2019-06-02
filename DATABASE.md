# Database Requirements
The PHP User System requires an SQLite or MySQL/MariaDB database with the following tables:

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

You can find the SQL `CREATE TABLE` queries in the `sql/` directory. 
