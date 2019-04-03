<?php
declare(strict_types = 1);

namespace App\Data;

class OrderLineDAO
{
    /**
     * Insert a new order line 
     * 
     * @param int $orderId
     * @param int $pizzaId
     * @param int $amount
     * @param float $unitPrice
     * 
     * @return int
     */
    public function insert(
        int $orderId,
        int $pizzaId,
        int $amount,
        float $unitPrice
    ):int {
        // Generate the query
        $sql = "INSERT INTO order_lines(order_id, pizza_id, amount, unit_price)
                VALUES (:orderId, :pizzaId, :amount, :unitPrice)";
        
        // Open the connection
        $pdo = DbConfig::getPdo();
        
        // Execute the query
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':orderId'   => $orderId,
            ':pizzaId'   => $pizzaId,
            ':amount'    => $amount,
            ':unitPrice' => $unitPrice
        ]);
        
        // Get the id of the order line
        $orderLineId = intval($pdo->lastInsertId());
        
        // Close the connection
        $pdo = null;
        
        // Return the id
        return $orderLineId;       
    }
}