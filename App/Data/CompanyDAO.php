<?php
declare(strict_types = 1);

namespace App\Data;

use App\Entities\Company;
use App\Entities\City;
use App\Exceptions\NoCompanyInfoException;

use PDO;

class CompanyDAO
{
    /**
     * Get the information of the company
     * 
     * @throw NoCompanyInfoException
     * @return Company|null
     */
    public function getInfo():?Company
    {
        $company = null;
        
        // Generate the query
        $sql = "SELECT cm.id, cm.name, cm.address, cm.city_id, cm.phone, cm.mail, cm.vat_number, cm.about_us,
                    ct.zipcode as city_zipcode, ct.name AS city_name, ct.delivery AS city_delivery
                FROM company cm
                JOIN cities ct ON cm.city_id = ct.id";
        
        // Open the connection
        $pdo = DbConfig::getPdo();
        
        // Execute the query 
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        
        if ($stmt->rowCount() === 1) {
            
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $city = City::create(
                intVal($row['city_id']),
                $row['city_zipcode'],
                $row['city_name'],
                boolval($row['city_delivery'])
            );
            
            $company = Company::create(
                $row['id'],
                $row['name'],
                $row['address'],
                $city,
                $row['phone'],
                $row['mail'],
                $row['vat_number'],
                $row['about_us']
            );

        } else {
            throw new NoCompanyInfoException();
        }
        
        // Close the db connection
        $pdo = null;
        
        // Return the result
        return $company;
    }
}
