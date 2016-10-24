<?php

namespace Elixir\Form\Element;

use Elixir\Form\FormEvent;

/**
 * @author Cédric Tanghe <ced.tanghe@gmail.com>
 */
class Button extends FieldAbstract
{
    /**
     * @var string
     */
    const RESET = 'reset';
    
    /**
     * @var string
     */
    const SUBMIT = 'submit';
    
    /**
     * @var string
     */
    const BUTTON = 'button';

    /**
     * {@inheritdoc}
     */
    public function __construct($name = null)
    {
        if ($name) {
            $this->setName($name);
        }

        $this->helper = 'button';
    }

    /**
     * @param string $value
     */
    public function setType($value)
    {
        $this->setAttribute('type', $value);
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->getAttribute('type');
    }

    /**
     * {@inheritdoc}
     */
    public function prepare($args = null)
    {
        $type = $this->getType();

        if (null === $type) {
            $this->setType(self::SUBMIT);
        }
        
        $this->setAttribute('name', $this->name);
        
        $this->prepared = true;
        $this->dispatch(new FormEvent(FormEvent::PREPARED));
    }

    /**
     * {@inheritdoc}
     */
    public function setValue($value, $format = self::VALUE_RAW)
    {
        $old = $this->value;
        parent::setValue($value, $format);

        if (empty($this->value)) {
            $this->value = $old;
        }
    }
}
