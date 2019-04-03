<?php
declare(strict_types = 1);

namespace App\Data;

use App\Entities\DeliveryAddress;

use PDO;

class DeliveryAddressDAO
{
    /**
     * Insert a delivery address into the database
     * 
     * @param string $lastname
     * @param string $firstname
     * @param string $address
     * @param int $cityId
     * @param string $phone
     * 
     * @return int
     */
    public function insert(
        string $lastname,
        string $firstname,
        string $address,
        int $cityId,
        string $phone
    ) { 
        // Generate the query
        $sql = "INSERT INTO delivery_address(lastname, firstname, address, city_id, phone)
                VALUES (:lastname, :firstname, :address, :cityId, :phone)";
        
        // Open the connection
        $pdo = DbConfig::getPdo();
        
        // Execute the query
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':lastname'  => $lastname,
            ':firstname' => $firstname,
            ':address'   => $address,
            ':cityId'    => $cityId,
            ':phone'     => $phone
        ]);
        
        // Get the last inserted id
        $deliveryAddressId = intVal($pdo->lastInsertId());
        
        // Close the connection
        $pdo = null;
        
        // Return the id
        return $deliveryAddressId;
    }
}