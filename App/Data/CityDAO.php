<?php
declare(strict_types = 1);

namespace App\Data;

use App\Entities\City;

use PDO;

class CityDAO
{
    /**
     * Get all cities
     * 
     * @return array
     */
    public function getAll()
    {
        $results = [];
        
        // Generate the query
        $sql = "SELECT id, zipcode, name, delivery
                FROM cities
                ORDER BY zipcode, name";
        
        // Open the connection
        $pdo = DbConfig::getPdo();
        
        // Execute the query
        $resultSet = $pdo->prepare($sql);
        $resultSet->execute();
        
        foreach ($resultSet as $row) {
            $city = $this->createFromDbRow($row);
            array_push($results, $city);
        }
        
        // Close the connection
        $pdo = null;
        
        // Return the results
        return $results;
    }
    
    /**
     * Get a city specified by it's id
     * 
     * @param int $id
     * 
     * @return City|null
     */
    public function getById(int $id):?City
    {
        $city = null;
        
        // Generate the query
        $sql = "SELECT id, zipcode, name, delivery
                FROM cities 
                WHERE id = :id";
        
        // Open the connection
        $pdo = DbConfig::getPdo();
        
        // Execute the query
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        
        // Get the information of the user
        if ($stmt->rowCount() > 0) {
            $row  = $stmt->fetch(PDO::FETCH_ASSOC);
            $city = $this->createFromDbRow($row);
        }
        
        return $city;
    }
    
    /**
     * Create a city object from a row
     * 
     * @param array $row
     * 
     * @return City|null
     */
    private function createFromDbRow(array $row):?City
    {
        $city = null;
        
        if (
            array_key_exists('id' , $row)
            && array_key_exists('zipcode', $row)
            && array_key_exists('name', $row) 
            && array_key_exists('delivery', $row)
        ) {
            $city = City::create(
                intval($row['id']),
                $row['zipcode'],
                $row['name'],
                boolval($row['delivery'])
            );
        }
        
        return $city;
    }
}