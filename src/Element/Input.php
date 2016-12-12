<?php

namespace Elixir\Form\Element;

/**
 * @author Cédric Tanghe <ced.tanghe@gmail.com>
 */
class Input extends FieldAbstract
{
    /**
     * @var string
     */
    const BUTTON = 'button';

    /**
     * @var string
     */
    const CHECKBOX = 'checkbox';

    /**
     * @var string
     */
    const FILE = 'file';

    /**
     * @var string
     */
    const HIDDEN = 'hidden';

    /**
     * @var string
     */
    const IMAGE = 'image';

    /**
     * @var string
     */
    const PASSWORD = 'password';

    /**
     * @var string
     */
    const RADIO = 'radio';

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
    const TEXT = 'text';

    /**
     * @var string
     */
    const COLOR = 'color';

    /**
     * @var string
     */
    const DATE = 'date';

    /**
     * @var string
     */
    const DATETIME = 'datetime';

    /**
     * @var string
     */
    const DATETIME_LOCAL = 'datetime-local';

    /**
     * @var string
     */
    const EMAIL = 'email';

    /**
     * @var string
     */
    const MONTH = 'month';

    /**
     * @var string
     */
    const NUMBER = 'number';

    /**
     * @var string
     */
    const RANGE = 'range';

    /**
     * @var string
     */
    const SEARCH = 'search';

    /**
     * @var string
     */
    const TEL = 'tel';

    /**
     * @var string
     */
    const TIME = 'time';

    /**
     * @var string
     */
    const URL = 'url';

    /**
     * @var string
     */
    const WEEK = 'week';

    /**
     * @var array
     */
    protected static $excludeTypes = [
        self::FILE => File::class,
        self::CHECKBOX => Checkbox::class,
        self::RADIO => Radio::class,
    ];

    /**
     * {@inheritdoc}
     */
    public function __construct($name = null)
    {
        if ($name) {
            $this->setName($name);
        }

        $this->helper = 'input';
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
     *
     * @throws \LogicException
     */
    public function prepare($args = null)
    {
        if ($this->prepared && (!isset($args['force']) || true !== $args['force']))
        {
            return;
        }
        
        $type = $this->getType();

        if (null === $type) {
            $this->setType(self::TEXT);
        }
        
        if (array_key_exists($type, static::$excludeTypes)) {
            throw new \LogicException(
                sprintf(
                    'The class "%s" class is better predisposed to such use.',
                    static::$excludeTypes[$type]
                )
            );
        }
        
        return parent::prepare($args);
    }

    /**
     * {@inheritdoc}
     */
    public function setValue($value, $format = self::VALUE_RAW)
    {
        $old = $this->value;
        parent::setValue($value, $format);

        if (in_array($this->getType(), [self::BUTTON, self::SUBMIT, self::RESET])) {
            if (empty($this->value)) {
                $this->value = $old;
            }
        }
    }
}
