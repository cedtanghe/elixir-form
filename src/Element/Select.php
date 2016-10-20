<?php

namespace Elixir\Form\Element;

/**
 * @author Cédric Tanghe <ced.tanghe@gmail.com>
 */
class Select extends FieldAbstract implements MultipleChoiceInterface
{
    use MultipleChoiceTrait;

    /**
     * {@inheritdoc}
     */
    public function __construct($name = null)
    {
        if ($name) {
            $this->setName($name);
        }

        $this->helper = 'select';
    }

    /**
     * @param bool $value
     */
    public function setMultiple($value)
    {
        if ($value) {
            $this->setAttribute('multiple', true);
        } else {
            $this->removeAttribute('multiple');
        }
    }

    /**
     * @return bool
     */
    public function isMultiple()
    {
        return $this->getAttribute('multiple', false);
    }
}
