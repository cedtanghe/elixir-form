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
     * {@inheritdoc}
     * @param array $params
     */
    public function __construct($type, array $params = [])
    {
        parent::__construct($type);
    }
}
