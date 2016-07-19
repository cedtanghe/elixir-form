<?php

namespace Elixir\Form;

use Elixir\Dispatcher\DispatcherTrait;
use Elixir\Form\ElementTrait;
use Elixir\Form\Extension\ExtensionInterface;
use Elixir\Form\Extension\ExtensionTrait;
use Elixir\Form\FormEvent;
use Elixir\Form\FormInterface;
use Elixir\STDLib\Facade\I18N;
use function Elixir\STDLib\array_get;

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
     * @var boolean 
     */
    protected $built = false;

    /**
     * @param string $name
     */
    public function __construct($name = null) 
    {
        if ($name)
        {
            $this->setName($name);
        }
    }
    
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
        $values = [];
        
        foreach ($this->elements as $element)
        {
            if (isset($values[$element->getName()]))
            {
                $values[$element->getName()] = array_merge((array)$values[$element->getName()], $element->getValue($format));
            }
            else
            {
                $values[$element->getName()] = $element->getValue($format);
            }
        }
        
        if ($format === self::VALUE_NORMALIZED)
        {
            $values = $this->filter($values, [self::FILTER_MODE => self::FILTER_OUT]);
        }
        
        return $values;
    }
    
     /**
     * {@inheritdoc}
     */
    public function filter($data = null, array $options = [])
    {
        $data = $data ?: $this->getValue(self::VALUE_RAW);
        $type = array_get(self::FILTER_MODE, $options, self::FILTER_OUT);
        
        foreach ($this->filters as $config)
        {
            if (($config['options'][self::FILTER_MODE] & $type) === $type)
            {
                $o = $config['options'] + $options;
                $data = $config['filter']->filter($data, $o);
            }
        }
        
        return $data;
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
    public function build($data = null)
    {
        $this->built = true;
        
        $e = new FormEvent(FormEvent::BUILD, ['data' => $data]);
        $this->dispatch($e);
        
        $data = $e->getData();
    }
    
    /**
     * {@inheritdoc}
     */
    public function isBuilt()
    {
        return $this->built;
    }
    
    /**
     * {@inheritdoc}
     */
    public function hasElement($name, $sub = false)
    {
        foreach ($this->elements as $element)
        {
            if ($element->getName() === $name)
            {
                return true;
            }
            else if ($sub && ($element instanceof FormInterface) && $element->hasElement($name, true))
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
    public function getElement($name, $sub = false, $default = null)
    {
        $elements = [];
        
        foreach ($this->elements as $element)
        {
            if ($element->getName() === $name)
            {
                $elements[] = $element;
            }
            else if ($sub && ($element instanceof FormInterface))
            {
                $el = $element->getElement($name, true, null);
                        
                if ($el)
                {
                    $elements[] = $el;
                }
            }
        }
        
        if (count($elements) > 0)
        {
            return count($elements) === 1 ? $elements[0] : $elements;
        }
        
        return is_callable($default) ? call_user_func($default) : $default;
    }
    
    /**
     * {@inheritdoc}
     */
    public function removeElement($name, $sub = false)
    {
        foreach ($this->elements as $index => $element)
        {
            if ($element->getName() === $name)
            {
                unset($this->elements[$index]);
            }
            else if ($sub && ($element instanceof FormInterface) && $element->hasElement($name, true))
            {
                $element->removeElement($name, true);
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
        
        foreach ($elements as $element)
        {
            $this->addElement($element);
        }
    }
    
    /**
     * {@inheritdoc}
     */
    public function populate($data)
    {
        if (!$this->built)
        {
            $this->build($data);
        }
        
        $e = new FormEvent(FormEvent::PRE_POPULATE, ['data' => $data]);
        $this->dispatch($e);
        
        $this->setValue($e->getData(), self::VALUE_NORMALIZED);
        $this->dispatch(new FormEvent(FormEvent::POPULATED));
    }
    
    /**
     * {@inheritdoc}
     */
    public function submit($data = null)
    {
        if (!$this->built)
        {
            $this->build($data);
        }
        
        $e = new FormEvent(FormEvent::PRE_SUBMIT, ['data' => $data]);
        $this->dispatch($e);
        
        $data = $e->getData();
        
        if (!empty($data))
        {
            $this->setValue($data, self::VALUE_RAW);
        }
        
        $this->submitted = false;
        $result = $this->validate();
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
        
        if (!$this->getMethod())
        {
            $this->setMethod('POST');
        }
        
        if (!$this->getAction())
        {
            $this->setAction('');
        }
        
        $this->setOption('required', $this->required);
        $this->setAttribute('name', $this->name);
        
        $this->prepared = true;
        $this->dispatch(new FormEvent(FormEvent::PREPARED));
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
        $options += [
            'revalidate' => false
        ];
        
        if ($this->submitted && !$options['revalidate'])
        {
            return $this->hasError();
        }
        
        $this->resetValidation();
        
        if ($data)
        {
            $this->setValue($data, self::VALUE_RAW);
        }
        
        $this->dispatch(new FormEvent(FormEvent::VALIDATE_ELEMENT));
        
        foreach ($this->elements as $element)
        {
            if (!$element->validate(null, $options))
            {
                if (!isset($this->validationErrors['elements'][$element->getName()]))
                {
                    $this->validationErrors['elements'][$element->getName()] = [];
                }
                
                $this->validationErrors['elements'][$element->getName()] = array_merge(
                    $this->validationErrors['elements'][$element->getName()],
                    $element->getErrorMessages()
                );
                
                $this->validationErrors['elements'][$element->getName()] = array_unique($this->validationErrors['elements'][$element->getName()]);
                
                if ($this->breakChainValidationOnFailure)
                {
                    return false;
                }
            }
        }
        
        foreach ($this->validators as $config)
        {
            $validator = $config['validator'];
            $o = $config['options'] + $options;

            $valid = $validator->validate($this, $o);

            if (!$valid)
            {
                if (!isset($this->validationErrors['form']))
                {
                    $this->validationErrors['form'] = [];
                }
                
                $this->validationErrors['form'] = array_merge($this->validationErrors['form'], $validator->getErrors());
                $this->validationErrors['form'] = array_unique($this->validationErrors['form']);

                if ($this->breakChainValidationOnFailure)
                {
                    return false;
                }
            }
        }
        
        return $this->hasError();
    }
    
    /**
     * {@inheritdoc}
     */
    public function isValid()
    {
        return $this->validate();
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
            if (!isset($omit[$element->getName()]))
            {
                $element->reset($omit);
            }
        }
        
        $this->dispatch(new FormEvent(FormEvent::RESET_ELEMENT));
    }
}
