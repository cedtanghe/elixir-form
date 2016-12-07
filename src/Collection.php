<?php

namespace Elixir\Form;

/**
 * @author Cédric Tanghe <ced.tanghe@gmail.com>
 */
class Collection extends Fieldset
{
    /**
     * {@inheritdoc}
     */
    protected $helper = 'collection';
    
    /**
     * @var bool
     */
    protected $sortable = true;

    /**
     * @var int
     */
    protected $minCardinality = 1;

    /**
     * @var int
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

    /**
     * {@inheritdoc}
     */
    public function setSortable($value)
    {
        $this->sortable = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function isSortable()
    {
        return $this->sortable;
    }
}
