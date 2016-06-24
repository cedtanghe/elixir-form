<?php

namespace Elixir\Form;

use Elixir\Dispatcher\DispatcherTrait;
use Elixir\Form\ElementTrait;
use Elixir\Form\Extension\ExtensionInterface;
use Elixir\Form\Extension\ExtensionTrait;
use Elixir\Form\FormEvent;
use Elixir\Form\FormInterface;
use Elixir\STDLib\Facade\I18N;

/**
 * @author Cédric Tanghe <ced.tanghe@gmail.com>
 */
class Form implements FormInterface, ExtensionInterface
{
    use ElementTrait;
    use DispatcherTrait;
    use ExtensionTrait;
    
    /**
     * @var string
     */
    const ERROR_DEFAULT = 'error_default';
    
    /**
     * @var array 
     */
    protected $elements = [];
    
    /**
     * @var boolean
     */
    protected $prepared = false;
    
    /**
     * @var boolean 
     */
    protected $submitted = false;
    
    /**
     * {@inheritdoc}
     */
    protected function getDefaultCatalogMessages()
    {
        return [
            self::ERROR_DEFAULT => I18N::__('Form is invalid.', ['context' => 'elixir'])
        ];
    }
    
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
     * @throws \InvalidArgumentException
     */
    public function addElement(ElementInterface $element)
    {
        if (!$element->getName())
        {
            throw new \InvalidArgumentException('A form element require a name.');
        }
        
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
        $e = new FormEvent(FormEvent::PRE_SUBMIT, $data);
        $this->dispatch($e);
        
        $data = $e->getData();
        
        if (!empty($data))
        {
            $this->setValue($value, self::VALUE_RAW);
        }
        
        $this->dispatch(new FormEvent(FormEvent::PRE_SUBMIT_VALIDATION));
        
        $result = $this->validate($data);
        $this->submitted = true;
        
        $this->dispatch(new FormEvent(FormEvent::SUBMITTED));
        return $result;
    }
    
    /**
     * {@inheritdoc}
     */
    public function isSubmitted()
    {
        return $this->submitted;
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
        
        $this->setOption('required', $this->required);
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
    public function validate($data = null, array $options = [])
    {
        // Todo
    }
    
    /**
     * {@inheritdoc}
     */
    public function isValid()
    {
        if (!$this->submitted)
        {
            $this->validate();
        }
        
        return $this->hasError();
    }
    
    /**
     * {@inheritdoc}
     */
    public function reset(array $omit = [])
    {
        $this->submitted = false;
        $this->resetValidation();
        
        foreach ($this->elements as $element)
        {
            if (isset($omit[$element->getName()]))
            {
                $element->reset($omit[$element->getName()]);
            }
            else if (!in_array($element->getName(), $omit))
            {
                $element->reset();
            }
        }
        
        $this->dispatch(new FormEvent(FormEvent::RESET_ELEMENT));
    }
}
