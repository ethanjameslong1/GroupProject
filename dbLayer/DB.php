<?php
define("DB_HOST", "sql200.infinityfree.com");
define("DB_NAME", "if0_39264781_userdb");
define("DB_CHARSET", "utf8mb4");
define("DB_USER", "if0_39264781");
define("DB_PASSWORD", "RVmLRvAbI828n");

class DB
{
    //Connect to Database
    public $error = "";
    private $pdo = null;
    private $stmt = null;
    function __construct()
    {
        try {
            // This connects to the local SQLite file, which is correct for your setup
            $db_file_path = __DIR__ . '/users.db';
            $this->pdo = new PDO(
                "sqlite:" . $db_file_path,
                null,
                null,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );

            // Create 'users' table if it doesn't exist
            $createUsersTableSQL = "
            CREATE TABLE IF NOT EXISTS users (
            user_id INTEGER PRIMARY KEY AUTOINCREMENT,
            username TEXT NOT NULL UNIQUE,
            email TEXT NOT NULL UNIQUE,
            hashed_password TEXT NOT NULL,
            created_at TEXT DEFAULT CURRENT_TIMESTAMP,
            access_level INTEGER DEFAULT 1,
            last_login TEXT
            );
            ";
            $this->pdo->exec($createUsersTableSQL);
            // --- ADD THIS PART ---
            // Create 'weight_log' table if it doesn't exist
            $createWeightLogTableSQL = "
            CREATE TABLE IF NOT EXISTS weight_log (
            weight_id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_ID INTEGER NOT NULL,
            weight REAL NOT NULL,
            log_date TEXT DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_ID) REFERENCES users(user_id) ON DELETE CASCADE
            );
            ";
            $this->pdo->exec($createWeightLogTableSQL);
            // --- END OF ADDED PART ---
            // --- ADDED THIS PART ---
            // Create 'meal' table if it doesn't exist
            $createMealTableSQL = "
            CREATE TABLE IF NOT EXISTS meal (
            meal_ID INTEGER PRIMARY KEY AUTOINCREMENT,
            user_ID INTEGER NOT NULL,
            name TEXT NOT NULL,
            mealType TEXT NOT NULL,
            calories INTEGER NOT NULL,
            timeEaten TEXT DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_ID) REFERENCES users(user_id) ON DELETE CASCADE
            );
            ";
            $this->pdo->exec($createMealTableSQL);
            // --- END OF ADDED PART ---

            $stmt = $this->pdo->query("SELECT COUNT(*) FROM users");
            $userCount = $stmt->fetchColumn();

            if ($userCount == 0) {
                $commonHashedPassword = '$2y$10$Sh0Dpt.51E3.WiVUziv0jeV3JOJHSDJ0jxAptWiNTG1teERFZxKuW';
                $currentTimestamp = date('Y-m-d H:i:s');
                $insertDataSQL = "
                INSERT INTO users (username, email, hashed_password, created_at, access_level, last_login) VALUES
                (:username_admin, :email_admin, :password_admin, :created_at_admin, :access_level_admin, :last_login_admin),
                (:username_user, :email_user, :password_user, :created_at_user, :access_level_user, :last_login_user);
                ";
                $stmt = $this->pdo->prepare($insertDataSQL);
                $stmt->execute([
                    ':username_admin' => 'admin',
                    ':email_admin' => 'admin@example.com',
                    ':password_admin' => $commonHashedPassword,
                    ':created_at_admin' => $currentTimestamp,
                    ':access_level_admin' => 3, // Access level 3 for admin
                    ':last_login_admin' => null,

                    ':username_user' => 'user',
                    ':email_user' => 'user@example.com', // Placeholder email
                    ':password_user' => $commonHashedPassword,
                    ':created_at_user' => $currentTimestamp,
                    ':access_level_user' => 1, // Access level 1 for user
                    ':last_login_user' => null,
                ]);
            }
        } catch (Exception $e) {
            echo "Error connecting <br>" . $e->getMessage();
            return null;
        }
    }

    //Closes Connection
    function __destruct()
    {
        if ($this->stmt !== null) {
            $this->stmt = null;
        }
        if ($this->pdo !== null) {
            $this->pdo = null;
        }
    }

    //Runs a select query
    function select($sql, $data = null)
    {
        try {
            $this->stmt = $this->pdo->prepare($sql);
            $this->stmt->execute($data);
            return $this->stmt->fetchAll();
        } catch (Exception $e) {
            echo $sql . "<br>" . $e->getMessage();
            return null;
        }
    }

    public function getPDO()
    {
        return $this->pdo;
    }
}

$_DB = new DB();
