<?php
/**
 * Database Helper Class for Club Activities
 * 
 * This class provides methods for database operations
 * using PDO for MySQL connections.
 */
class DatabaseHelper {
    private $host;
    private $dbName;
    private $username;
    private $password;
    private $pdo;

    /**
     * Constructor
     */
    public function __construct($host, $dbName, $username, $password) {
        $this->host = $host;
        $this->dbName = $dbName;
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * Get PDO connection
     */
    public function getPDO() {
        if (!$this->pdo) {
            // Create initial connection to MySQL server
            $this->pdo = new PDO("mysql:host={$this->host};charset=utf8mb4", 
                                $this->username, 
                                $this->password);

            // Set error mode to exceptions
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Create database if it doesn't exist
            $this->pdo->exec("CREATE DATABASE IF NOT EXISTS `{$this->dbName}`");
            $this->pdo->exec("USE `{$this->dbName}`");
        }

        return $this->pdo;
    }

    /**
     * Create club activities table if it doesn't exist and populate with sample data if empty
     */
    public function createAndPopulateClubActivitiesTable() {
        // Create club activities table if it doesn't exist
        $this->getPDO()->exec("CREATE TABLE IF NOT EXISTS `club_activities` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `title` VARCHAR(100) NOT NULL,
            `club` VARCHAR(50) NOT NULL,
            `content` TEXT NOT NULL,
            `datetime` DATETIME NOT NULL,
            `image_url` VARCHAR(255) DEFAULT NULL,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        // Create comments table if it doesn't exist
        $this->getPDO()->exec("CREATE TABLE IF NOT EXISTS `activity_comments` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `activity_id` INT NOT NULL,
            `name` VARCHAR(100) NOT NULL,
            `comment` TEXT NOT NULL,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (`activity_id`) REFERENCES `club_activities`(`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        // Check if the activities table is empty
        $stmt = $this->getPDO()->query("SELECT COUNT(*) FROM `club_activities`");
        $count = $stmt->fetchColumn();

        // If table is empty, insert sample data
        if ($count == 0) {
            // Sample activities data based on the clubs shown in your HTML
            $sampleActivities = [
                [
                    'title' => 'Paint & Sip Night',
                    'club' => 'Art Club',
                    'content' => 'Join us for painting and mocktails.',
                    'datetime' => '2025-04-10 19:00:00',
                    'image_url' => 'img/art-club.jpg'
                ],
                [
                    'title' => 'Star Gazing Night',
                    'club' => 'Astrology Club',
                    'content' => 'Explore the constellations through telescopes and apps.',
                    'datetime' => '2025-04-12 20:30:00',
                    'image_url' => 'img/astorology-club.jpg'
                ],
                [
                    'title' => 'Book Exchange Fair',
                    'club' => 'Book Club',
                    'content' => 'Swap your favorite reads and discover new ones.',
                    'datetime' => '2025-04-15 17:00:00',
                    'image_url' => 'img/book-club.jpg'
                ],
                [
                    'title' => 'Classic Movie Marathon',
                    'club' => 'Movie Club',
                    'content' => 'Enjoy back-to-back screenings with popcorn and trivia.',
                    'datetime' => '2025-04-18 18:00:00',
                    'image_url' => 'img/movie-club.jpg'
                ],
                [
                    'title' => 'Open Mic Night',
                    'club' => 'Music Club',
                    'content' => 'Bring your instruments and jam with fellow musicians.',
                    'datetime' => '2025-04-20 19:00:00',
                    'image_url' => 'img/music-club.jpg'
                ],
                [
                    'title' => 'Build a Bot Workshop',
                    'club' => 'Robotics Club',
                    'content' => 'Learn to assemble and program your first robot!',
                    'datetime' => '2025-04-22 16:00:00',
                    'image_url' => 'img/rocotics-club.jpg'
                ],
                [
                    'title' => 'Spring Sports Day',
                    'club' => 'Sports Club',
                    'content' => 'Compete in fun team games and win cool prizes!',
                    'datetime' => '2025-04-25 09:00:00',
                    'image_url' => 'img/sports-club.jpg'
                ]
            ];

            // Prepare insert statement
            $stmt = $this->getPDO()->prepare("INSERT INTO `club_activities` (`title`, `club`, `content`, `datetime`, `image_url`) VALUES (?, ?, ?, ?, ?)");

            // Insert each activity
            foreach ($sampleActivities as $activity) {
                $stmt->execute([
                    $activity['title'], 
                    $activity['club'], 
                    $activity['content'], 
                    $activity['datetime'], 
                    $activity['image_url']
                ]);
            }
        }
    }

    /**
     * Get all club activities with optional filtering, sorting, and pagination
     */
    public function getActivities($searchTerm = '', $club = '', $sortBy = 'newest', $page = 1, $limit = 10) {
        // Make sure the table exists and has data
        $this->createAndPopulateClubActivitiesTable();

        // Calculate offset for pagination
        $offset = ($page - 1) * $limit;

        // Build the base SQL query
        $sql = "SELECT * FROM `club_activities`";
        $params = [];
        $whereConditions = [];

        // Add search term condition if provided
        if (!empty($searchTerm)) {
            $whereConditions[] = "`title` LIKE ? OR `content` LIKE ?";
            $params[] = "%$searchTerm%";
            $params[] = "%$searchTerm%";
        }

        // Add club filter if provided
        if (!empty($club) && $club !== 'All Clubs') {
            $whereConditions[] = "`club` = ?";
            $params[] = $club;
        }

        // Combine WHERE conditions if any
        if (!empty($whereConditions)) {
            $sql .= " WHERE " . implode(" AND ", $whereConditions);
        }

        // Add ORDER BY clause based on sort parameter
        if ($sortBy === 'oldest') {
            $sql .= " ORDER BY `datetime` ASC";
        } else {
            $sql .= " ORDER BY `datetime` DESC"; // Default to newest
        }

        // Get total count for pagination without limit
        $countSql = str_replace("SELECT *", "SELECT COUNT(*)", $sql);
        $countStmt = $this->getPDO()->prepare($countSql);
        $countStmt->execute($params);
        $totalCount = $countStmt->fetchColumn();

        // Add LIMIT clause for pagination
        $sql .= " LIMIT ?, ?";
        $params[] = (int)$offset;
        $params[] = (int)$limit;

        // Execute the query
        $stmt = $this->getPDO()->prepare($sql);
        $stmt->execute($params);
        $activities = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Return activities with pagination metadata
        return [
            'total' => $totalCount,
            'page' => $page,
            'limit' => $limit,
            'totalPages' => ceil($totalCount / $limit),
            'activities' => $activities
        ];
    }

    /**
     * Get a single activity by ID
     */
    public function getActivity($id) {
        $stmt = $this->getPDO()->prepare("SELECT * FROM `club_activities` WHERE `id` = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Create a new club activity
     */
    public function createActivity($title, $club, $content, $datetime, $imageUrl = null) {
        // Make sure the tables exist
        $this->createAndPopulateClubActivitiesTable();

        // Prepare and execute the insert statement
        $stmt = $this->getPDO()->prepare("INSERT INTO `club_activities` (`title`, `club`, `content`, `datetime`, `image_url`) VALUES (?, ?, ?, ?, ?)");

        if ($stmt->execute([$title, $club, $content, $datetime, $imageUrl])) {
            return $this->getPDO()->lastInsertId();
        }

        return false;
    }

    /**
     * Update an existing club activity
     */
    public function updateActivity($id, $title, $club, $content, $datetime, $imageUrl = null) {
        // If image URL is null, don't update that field
        if ($imageUrl === null) {
            $stmt = $this->getPDO()->prepare("UPDATE `club_activities` SET `title` = ?, `club` = ?, `content` = ?, `datetime` = ? WHERE `id` = ?");
            return $stmt->execute([$title, $club, $content, $datetime, $id]);
        } else {
            $stmt = $this->getPDO()->prepare("UPDATE `club_activities` SET `title` = ?, `club` = ?, `content` = ?, `datetime` = ?, `image_url` = ? WHERE `id` = ?");
            return $stmt->execute([$title, $club, $content, $datetime, $imageUrl, $id]);
        }
    }

    /**
     * Delete a club activity
     */
    public function deleteActivity($id) {
        $stmt = $this->getPDO()->prepare("DELETE FROM `club_activities` WHERE `id` = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Get comments for an activity
     */
    public function getComments($activityId) {
        $stmt = $this->getPDO()->prepare("SELECT * FROM `activity_comments` WHERE `activity_id` = ? ORDER BY `created_at` DESC");
        $stmt->execute([$activityId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Add a comment to an activity
     */
    public function addComment($activityId, $name, $comment) {
        $stmt = $this->getPDO()->prepare("INSERT INTO `activity_comments` (`activity_id`, `name`, `comment`) VALUES (?, ?, ?)");

        if ($stmt->execute([$activityId, $name, $comment])) {
            return $this->getPDO()->lastInsertId();
        }

        return false;
    }

    /**
     * Delete a comment
     */
    public function deleteComment($commentId) {
        $stmt = $this->getPDO()->prepare("DELETE FROM `activity_comments` WHERE `id` = ?");
        return $stmt->execute([$commentId]);
    }
}
?>