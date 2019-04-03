<?php
declare(strict_types = 1);

namespace App\Business;

use App\Data\OrderLineDAO;

class OrderLineService 
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
        $orderLineDAO = new OrderLineDAO();
        $orderLineId  = $orderLineDAO->insert($orderId, $pizzaId, $amount, $unitPrice);
        
        return $orderLineId;
    }
}