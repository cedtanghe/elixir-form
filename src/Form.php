<?php

namespace Elixir\Form;

use Elixir\Dispatcher\DispatcherTrait;
use Elixir\Form\ElementTrait;
use Elixir\Form\FormEvent;
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
     * @param string $value
     */
    public function setMethod($value)
    {
        $this->setAttribute('method', $value);
    }
    
    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->getAttribute('method');
    }
    
    /**
     * @param string $value
     */
    public function setAction($value)
    {
        $this->setAttribute('action', $value);
    }
    
    /**
     * @return string
     */
    public function getAction()
    {
        return $this->getAttribute('action');
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
    public function submit($data = null)
    {
        // Todo
    }
    
    /**
     * {@inheritdoc}
     */
    public function isEmpty()
    {
        foreach ($this->elements as $element)
        {
            if (!$element->isEmpty())
            {
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * {@inheritdoc}
     */
    public function prepare($args = null) 
    {
        if ($this->parent && $this->getAttribute('enctype') === self::ENCTYPE_MULTIPART)
        {
            $this->parent->setAttribute('enctype', self::ENCTYPE_MULTIPART);
        }
        
        if ($this->hasAttribute('method') && !in_array(strtolower($this->getAttribute('method')), [self::METHOD_GET, self::METHOD_POST]))
        {
            throw new \InvalidArgumentException(sprintf('The attribute "method" is invalid, use a hidden field "%s" instead', self::METHOD_PROXY_NAME));
        }
        
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
    public function isEligible()
    {
        return $this->required || !$this->isEmpty();
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
