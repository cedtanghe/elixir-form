<?php

namespace Elixir\Form;

use Elixir\Dispatcher\Event;

/**
 * @author Cédric Tanghe <ced.tanghe@gmail.com>
 */
class FormEvent extends Event 
{
    /**
     * @var string
     */
    const PREPARE = 'prepare';
    
    /**
     * @var string
     */
    const PRE_SUBMIT = 'pre_submit';
    
    /**
     * @var string
     */
    const PRE_SUBMIT_VALIDATION = 'pre_submit_validation';
    
    /**
     * @var string
     */
    const RESET_FORM = 'reset_form';
    
    /**
     * @var array
     */
    protected $data;
    
    /**
     * {@inheritdoc}
     * @param array $params
     */
    public function __construct($type, array $params = [])
    {
        parent::__construct($type);
        
        $params += [
            'data' => null
        ];
        
        $this->data = $params['data'];
    }
    
    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }
    
    /**
     * @param array $value
     */
    public function setData(array $value)
    {
        $this->data = $value;
    }
}
