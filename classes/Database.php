<?php
namespace AzurWeb;

use PDO;
use PDOException;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class Database {
    private $connection;
    private $logger;

    public function __construct($host, $dbname, $user, $pass) {
        $this->logger = new Logger('database_logger');
        $this->logger->pushHandler(new StreamHandler(__DIR__ . '/../logs/database.log', Logger::ERROR));

        try {
            $this->connection = new PDO("mysql:host={$host};dbname={$dbname};charset=utf8", $user, $pass);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->logger->error('Erreur de connexion à la base de données : ' . $e->getMessage());
            throw new \Exception('Erreur de connexion à la base de données');
        }
    }

    public function getConnection() {
        return $this->connection;
    }

    public function query($sql, $params = []) {
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            $this->logger->error('Erreur lors de l\'exécution de la requête : ' . $e->getMessage());
            throw new \Exception('Erreur lors de l\'exécution de la requête');
        }
    }

    public function getUserByUsername($username) {
        $query = "SELECT * FROM admin WHERE username = :username";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    

    public function getProjects() {
        try {
            $stmt = $this->connection->prepare("SELECT * FROM projets");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->logger->error('Erreur lors de la récupération des projets : ' . $e->getMessage());
            return [];
        }
    }
}
