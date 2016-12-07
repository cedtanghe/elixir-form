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
    const BUILD = 'build';

    /**
     * @var string
     */
    const PREPARED = 'prepared';

    /**
     * @var string
     */
    const PRE_POPULATE = 'pre_populate';

    /**
     * @var string
     */
    const POPULATED = 'populated';

    /**
     * @var string
     */
    const PRE_SUBMIT = 'pre_submit';

    /**
     * @var string
     */
    const SUBMITTED = 'submitted';

    /**
     * @var string
     */
    const VALIDATE_ELEMENT = 'validate_element';

    /**
     * @var string
     */
    const RESET_ELEMENT = 'reset_element';

    /**
     * @var string
     */
    const ELEMENT_CREATED = 'element_created';

    /**
     * @var array
     */
    protected $data;

    /**
     * @var ElementInterface
     */
    protected $element;

    /**
     * {@inheritdoc}
     *
     * @param array $params
     */
    public function __construct($type, array $params = [])
    {
        parent::__construct($type);
        $params += [
            'data' => null,
            'element' => null,
        ];

        $this->data = $params['data'];
        $this->element = $params['element'];
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

    /**
     * @return ElementInterface
     */
    public function getElement()
    {
        return $this->element;
    }
}
