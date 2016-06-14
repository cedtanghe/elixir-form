<?php

namespace Elixir\Form\Element;

use Elixir\Dispatcher\DispatcherTrait;
use Elixir\Form\ElementTrait;
use Elixir\Form\FieldInterface;

/**
 * @author Cédric Tanghe <ced.tanghe@gmail.com>
 */
abstract class FieldAbstract implements FieldInterface
{
    use ElementTrait;
    use DispatcherTrait;
    
    /**
     * @var array 
     */
    protected $value = [];
    
    /**
     * @var string 
     */
    protected $label;
    
    /**
     * @var string 
     */
    protected $description;
    
    /**
     * @var boolean
     */
    protected $prepared = false;
    
    /**
     * {@inheritdoc}
     */
    public function setValue($value, $format = self::VALUE_RAW)
    {
        // Todo
    }
    
    /**
     * {@inheritdoc}
     */
    public function getValue($format = self::VALUE_NORMALIZED)
    {
        // Todo
    }
    
    /**
     * {@inheritdoc}
     */
    public function setLabel($value)
    {
        $this->label = $value;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getLabel()
    {
        return $this->label;
    }
    
    /**
     * {@inheritdoc}
     */
    public function setDescription($value)
    {
        $this->description = $value;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getDescription()
    {
        return $this->description;
    }
    
    /**
     * {@inheritdoc}
     */
    public function isEmpty()
    {
        // Todo
    }
    
    /**
     * {@inheritdoc}
     */
    public function isEligible()
    {
        return $this->required || !$this->isEmpty();
    }
    
     /**
     * {@inheritdoc}
     */
    public function prepare($args = null) 
    {
        $this->setOption('required', $this->required);
        $this->setOption('label', $this->label);
        $this->setOption('description', $this->description);
        
        $this->setAttribute('name', $this->name);
        
        $this->prepared = true;
        $this->dispatch(new FormEvent(FormEvent::PREPARE));
    }
    
    /**
     * {@inheritdoc}
     */
    public function isPrepared()
    {
        return $this->prepared;
    }
    
    /**
     * {@inheritdoc}
     */
    public function isValid()
    {
        // Todo
    }
    
    /**
     * {@inheritdoc}
     */
    public function reset()
    {
        // Todo
    }
}
