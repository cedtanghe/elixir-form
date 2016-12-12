<?php

namespace Elixir\Form;

/**
 * @author Cédric Tanghe <ced.tanghe@gmail.com>
 */
class Fieldset extends Form implements FieldsetInterface
{
    /**
     * {@inheritdoc}
     */
    protected $helper = 'fieldset';

    /**
     * @var string
     */
    protected $legend;

    /**
     * {@inheritdoc}
     */
    public function setLegend($value)
    {
        $this->legend = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function getLegend()
    {
        return $this->legend;
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
        
        $this->setOption('legend', $this->legend);
        
        $this->removeAttribute('method');
        $this->removeAttribute('action');
        $this->removeAttribute('enctype');
        
        $this->prepared = true;
        $this->dispatch(new FormEvent(FormEvent::PREPARED));
    }
}
