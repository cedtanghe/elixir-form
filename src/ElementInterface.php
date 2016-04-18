<?php

namespace Elixir\Form;

/**
 * @author Cédric Tanghe <ced.tanghe@gmail.com>
 */
interface ElementInterface 
{
    /**
     * @var string
     */
    const VALUE_NORMALIZED = 'normalized';
    
    /**
     * @var string
     */
    const VALUE_RAW = 'raw';
    
    /**
     * @var integer
     */
    const FILTER_IN = 1;
    
    /**
     * @var integer
     */
    const FILTER_OUT = 2;
    
    /**
     * @var integer
     */
    const FILTER_ALL = 3;
    
    /**
     * @param string $value
     */
    public function setName($value);
    
    /**
     * @return string
     */
    public function getName();

    /**
     * @param string|callable $value
     */
    public function setHelper($value);

    /**
     * @return string|callable
     */
    public function getHelper();
    
    /**
     * @param  mixed $value
     */
    public function setValue($value, $format = self::VALUE_RAW);
    
    /**
     * @return mixed
     */
    public function getValue($format = self::VALUE_NORMALIZED);
    
    /**
     * @param string $key
     * @return boolean
     */
    public function hasAttribute($key);

    /**
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function getAttribute($key, $default = null);

    /**
     * @param string $key
     * @param mixed $value
     */
    public function setAttribute($key, $value);
    
    /**
     * @return void
     */
    public function removeAttribute($key);

    /**
     * @param array $data
     */
    public function setAttributes(array $data);

    /**
     * @return array
     */
    public function getAttributes();
    
    /**
     * @param string $key
     * @return boolean
     */
    public function hasOption($key);

    /**
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function getOption($key, $default = null);

    /**
     * @param string $key
     * @param mixed $value
     */
    public function setOption($key, $value);
    
    /**
     * @return void
     */
    public function removeOption($key);

    /**
     * @param array $data
     */
    public function setOptions(array $data);

    /**
     * @return array
     */
    public function getOptions();
    
    /**
     * @return void
     */
    public function prepare();
    
    /**
     * @return boolean
     */
    public function isPrepared();

    /**
     * @return boolean
     */
    public function isEmpty();
    
    /**
     * @return boolean
     */
    public function isEligible();
    
    /**
     * @return boolean
     */
    public function isValid();
    
    /**
     * @param array $messages
     */
    public function setErrorMessages(array $messages);
    
    /**
     * @return array
     */
    public function getErrorMessages();
}
