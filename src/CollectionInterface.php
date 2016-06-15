<?php

namespace Elixir\Form;

/**
 * @author Cédric Tanghe <ced.tanghe@gmail.com>
 */
interface CollectionInterface
{
    /**
     * @param integer $value
     */
    public function setMinCardinality($value);
    
    /**
     * @return integer
     */
    public function getMinCardinality();
    
    /**
     * @param integer $value
     */
    public function setMaxCardinality($value = -1);
    
    /**
     * @return integer
     */
    public function getMaxCardinality();
}
