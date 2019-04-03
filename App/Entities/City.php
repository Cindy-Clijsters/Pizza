<?php
declare (strict_types = 1);

namespace App\Entities;

class City
{
    private $id;
    private $zipCode;
    private $name;
    private $delivery;
    
    private static $idMap = [];
    
    /**
     * Constructor function
     * 
     * @param int $id
     * @param string $zipCode
     * @param string $name
     * @param bool $delivery
     * 
     * @return void
     */
    private function __construct(
        int $id,
        string $zipCode,
        string $name,
        bool $delivery
    ) {
        $this->id       = $id;
        $this->zipCode  = $zipCode;
        $this->name     = $name;
        $this->delivery = $delivery;
    }
    
    /**
     * Create a city object
     * 
     * @param int $id
     * @param string $zipCode
     * @param string $name
     * @param bool $delivery
     * 
     * @return City
     */
    public static function create(
        int $id,
        string $zipCode,
        string $name,
        bool $delivery
    ): City {
        if (!isset(self::$idMap[$id])) {
            self::$idMap[$id] = new City(
                $id,
                $zipCode,
                $name,
                $delivery
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
     * Set the zip code
     * 
     * @param string $zipCode
     * 
     * @return void
     */
    public function setZipCode(string $zipCode):void 
    {
        $this->zipCode = $zipCode;
    }
    
    /**
     * Get the zip code 
     * 
     * @return string
     */
    public function getZipCode():string
    {
        return $this->zipCode;
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
     * Return the delivery
     * 
     * @param bool $delivery
     * 
     * @return void
     */
    public function setDelivery(bool $delivery):void
    {
        $this->delivery = $delivery;
    }
    
    /**
     * Get the delivery
     * 
     * @return bool
     */
    public function getDelivery():bool
    {
        return $this->delivery;
    }
}