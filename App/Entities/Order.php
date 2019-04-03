<?php
declare (strict_types = 1);

namespace App\Entities;

use App\Exceptions\InvalidStatusException;

use DateTime;

class Order
{
    private $id;
    private $user;
    private $deliveryAddress;
    private $deliveryDateTime;
    private $remarks;
    private $status;
    private $createdAt;
    
    private $orderLines = [];
    
    const STATUS_PLACED         = 'orderPlaced';
    const STATUS_CONFIRMED      = 'orderConfirmed';
    const STATUS_PROGRESS       = 'inProgress';
    const STATUS_READY_DELIVERY = 'readyForDelivery';
    const STATUS_IN_DELIVERY    = 'inDelivery';
    const STATUS_DELIVERED      = 'delivered';
    const STATUS_CANCELLED      = 'cancelled';
    
    const VALID_STATUSES = [
        self::STATUS_PLACED,
        self::STATUS_CONFIRMED,
        self::STATUS_PROGRESS,
        self::STATUS_READY_DELIVERY,
        self::STATUS_IN_DELIVERY,
        self::STATUS_DELIVERED,
        self::STATUS_CANCELLED
    ];
    
    /**
     * Constructor function
     * 
     * @param int $id
     * @param DateTime $deliveryDateTime
     * @param string $remarks
     * @param string $status
     * @param DateTime $createdAt
     * @param User|null $user
     * @param DeliveryAddress|null $deliveryAddress
     * 
     * @return void
     */
    private function __construct(
        int $id,
        DateTime $deliveryDateTime,
        string $remarks,
        string $status,
        DateTime $createdAt,
        ?User $user = null,
        ?DeliveryAddress $deliveryAddress = null
    ){
        $this->id               = $id;
        $this->user             = $user;
        $this->deliveryAddress  = $deliveryAddress;
        $this->deliveryDateTime = $deliveryDateTime;
        $this->remarks          = $remarks;
        $this->status           = $status;
        $this->createdAt        = $createdAt;
    }
    
    /**
     * Create an order object
     * 
     * @param int $id
     * @param DateTime $deliveryDateTime
     * @param string $remarks
     * @param string $status
     * @param DateTime $createdAt
     * @param User|null $user
     * @param DeliveryAddress|null $deliveryAddress
     * 
     * @return Order
     */
    public static function create(
        int $id,
        DateTime $deliveryDateTime,
        string $remarks,
        string $status,
        DateTime $createdAt,
        ?User $user = null,
        ?DeliveryAddress $deliveryAddress = null      
    ):Order {
        
        if (!isset(self::$idMap[$id])) {
            self::$idMap[$id] = new Order(
                $id,
                $deliveryDateTime,
                $remarks,
                $status,
                $createdAt,
                $user,
                $deliveryAddress
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
     * Set the user
     * 
     * @param User|null $user
     * 
     * @return void
     */
    public function setUser(?User $user):void
    {
        $this->user = $user;
    }
    
    /**
     * Get the user
     * 
     * @return User|null
     */
    public function getUser():?User 
    {
        return $this->user;
    }

    /**
     * Set the delivery address
     * 
     * @param DeliveryAddress|null $deliveryAddress
     * 
     * @return void
     */
    public function setDeliveryAddress(?DeliveryAddress $deliveryAddress):void
    {
        $this->deliveryAddress = $deliveryAddress;
    }
    
    /**
     * Get the delivery address
     * 
     * @return DeliveryAddress|null
     */
    public function getDeliveryAddress():?DeliveryAddress
    {
        return $this->deliveryAddress;
    }
    
    /**
     * Set the delivery date time
     * 
     * @param DateTime $deliveryDateTime
     * 
     * @return void
     */
    public function setDeliveryDateTime(DateTime $deliveryDateTime):void
    {
        $this->deliveryDateTime = $deliveryDateTime;
    }
    
    /**
     * Get the delivery date time
     * 
     * @return DateTime
     */
    public function getDeliveryDateTime():DateTime
    {
        return $this->deliveryDateTime;
    }
    
    /**
     * Set the remarks
     * 
     * @param string $remarks
     * 
     * @return void
     */
    public function setRemarks(string $remarks):void
    {
        $this->remarks = $remarks;
    }
    
    /**
     * Get the remarks
     * 
     * @return string
     */
    public function getRemarks():string
    {
        return $this->remarks;
    }
    
    /**
     * Set the status of the order
     * 
     * @param string $status
     * 
     * @return void
     */
    public function setStatus(string $status):void
    {
        if (in_array($status, self::VALID_STATUSES)) {
            $this->status = $status;
        } else {
            throw new InvalidStatusException();
        }
        
    }
    
    /**
     * Get the status of the order
     *
     * @return string
     */
    public function getStatus():string
    {
        return $this->status;
    }
    
    /**
     * Set the creation date
     * 
     * @param DateTime $createdAt
     * 
     * @return void
     */
    public function setCreatedAt(DateTime $createdAt):void
    {
        $this->createdAt = $createdAt;
    }
    
    /**
     * Get the creation date
     * 
     * @return DateTime
     */
    public function getCreatedAt():DateTime
    {
        return $this->createdAt;
    }
    
    /**
     * Add a new orderline
     * 
     * @param OrderLine $orderLine
     */
    public function addOrderLine(OrderLine $orderLine)
    {
        $this->orderLines[] = $orderLine;
    }
    
    /**
     * Get the order lines
     * 
     * @return array
     */
    public function getOrderLines():array
    {
        return $this->orderLines;
    }
}