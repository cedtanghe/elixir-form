<?php

namespace Elixir\Form;

use Elixir\Form\Form;

/**
 * @author Cédric Tanghe <ced.tanghe@gmail.com>
 */
class Fieldset extends Form
{
    /**
     * @var string
     */
    protected $legend;
    
    /**
     * {@inheritdoc}
     */
    public function setLegend($value)
    {
        $this->legend = $value;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getLegend()
    {
        return $this->legend;
    }
    
    /**
     * {@inheritdoc}
     */
    public function prepare($args = null) 
    {
        $this->setAttribute('legend', $this->legend);
        parent::prepare($args);
    }
}
