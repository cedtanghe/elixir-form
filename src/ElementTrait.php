<?php

namespace Elixir\Form;

use Elixir\Filter\FilterInterface;
use Elixir\Filter\FilterizableTrait;
use Elixir\Form\Filter\DataTransformerInterface;
use Elixir\Validator\ValidatableTrait;
use function Elixir\STDLib\array_get;
use function Elixir\STDLib\array_has;
use function Elixir\STDLib\array_remove;
use function Elixir\STDLib\array_set;

/**
 * @author Cédric Tanghe <ced.tanghe@gmail.com>
 */
trait ElementTrait
{
    use FilterizableTrait
    {
        addFilter as traitAddFilter;
    }

    use ValidatableTrait;

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
     * @var bool
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
     * @return bool
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
    public function getRootElement()
    {
        if ($this->isMainElement()) {
            return $this;
        }

        return $this->parent->getRootElement();
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

    /**
     * @see FilterizableTrait::addFilter()
     */
    public function addFilterIn(FilterInterface $filter, array $options = [])
    {
        $options[ElementInterface::FILTER_MODE] = ElementInterface::FILTER_IN;
        $this->addFilter($filter, $options);
    }

    /**
     * @see FilterizableTrait::addFilter()
     */
    public function addFilterOut(FilterInterface $filter, array $options = [])
    {
        $options[ElementInterface::FILTER_MODE] = ElementInterface::FILTER_OUT;
        $this->addFilter($filter, $options);
    }

    /**
     * @see FilterizableTrait::addFilter()
     */
    public function addFilterBoth(FilterInterface $filter, array $options = [])
    {
        $options[ElementInterface::FILTER_MODE] = ElementInterface::FILTER_BOTH;
        $this->addFilter($filter, $options);
    }

    /**
     * @see FilterizableTrait::addFilter()
     */
    public function addDataTransformer(DataTransformerInterface $filter, array $options = [])
    {
        $this->addFilterBoth($filter, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function addFilter(FilterInterface $filter, array $options = [])
    {
        if ($filter instanceof DataTransformerInterface) {
            $options[ElementInterface::FILTER_MODE] = ElementInterface::FILTER_BOTH;
        } elseif (!isset($options[ElementInterface::FILTER_MODE])) {
            $options[ElementInterface::FILTER_MODE] = ElementInterface::FILTER_OUT;
        }

        return $this->traitAddFilter($filter, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function filter($data = null, array $options = [])
    {
        $data = $data ?: $this->getValue(self::VALUE_RAW);
        $type = array_get(self::FILTER_MODE, $options, self::FILTER_OUT);

        foreach ($this->filters as $config) {
            if (($config['options'][self::FILTER_MODE] & $type) === $type) {
                $o = $config['options'] + $options;
                $data = $config['filter']->filter($data, $o);
            }
        }

        return $data;
    }
}
