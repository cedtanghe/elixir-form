<?php

namespace Elixir\Form\Element;

/**
 * @author Cédric Tanghe <ced.tanghe@gmail.com>
 */
interface MultipleChoiceInterface
{
    /**
     * @var string
     */
    const DATA_USE_KEYS = 'keys';
    
    /**
     * @var string
     */
    const DATA_USE_VALUES = 'values';
    
    /**
     * @param string $value
     */
    public function setDataType($value);
    
    /**
     * @return string
     */
    public function getDataType();
    
    /**
     * @param array|\ArrayAccess $value
     * @param string $type
     */
    public function setData($value);
    
    /**
     * @return array|\ArrayAccess
     */
    public function getData();
}
