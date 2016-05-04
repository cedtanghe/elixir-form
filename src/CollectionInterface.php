<?php

namespace Elixir\Form;

/**
 * @author Cédric Tanghe <ced.tanghe@gmail.com>
 */
interface CollectionInterface
{
   /**
    * @param integer $min
    * @param integer $max
    */
    public function setCardinality($min, $max = -1);
    
    /**
     * @return array
     */
    public function getCardinality();
}
