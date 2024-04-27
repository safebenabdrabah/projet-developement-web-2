<?php
require_once('connection.php');
// Article class
class User {
    public $id;
    public $email;
    public $username;
    public $password;

    public function __construct($id, $email, $username, $password) {
        $this->id = $id;
        $this->email = $email;
        $this->username = $username;
        $this->password = $password;
    }
}

// User management class
class UserManagement {
    private $conn; // Connection passed by session

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Create a new user
    public function createUser($username, $password, $email) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("INSERT INTO user (username_user, password_user, email_user) VALUES (:username, :password, :email)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
    }

   // Retrieve a user by ID
public function getUserById($user_id) {
    $sql = "SELECT * FROM user WHERE id_user = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindValue(1, $user_id, PDO::PARAM_INT); // Bind user_id as integer
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if user exists
    if ($result === false) {
        return null; // Return null if user not found
    }

    // Ensure the result contains the expected keys
    if (!isset($result['id_user'], $result['username_user'], $result['email_user'])) {
        return null; // Return null if user information is incomplete
    }

    return $result;
}


    // Update a user
    // Update a user
public function updateUser($user_id, $username, $password, $email) {
    $stmt = $this->conn->prepare("UPDATE user SET username_user = ?, password_user = ?, email_user = ? WHERE id_user = ?");
    $stmt->bindParam(1, $username);
    $stmt->bindParam(2, $password);
    $stmt->bindParam(3, $email);
    $stmt->bindParam(4, $user_id);
    $stmt->execute();
}

    // Delete a user
    public function deleteUser($user_id) {
        $stmt = $this->conn->prepare("DELETE FROM user WHERE id_user = ?");
        $stmt->bindValue(1, $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $stmt->closeCursor(); // Close cursor to release resources
    }

    // Fetch all users
    public function getAllUsers() {
        $users = array();
        $stmt = $this->conn->prepare("SELECT * FROM user");
        $stmt->execute();
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $user = new User($row['id_user'], $row['email_user'], $row['username_user'], $row['password_user']);
            $users[] = $user;
        }
    
        return $users;
    }
    public function getUserByUsernameAndPassword($username, $password) {
        $stmt = $this->conn->prepare("SELECT * FROM user WHERE username_user = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Check if the user exists and the provided password matches
        if ($user && $user['password_user'] === $password) {
            // Password verification succeeded, return the user
            return $user;
        } else {
            // Either the user does not exist or the password is incorrect
            return null;
        }
    }
    
    
    
    public function getArticlesByUserId($user_id) {
        $articles = array();
        $stmt = $this->conn->prepare("SELECT * FROM articles WHERE author_id = ?");
        $stmt->execute([$user_id]);
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $articles[] = $row;
        }
    
        return $articles;
    }    
    
}
?>
