<?php

namespace Elixir\Form\Element;

/**
 * @author Cédric Tanghe <ced.tanghe@gmail.com>
 */
class Radio extends FieldAbstract implements MultipleChoiceInterface
{
    use MultipleChoiceTrait;
    
    /**
     * @var string
     */
    const RADIO = 'radio';
    
    /**
     * {@inheritdoc}
     */
    public function __construct($name = null)
    {
        if ($name) {
            $this->setName($name);
        }

        $this->helper = 'radio';
    }
    
    /**
     * {@inheritdoc}
     */
    public function prepare($args = null)
    {
        $this->setType(self::RADIO);
        parent::prepare($args);
    }
}
