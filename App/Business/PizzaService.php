<?php
declare(strict_types = 1);

namespace App\Business;

use App\Data\PizzaDAO;

use stdClass;

class PizzaService
{
    /**
     * Get a list with all available pizzas
     * 
     * @return array
     */
    public function getAvailablePizzas()
    {
        $pizzaDAO = new PizzaDAO();
        $pizzas   = $pizzaDAO->getAvailablePizzas();
        
        return $pizzas;
    }
    
    /**
     * Get a pizza specified by it's id
     * 
     * @param int $id
     * 
     * @return Pizza
     */
    public function getById(int $id)
    {
        $pizzaDAO = new PizzaDAO();
        $pizza    = $pizzaDAO->getById($id);
        
        return $pizza;
    }
    
    /**
     * Get multiple pizza's specified by their id's
     * 
     * @param array $pizzaIds (array($pizzaId, $pizzaId)
     * 
     * @return array
     */
    public function getMultipleById(array $pizzaIds)
    {
        $pizzaDAO = new PizzaDAO();
        $pizzas   = $pizzaDAO->getMultipleById($pizzaIds);
        
        return $pizzas;
    }
}