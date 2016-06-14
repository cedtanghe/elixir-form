<?php

namespace Elixir\Form;

use Elixir\Filter\FilterTrait;
use Elixir\Form\ElementInterface;
use Elixir\Validator\ValidateTrait;
use function Elixir\STDLib\array_get;
use function Elixir\STDLib\array_has;
use function Elixir\STDLib\array_remove;
use function Elixir\STDLib\array_set;

/**
 * @author Cédric Tanghe <ced.tanghe@gmail.com>
 */
trait ElementTrait 
{
    use FilterTrait;
    use ValidateTrait;
    
    /**
     * @var string
     */
    protected $name;
    
    /**
     * @var ElementInterface
     */
    protected $parent;
    
    /**
     * @var string|callable
     */
    protected $helper;
    
    /**
     * @var array
     */
    protected $attributes = [];
    
    /**
     * @var array
     */
    protected $options = [];
    
    /**
     * @var boolean
     */
    protected $required = false;

    /**
     * {@inheritdoc}
     */
    public function setName($value)
    {
        $this->name = $value;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * @return boolean
     */
    public function isMainElement()
    {
        return null === $this->parent;
    }
    
    /**
     * {@inheritdoc}
     */
    public function setParent(ElementInterface $value)
    {
        $this->parent = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * {@inheritdoc}
     */
    public function setHelper($value)
    {
        $this->helper = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function getHelper()
    {
        return $this->helper;
    }
    
    /**
     * {@inheritdoc}
     */
    public function hasAttribute($key)
    {
        return array_has($key, $this->attributes);
    }

    /**
     * {@inheritdoc}
     */
    public function getAttribute($key, $default = null)
    {
        return array_get($key, $this->attributes);
    }

    /**
     * {@inheritdoc}
     */
    public function setAttribute($key, $value)
    {
        array_set($key, $value, $this->attributes);
    }
    
    /**
     * {@inheritdoc}
     */
    public function removeAttribute($key)
    {
        array_remove($key, $this->attributes);
    }

    /**
     * {@inheritdoc}
     */
    public function setAttributes(array $data)
    {
        $this->attributes = $data;
    }

    /**
     * {@inheritdoc}
     */
    public function getAttributes()
    {
        return $this->attributes;
    }
    
    /**
     * {@inheritdoc}
     */
    public function hasOption($key)
    {
        return array_has($key, $this->options);
    }

    /**
     * {@inheritdoc}
     */
    public function getOption($key, $default = null)
    {
        return array_get($key, $this->options);
    }

    /**
     * {@inheritdoc}
     */
    public function setOption($key, $value)
    {
        array_set($key, $value, $this->options);
    }
    
    /**
     * {@inheritdoc}
     */
    public function removeOption($key)
    {
        array_remove($key, $this->attributes);
    }

    /**
     * {@inheritdoc}
     */
    public function setOptions(array $data)
    {
        $this->options = $data;
    }

    /**
     * {@inheritdoc}
     */
    public function getOptions()
    {
        return $this->options;
    }
    
    /**
     * {@inheritdoc}
     */
    public function setRequired($value)
    {
        $this->required = $value;
    }
    
    /**
     * {@inheritdoc}
     */
    public function isRequired()
    {
        return $this->required;
    }
}