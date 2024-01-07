<?php
require_once ('Model/Database1.php');
require_once('Model/UserData5.php');


class  UserDataSet1
{
    protected $_dbHandle, $_dbInstance;

    public function __construct()
    {
        $this->_dbInstance = Database1::getInstance();
        $this->_dbHandle = $this->_dbInstance->getdbConnection();
    }

    // This code is used when someone wants to register themselves, the inserted credentials will be stored in the database
    public function insertUsers($sql, $params)
    {
        // Used to insert into the database, will insert users input from register page.
        $sqlQuery = $sql;
        // echo $sqlQuery;
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->execute($params);
        echo "Rows affected: " . $statement->rowCount() . "<br>";// execute the PDO statement
    }

    public function executeSql($sql, $params = [])
    {
        $statement = $this->_dbHandle->prepare($sql);
        $statement->execute($params);
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    // Adds a new post to the database
    public function addPost($sql, $params)
    {
        $sqlQuery = $sql;
        // echo $sqlQuery;
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->execute($params); // execute the PDO statement
    }


// Checks login credentials against the database
    public function checkLogin($sql)
    {
        // used to check login credentials, compares them against database.
        $sqlQuery = $sql;
        //  echo $sqlQuery; // used for testing..,
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->execute(); // execute the PDO statement

        $dataSet = [];

        $row = $statement->fetch();

        $dataSet[] = new UserData5($row);

        return $dataSet;

    }

    // Fetches posts from the database using an SQL query
    public function fetchPostsBySql($sql)
    {
        $statement = $this->_dbHandle->prepare($sql);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

// Upvotes a post
    public function upvotePost($post_id, $user_id)
    {
        // Check if the user has already upvoted the post
        $sql = "SELECT * FROM user_votes WHERE user_id = :user_id AND post_id = :post_id";
        $stmt = $this->_dbHandle->prepare($sql);
        $stmt->execute([':user_id' => $user_id, ':post_id' => $post_id]);

        if ($stmt->rowCount() > 0) {
            // User has already upvoted the post so returns false
            return false;
        }

        // Update the post upvotes
        $sql = "UPDATE post SET upvotes = upvotes + 1 WHERE id = :post_id";
        $stmt = $this->_dbHandle->prepare($sql);
        $stmt->execute([':post_id' => $post_id]);

        // Record the user's vote
        $sql = "INSERT INTO user_votes (user_id, post_id) VALUES (:user_id, :post_id)";
        $stmt = $this->_dbHandle->prepare($sql);
        $stmt->execute([':user_id' => $user_id, ':post_id' => $post_id]);

        return true;
    }

    // Deletes a post by ID and user ID
    public function deletePostById($post_id, $user_id)
    {
        // Delete associated user_votes records
        $sqlQuery = "DELETE FROM user_votes WHERE post_id = :post_id";
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->bindParam(':post_id', $post_id, PDO::PARAM_INT);
        $statement->execute();

        // Delete the post
        $sqlQuery = "DELETE FROM post WHERE id = :post_id AND user_id = :user_id";
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->bindParam(':post_id', $post_id, PDO::PARAM_INT);
        $statement->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $statement->execute();
    }


    // Method to delete a user from the database
    public function deleteUserAccount($user_id)
    {
        // Delete all associated records from other tables
        // Delete the user's posts
        $sqlQuery = "DELETE FROM post WHERE user_id = :user_id";
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $statement->execute();

        // Delete associated user_votes records
        $sqlQuery = "DELETE FROM user_votes WHERE user_id = :user_id";
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $statement->execute();

        // Delete the user account
        $sqlQuery = "DELETE FROM users4 WHERE id = :user_id";
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $statement->execute();
    }



// Method to fetch all posts created by a given user
    public function fetchPostsByUserId($user_id)
    {
        $sqlQuery = "SELECT * FROM post WHERE user_id = :user_id ORDER BY created_at DESC";
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $statement->execute();
        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = $row;
        }
        return $dataSet;
    }

// Fetch a post by its ID, along with the name of the user who posted it and the category name
    public function fetchPostById($post_id)
    {
        $sql = "SELECT post.*, users4.name, post.category AS category_name 
        FROM post 
        INNER JOIN users4 ON post.user_id = users4.id 
         WHERE post.id = :post_id";
        $stmt = $this->_dbHandle->prepare($sql); // Use _dbHandle instead of C
        $stmt->execute([':post_id' => $post_id]);

        if ($stmt->rowCount() > 0) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        return null;
    }

    // Fetch a user's profile information by their ID
    public function getUserProfile($userId)
    {
        $sql = "SELECT * FROM users4 WHERE id = :id";
        $stmt = $this->_dbHandle->prepare($sql);
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    }
    // Update a user's profile information
    public function updateUserProfile($userId, $name, $email, $password)
    {
        $sql = "UPDATE users4 SET name = :name, email = :email, password = :password WHERE id = :id";
        $stmt = $this->_dbHandle->prepare($sql);
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':password', $password, PDO::PARAM_STR);
        $stmt->execute();
    }


