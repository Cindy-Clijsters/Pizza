<?php
declare(strict_types = 1);

namespace App\Entities;

class OrderLine
{
    private $id;
    private $order;
    private $pizza;
    private $amount;
    private $unitPrice;
    
    private static $idMap = [];
    
    /**
     * Constuctor function
     * 
     * @param int $id
     * @param Order $order
     * @param Pizza $pizza
     * @param int $amount
     * @param float $unitPrice
     * 
     * @return void
     */
    private function __construct(
        int $id,
        Order $order,
        Pizza $pizza,
        int $amount,
        float $unitPrice
    ){
        $this->id        = $id;
        $this->order     = $order;
        $this->pizza     = $pizza;
        $this->amount    = $amount;
        $this->unitPrice = $unitPrice;
    }
    
    /**
     * Create a new order line
     * 
     * @return void
     */
    public static function create(
        int $id,
        Order $order,
        Pizza $pizza,
        int $amount,
        float $unitPrice       
    ) {
        if (!isset(self::$idMap[$id])) {
            self::$idMap[$id] = new OrderLine(
                $id,
                $order,
                $pizza,
                $amount,
                $unitPrice
            );
        }
        
        return self::$idMap[$id];
    }
    
 
    /**
     * Set the id
     * 
     * @param int $id
     * 
     * @return void
     */
    public function setId(int $id):void
    {
        $this->id = $id;
    }
    
    /**
     * Get the id
     * 
     * @return int
     */
    public function getId():int
    {
        return $this->id;
    }
    
    /**
     * Set the order
     * 
     * @param Order $order
     * 
     * @return void
     */
    public function setOrder(Order $order):void
    {
        $this->order = $order;
    }
    
    /**
     * Get the order
     * 
     * @return Order
     */
    public function getOrder():Order
    {
        return $this->order;
    }
    
    /**
     * Get the pizza
     * 
     * @param Pizza $pizza
     * 
     * @return void
     */
    public function setPizza(Pizza $pizza):void
    {
        $this->pizza = $pizza;
    }
    
    /**
     * Set the pizza
     * 
     * @return Pizza
     */
    public function getPizza():Pizza 
    {
        return $this->pizza;
    }
    
    /**
     * Get the amount
     * 
     * @param int $amount
     * 
     * @return void
     */
    public function setAmount(int $amount):void
    {
        $this->amount = $amount;
    }
    
    /**
     * Get the amount
     * 
     * @return int
     */
    public function getAmount():int
    {
        return $this->amount;
    }
    
    /**
     * Get the unit price
     * 
     * @param float $unitPrice
     * 
     * @return void
     */
    public function setUnitPrice(float $unitPrice):void
    {
        $this->unitPrice = $unitPrice;
    }
    
    /**
     * Set the unit price
     * 
     * @return float
     */
    public function getUnitPrice():float
    {
        return $this->unitPrice;
    }

}