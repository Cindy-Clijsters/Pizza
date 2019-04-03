<?php
declare(strict_types = 1);

namespace App\Entities;

use App\Exceptions\InvalidStatusException;

class Pizza
{
    const STATUS_AVAILABLE   = 'available';
    const STATUS_INAVAILABLE = 'inavailable';
    
    const VALID_STATUSES = [
        self::STATUS_AVAILABLE,
        self::STATUS_INAVAILABLE
    ];
    
    private $id;
    private $name;
    private $description;
    private $vegetarian;
    private $price;
    private $promotionPrice;
    private $status;
    
    private static $idMap = [];
    
    /**
     * Constructor function
     * 
     * @param int $id
     * @param string $name
     * @param string $description
     * @param bool $vegetarian
     * @param float $price
     * @param float $promotionPrice
     * @param string $image
     * @param string $status
     * 
     * @return void
     */
    private function __construct(
        int $id,
        string $name,
        string $description,
        bool $vegetarian,
        float $price,
        ?float $promotionPrice,
        string $image,
        string $status = self::STATUS_AVAILABLE
    ) {
        $this->id             = $id;
        $this->name           = $name;
        $this->description    = $description;
        $this->vegetarian     = $vegetarian;
        $this->price          = $price;
        $this->promotionPrice = $promotionPrice;
        $this->image          = $image;
        $this->status         = $status;
    }
    
    /**
     * Create a pizza object
     * 
     * @param int $id
     * @param string $name
     * @param string $description
     * @param bool $vegetarian
     * @param float $price
     * @param float|null $promotionPrice
     * @param string $image
     * @param string $status
     * 
     * @return Pizza
     */
    public static function create(
        int $id,
        string $name,
        string $description,
        bool $vegetarian,
        float $price,
        ?float $promotionPrice,
        string $image,
        string $status
    ):Pizza {
        if (!isset(self::$idMap[$id])) {
            self::$idMap[$id] = new Pizza(
                $id,
                $name,
                $description,
                $vegetarian,
                $price,
                $promotionPrice,
                $image,
                $status
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
     * Set the name
     * 
     * @param string $name
     * 
     * @return void
     */
    public function setName(string $name):void 
    {
        $this->name = $name;
    }
    
    /**
     * Get the name
     * 
     * @return string
     */
    public function getName():string
    {
        return $this->name;
    }
    
    /**
     * Set the description
     * 
     * @param string $description
     * 
     * @return void
     */
    public function setDescription(string $description):void
    {
        $this->description = $description;
    }
    
    /**
     * Get the description
     * 
     * @return string
     */
    public function getDescription():string
    {
        return $this->description;
    }
    
    /**
     * Set the vegetarian option
     * 
     * @param bool $vegetarian
     * 
     * @return void
     */
    public function setVegetarian(bool $vegetarian):void 
    {
        $this->vegetarian = $vegetarian;
    }
    
    /**
     * Get the vegetarian option
     * 
     * @return bool
     */
    public function getVegetarian():bool
    {
        return $this->vegetarian;
    }
    
    /**
     * Set the price 
     * 
     * @param float $price
     * 
     * @return void
     */
    public function setPrice(float $price):void 
    {
        $this->price = $price;
    }
    
    /**
     * Get the price 
     * 
     * @return float
     */
    public function getPrice():float 
    {
        return $this->price;
    }
    
    /**
     * Set the promotion price
     * 
     * @param float $promotionPrice
     * 
     * @return void
     */
    public function setPromotionPrice(float $promotionPrice):void 
    {
        $this->promotionPrice = $promotionPrice;
    }
    
    /**
     * Get the promotion price
     * 
     * @return ?float
     */
    public function getPromotionPrice():?float
    {
        return $this->promotionPrice;
    }
    
    public function getAdjustedPromotionPrice():float
    {
        return $this->promotionPrice ?? $this->price; 
    }
    
    /**
     * Set the image
     * 
     * @param string $image
     * 
     * @return void
     */
    public function setImage(string $image):void 
    {
        $this->image = $image;
    }
    
    /**
     * Get the image
     * 
     * @return string
     */
    public function getImage():string
    {
        return $this->image;
    }
    
    /**
     * Set the status
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
     * Get the status
     * 
     * @return string
     */
    public function getStatus():string
    {
        return $this->status;
    }
    
}