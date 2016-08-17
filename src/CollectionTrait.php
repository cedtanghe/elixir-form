<?php

namespace Elixir\Form;

/**
 * @author Cédric Tanghe <ced.tanghe@gmail.com>
 */
trait CollectionTrait 
{
    /**
     * @var integer
     */
    protected $minCardinality = 1;
    
    /**
     * @var integer
     */
    protected $maxCardinality = -1;
    
    /**
     * {@inheritdoc}
     */
    public function setMinCardinality($value)
    {
        $this->minCardinality = $value;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getMinCardinality()
    {
        return $this->minCardinality;
    }
    
    /**
     * {@inheritdoc}
     */
    public function setMaxCardinality($value = -1)
    {
        $this->maxCardinality = $value;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getMaxCardinality()
    {
        return $this->maxCardinality;
    }
}
