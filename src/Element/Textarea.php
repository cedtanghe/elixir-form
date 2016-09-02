<?php

namespace Elixir\Form\Element;

/**
 * @author Cédric Tanghe <ced.tanghe@gmail.com>
 */
class Textarea
{
    /**
     * {@inheritdoc}
     */
    public function __construct($name = null)
    {
        if ($name) {
            $this->setName($name);
        }

        $this->helper = 'textarea';
    }
}
