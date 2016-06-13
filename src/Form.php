<?php

namespace Elixir\Form;

use Elixir\Dispatcher\DispatcherTrait;
use Elixir\Form\ElementTrait;
use Elixir\Form\FormInterface;

/**
 * @author Cédric Tanghe <ced.tanghe@gmail.com>
 */
class Form implements FormInterface
{
    use ElementTrait;
    use DispatcherTrait;
    
    /**
     * @var array 
     */
    protected $elements = [];
    
    /**
     * @var array 
     */
    protected $value = [];
    
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
    public function hasElement($name)
    {
        foreach ($this->elements as $element)
        {
            if ($element->getName() === $name)
            {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * {@inheritdoc}
     */
    public function addElement(ElementInterface $element)
    {
        $this->elements[] = $element;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getElement($name, $default = null)
    {
        foreach ($this->elements as $element)
        {
            if ($element->getName() === $name)
            {
                return $element;
            }
        }
        
        return is_callable($default) ? call_user_func($default) : $default;
    }
    
    /**
     * {@inheritdoc}
     */
    public function removeElement($name)
    {
        $i = count($this->elements);
        
        while ($i--)
        {
            if ($element->getName() === $name)
            {
                array_splice($this->elements, $i, 1);
                break;
            }
        }
    }
    
    /**
     * {@inheritdoc}
     */
    public function getElements()
    {
        return $this->elements;
    }
    
    /**
     * {@inheritdoc}
     */
    public function setElements(array $elements)
    {
        $this->elements = [];
        
        foreach ($elements as $elements)
        {
            $this->addElement($element);
        }
    }
    
    /**
     * {@inheritdoc}
     */
    public function populate($data)
    {
        // Todo
    }
    
    /**
     * {@inheritdoc}
     */
    public function bind($data)
    {
        // Todo
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
        // Todo
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
    public function reset(array $omit = [])
    {
        // Todo
    }
}
