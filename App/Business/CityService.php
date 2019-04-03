<?php
declare (strict_types = 1);

namespace App\Business;

use App\Data\CityDAO;
use App\Entities\City;

class CityService
{
    /**
     * Get an array with all cities
     * 
     * @return array
     */
    public function getAll()
    {
        $cityDAO = new CityDAO();
        $cities  = $cityDAO->getAll();
        
        return $cities;
    }
    
    /**
     * Get a city by it's id
     * 
     * @param int $id
     * 
     * @return City|null
     */
    public function getById(int $id):?City
    {
        $cityDAO = new CityDAO();
        $city    = $cityDAO->getById($id);
        
        return $city;
    }
    
    /**
     * Check if home delivery is available
     * 
     * @param int $cityId
     * 
     * @return bool
     */
    public function checkHomeDelivery(int $cityId):bool
    {
        $result = false;
        
        $city = $this->getById($cityId);
        
        if ($city->getDelivery() === true) {
            $result = true;
        }
        
        return $result;
    }
}