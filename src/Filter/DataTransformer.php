<?php

namespace Elixir\Form\Filter;

use Elixir\Form\DataTransformerInterface;

/**
 * @author Cédric Tanghe <ced.tanghe@gmail.com>
 */
class DataTransformer implements DataTransformerInterface
{
    /**
     * @var FilterInterface 
     */
    protected $in;
    
    /**
     * @var FilterInterface 
     */
    protected $out;
    
    /**
     * @param FilterInterface $in
     * @param FilterInterface $out
     */
    public function __construct(FilterInterface $in = null, FilterInterface $out = null)
    {
        $this->setFilterIn($in);
        $this->setFilterOut($out);
    }
    
    /**
     * @param FilterInterface $value
     */
    public function setFilterIn(FilterInterface $value)
    {
        $this->in = $value;
    }
    
    /**
     * @param FilterInterface $value
     */
    public function setFilterOut(FilterInterface $value)
    {
        $this->out = $value;
    }
    
    /**
     * {@inheritdoc}
     */
    public function filter($content, array $options = [])
    {
        if ($options[ElementInterface::FILTER_MODE] === ElementInterface::FILTER_IN)
        {
            return $this->applyFilterIn($content, $options);
        }
        else
        {
            return $this->applyFilterOut($content, $options);
        }
    }
    
    /**
     * @see FilterInterface::filter()
     */
    protected function applyFilterIn($content, array $options)
    {
        return $this->in ? $this->in->filter($content, $options) : $content;
    }
    
    /**
     * @see FilterInterface::filter()
     */
    protected function applyFilterOut($content, array $options)
    {
        return $this->out ? $this->out->filter($content, $options) : $content;
    }
}
