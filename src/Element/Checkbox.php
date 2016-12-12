<?php

namespace Elixir\Form\Element;

/**
 * @author Cédric Tanghe <ced.tanghe@gmail.com>
 */
class Checkbox extends FieldAbstract implements MultipleChoiceInterface
{
    use MultipleChoiceTrait;
    
    /**
     * @var string
     */
    const CHECKBOX = 'checkbox';
    
    /**
     * {@inheritdoc}
     */
    public function __construct($name = null)
    {
        if ($name) {
            $this->setName($name);
        }

        $this->helper = 'checkbox';
    }
    
    /**
     * {@inheritdoc}
     */
    public function prepare($args = null)
    {
        if ($this->prepared && (!isset($args['force']) || true !== $args['force']))
        {
            return;
        }
        
        $this->setType(self::CHECKBOX);
        parent::prepare($args);
    }
}
