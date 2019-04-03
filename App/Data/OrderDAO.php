<?php
declare(strict_types = 1);

namespace App\Data;

use App\Entities\Order;
use App\Exceptions\InvalidStatusException;

use DateTime;
use PDO;

class OrderDAO
{
    /**
     * Insert a new order
     * 
     * @param int|null $userId
     * @param int|null $deliveryAddressId
     * @param DateTime $deliveryDateTime
     * @param string $remarks
     * @param string $status
     * 
     * @throw InvalidStatusException
     * @return int
     */
    public function insert(
        ?int $userId,
        ?int $deliveryAddressId,
        DateTime $deliveryDateTime,
        string $remarks,
        string $status
    ):int {
        
        if (!in_array($status, Order::VALID_STATUSES)) {
            throw new InvalidStatusException();
        }
        
        // Generate the query
        $sql = "INSERT INTO `orders` (user_id, delivery_address_id, delivery_date_time, remarks, status)
                VALUES (:userId, :deliveryAddressId, :deliveryDateTime, :remarks, :status)";
        
        // Open the connection
        $pdo = DbConfig::getPdo();
        
        // Execute the query
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':userId'            => $userId,
            ':deliveryAddressId' => $deliveryAddressId,
            ':deliveryDateTime'  => $deliveryDateTime->format('Y-m-d H:i:s'),
            ':remarks'           => $remarks,
            ':status'            => $status
        ]);
        
        // Get the last inserted id
        $orderId = intVal($pdo->lastInsertId());
        
        // Close the connection
        $pdo = null;
        
        // Return the id
        return $orderId;
    }
}