<?php
declare(strict_types = 1);

namespace App\Data;

use App\Entities\User;
use App\Entities\City;

use PDO;

class UserDAO
{
    /**
     * Get a user specified by it's mail
     * 
     * @param string $mail
     * 
     * @return User|null
     */
    public function getByMail(string $mail):?User
    {
       $user = null;
       
       // Generate the query
       $sql = "SELECT u.id, u.lastname, u.firstname, u.address, u.city_id, u.phone, u.mail, u.password, u.remarks, u.promotions,
                   c.zipcode as city_zipcode, c.name AS city_name, c.delivery AS city_delivery
               FROM users u
               JOIN cities c ON u.city_id = c.id
               WHERE mail = :mail";
       
       // Open the connection
       $pdo = DbConfig::getPdo();
       
       // Execute the query
       $stmt = $pdo->prepare($sql);
       $stmt->execute([':mail' => $mail]);
       
       // Get the information of the user
       if ($stmt->rowCount() > 0) {
           $row  = $stmt->fetch(PDO::FETCH_ASSOC);
           $user = $this->createFromDbRow($row);
       }
       
       // Close the db connection
       $pdo = null;
               
       // Return the result
       return $user;
    }
    
    /**
     * Get a user specified by it's id
     * 
     * @param int $id
     * 
     * @return User|null
     */
    public function getById(int $id):?User
    {
        $user = null;
        
       // Generate the query
       $sql = "SELECT u.id, u.lastname, u.firstname, u.address, u.city_id, u.phone, u.mail, u.password, u.remarks, u.promotions,
                   c.zipcode as city_zipcode, c.name AS city_name, c.delivery AS city_delivery
               FROM users u
               JOIN cities c ON u.city_id = c.id
               WHERE u.id = :id";
       
       // Open the connection
       $pdo = DbConfig::getPdo();
       
       // Execute the query
       $stmt = $pdo->prepare($sql);
       $stmt->execute([':id' => $id]);
       
       // Get the information of the user
       if ($stmt->rowCount() > 0) {
           $row  = $stmt->fetch(PDO::FETCH_ASSOC);
           $user = $this->createFromDbRow($row);
       }
       
       // Close the db connection
       $pdo = null;
               
       // Return the result
       return $user;
    }
    
    /**
     * Insert a new user into the database
     * 
     * @param string $lastname
     * @param string $firstname
     * @param string $address
     * @param int $cityId
     * @param string $phone
     * @param string $mail
     * @param string $passwordHash
     * @param string $remarks
     * @param bool $promotions
     * 
     * @return int
     */
    public function insert(
        string $lastname,
        string $firstname,
        string $address,
        int $cityId,
        string $phone,
        string $mail,
        string $passwordHash,
        string $remarks = '',
        bool $promotions = false
    ):int {
        // Generate the query
        $sql = "INSERT INTO users(lastname, firstname, address, city_id, phone, mail, password, remarks, promotions)
                VALUES (:lastname, :firstname, :address, :cityId, :phone, :mail, :password, :remarks, :promotions)";
        
        // Open the connection
        $pdo = DbConfig::getPdo();
        
        // Execute the query
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':lastname'   => $lastname,
            ':firstname'  => $firstname,
            ':address'    => $address,
            ':cityId'     => $cityId,
            ':phone'      => $phone,
            ':mail'       => $mail,
            ':password'   => $passwordHash,
            ':remarks'    => $remarks,
            ':promotions' => intval($promotions)
        ]);
        
        // Get the user Id
        $userId = intVal($pdo->lastInsertId());
        
        // Close the connection
        $pdo = null;
        
        // Return the id
        return $userId;
    }
    
    /**
     * Create a user object from a db row
     * 
     * @param array $row
     * 
     * @return User|null
     */
    private function createFromDbRow(array $row):?User
    {       
        $user = null;
        
        if (
            array_key_exists('id', $row)
            && array_key_exists('lastname', $row)
            && array_key_exists('firstname', $row)
            && array_key_exists('address', $row)
            && array_key_exists('city_id', $row)
            && array_key_exists('phone', $row)
            && array_key_exists('mail', $row)
            && array_key_exists('remarks', $row)
            && array_key_exists('promotions', $row)
            && array_key_exists('city_zipcode', $row)
            && array_key_exists('city_name', $row)
            && array_key_exists('city_delivery', $row)             
        ) {
            $city = City::create(
                intVal($row['city_id']),
                $row['city_zipcode'],
                $row['city_name'],
                boolval($row['city_delivery'])
            );
            
            $user = User::create(
                $row['id'],
                $row['lastname'],
                $row['firstname'],
                $row['address'],
                $city,
                $row['phone'],
                $row['password'],
                $row['remarks'],
                boolval($row['promotions'])
            );
        }
        
        return $user;
    }
}