<?php

namespace Elixir\Form;

use Elixir\Filter\FilterInterface;

/**
 * @author Cédric Tanghe <ced.tanghe@gmail.com>
 */
class ElementFilter implements FilterInterface
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
    public function __construct(FilterInterface $in, FilterInterface $out)
    {
        $this->in = $in;
        $this->out = $out;
    }
    
    /**
     * {@inheritdoc}
     */
    public function filter($content, array $options = [])
    {
        if ($options[ElementInterface::FILTER_MODE] === ElementInterface::FILTER_IN)
        {
            return $this->in($content, $options);
        }
        else
        {
            return $this->out($content, $options);
        }
    }
    
    /**
     * @see FilterInterface::filter()
     */
    protected function in($content, array $options)
    {
        return $this->in->filter($options, $options);
    }
    
    /**
     * @see FilterInterface::filter()
     */
    protected function out($content, array $options)
    {
        return $this->out->filter($options, $options);
    }
}
