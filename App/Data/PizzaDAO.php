<?php
declare(strict_types = 1);

namespace App\Data;

use App\Entities\Pizza;

use PDO;

class PizzaDAO
{
    /**
     * Get all available pizzas
     * 
     * @return array
     */
    public function getAvailablePizzas()
    {
        $results = [];
        
        // Generate the query
        $sql = "SELECT id, name, description, vegetarian, price, promotion_price, image, status
                FROM pizza 
                WHERE status = :status
                ORDER BY name";
        
        // Open the connection
        $pdo = DBConfig::getPdo();
        
        // Execute the query
        $resultSet = $pdo->prepare($sql);
        $resultSet->execute([':status' => Pizza::STATUS_AVAILABLE]);
        
        foreach ($resultSet as $row) {
            $pizza = $this->createFromDbRow($row);
            array_push($results, $pizza);
        }
        
        // Close the connection
        $pdo = null;
                
        // Return the results
        return $results;
                
    }
    
    /**
     * Get the pizza specified by it's id
     * 
     * @param int $id
     * 
     * @return Pizza|null
     */
    public function getById(int $id):?Pizza
    {
        $pizza = null;
        
        // Generate the query
        $sql = "SELECT id, name, description, vegetarian, price, promotion_price, image, status
                FROM pizza
                WHERE id = :id";
        
        // Open the connection
        $pdo = DbConfig::getPdo();
        
        // Execute the query
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        
        // Get the information of the pizza
        if ($stmt->rowCount() > 0) {
            $row   = $stmt->fetch(PDO::FETCH_ASSOC);
            $pizza = $this->createFromDbRow($row);
        }
        
        // Close the db connection
        $pdo = null;
        
        return $pizza;
    }
    
    /**
     * Get multiple pizza's specified by their ID's
     * 
     * @param array $pizzaIds
     * 
     * @return array
     */
    public function getMultipleById(array $pizzaIds)
    {
        $pizzas = [];
        
        // Generate the param string
        $keyArray = [];
        foreach ($pizzaIds as $key=>$value) {
            $keyArray[] = ':id' . $key;
        }
        $keyString = implode(',', $keyArray);
        
        // Generate the query       
        $sql = "SELECT id, name, description, vegetarian, price, promotion_price, image, status
                FROM pizza
                WHERE id IN (" . $keyString. ")
                ORDER BY name";
        
         // Open the connection
        $pdo = DbConfig::getPdo();
        
        // Execute the query
        $paramArray = [];
        foreach ($pizzaIds as $key=>$value) {
            $paramsArray[':id' . $key] = $value;
        }
        
        $resultSet = $pdo->prepare($sql);
        $resultSet->execute($paramsArray);
        
        // Get the information of the pizzas
        foreach ($resultSet as $row) {
            $pizza = $this->createFromDbRow($row);
            array_push($pizzas, $pizza);
        }
        
        return $pizzas;
    }
    
    /**
     * Create a pizza object from a row
     * 
     * @param array $row
     * 
     * @return Pizza|null
     */
    private function createFromDbRow(array $row):?Pizza
    {
        $pizza = null;
        
        if (
            array_key_exists('id' , $row)
            && array_key_exists('name', $row) 
            && array_key_exists('description', $row)
            && array_key_exists('vegetarian', $row)
            && array_key_exists('price', $row)
            && array_key_exists('promotion_price', $row)
            && array_key_exists('image', $row)
            && array_key_exists('status', $row) 
        ) {
            $pizza = Pizza::create(
                intval($row['id']),
                $row['name'],
                $row['description'],
                boolval($row['vegetarian']),
                $row['price'],
                $row['promotion_price'],
                $row['image'],
                $row['status']
            );
        }
        
        return $pizza;
    }
       
}