    // Updates the profile picture for a user with the given ID
    public function updateProfilePicture($userId, $photo)
    {
        $sql = "UPDATE users4 SET photo = :photo WHERE id = :id";
        $stmt = $this->_dbHandle->prepare($sql);
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':photo', $photo, PDO::PARAM_STR);
        if (!$stmt->execute()) {
            // There was an error updating the profile picture
            $error = $stmt->errorInfo();
            error_log("Error updating profile picture: " . $error[2]);
            return false;
        }
        return true;
    }

// Sends a post request from a sender to a receiver for a given post ID
    public function sendPostRequest($sender_id, $receiver_id, $post_id)
    {
        $stmt = $this->_dbHandle->prepare("INSERT INTO post_requests (sender_id, receiver_id, post_id, status) VALUES (?, ?, ?, ?)");
        // Default status can be set to 'pending', for example
        $status = 'pending';
        $stmt->bindParam(1, $sender_id, PDO::PARAM_INT);
        $stmt->bindParam(2, $receiver_id, PDO::PARAM_INT);
        $stmt->bindParam(3, $post_id, PDO::PARAM_INT);
        $stmt->bindParam(4, $status, PDO::PARAM_STR);
        $result = $stmt->execute();
        return $result;
    }
// Adds a user as a participant for a given post ID
    public function addPostParticipant($user_id, $post_id)
    {
        $stmt = $this->_dbHandle->prepare("INSERT INTO post_participants (user_id, post_id) VALUES (?, ?)");
        $stmt->bindParam(1, $user_id, PDO::PARAM_INT);
        $stmt->bindParam(2, $post_id, PDO::PARAM_INT);
        $stmt->execute();
    }
// Updates the status of a post request with the given ID
    public function updatePostRequestStatus($status, $request_id)
    {
        $stmt = $this->_dbHandle->prepare("UPDATE post_requests SET status = ? WHERE id = ?");
        $stmt->bindParam(1, $status, PDO::PARAM_STR);
        $stmt->bindParam(2, $request_id, PDO::PARAM_INT);
        $stmt->execute();
    }
// Fetches all pending post requests for a user with the given ID
    public function fetchUserPostRequests($receiver_id)
    {
        $sql = "SELECT post_requests.id, post_requests.sender_id, post_requests.post_id, post_requests.status, users4.name as sender_name, post.title as post_title FROM post_requests INNER JOIN users4 ON post_requests.sender_id = users4.id INNER JOIN post ON post_requests.post_id = post.id WHERE post_requests.receiver_id = ? AND post_requests.status = 'pending'";
        $stmt = $this->_dbHandle->prepare($sql);
        $stmt->bindParam(1, $receiver_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }
// Fetches all participants for a post with the given ID
    public function fetchPostParticipants($post_id)
    {
        $stmt = $this->_dbHandle->prepare("SELECT users4.id, users4.name FROM post_participants INNER JOIN users4 ON post_participants.user_id = users4.id WHERE post_participants.post_id = ?");
        $stmt->bindParam(1, $post_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
// Checks if a post request has already been sent by the given sender for the given post ID
    public function hasRequestBeenSent($sender_id, $post_id)
    {
        $sqlQuery = "SELECT * FROM `post_requests` WHERE `sender_id` = ? AND `post_id` = ?";
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute([$sender_id, $post_id]);

        $result = $statement->fetch();

        return !empty($result);
    }
}
















