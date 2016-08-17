<?php

namespace Elixir\Form;

/**
 * @author Cédric Tanghe <ced.tanghe@gmail.com>
 */
interface CollectionInterface
{
    /**
     * @var string
     */
    const MIN_CARDINALITY = 'min_cardinality';
    
    /**
     * @var string
     */
    const MAX_CARDINALITY = 'max_cardinality';
    
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
