<?php

namespace Elixir\Form\Element;

use Elixir\Dispatcher\DispatcherTrait;
use Elixir\Form\ElementTrait;
use Elixir\Form\FieldInterface;
use Elixir\Form\FormEvent;
use Elixir\STDLib\Facade\I18N;

/**
 * @author Cédric Tanghe <ced.tanghe@gmail.com>
 */
abstract class FieldAbstract implements FieldInterface
{
    use ElementTrait;
    use DispatcherTrait;

    /**
     * @var string
     */
    const ERROR_DEFAULT = 'error_default';

    /**
     * @var mixed
     */
    protected $value;

    /**
     * @var string
     */
    protected $label;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var bool
     */
    protected $prepared = false;

    /**
     * {@inheritdoc}
     */
    public function __construct($name = null)
    {
        parent::__construct($name);
        $this->helper = 'input';
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefaultCatalogMessages()
    {
        return [
            self::ERROR_DEFAULT => I18N::__('Field is invalid.', ['context' => 'elixir']),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function setValue($value, $format = self::VALUE_RAW)
    {
        if ($format === self::VALUE_NORMALIZED) {
            $value = $this->filter($value, [self::FILTER_MODE => self::FILTER_IN]);
        }

        $this->value = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function getValue($format = self::VALUE_NORMALIZED)
    {
        $value = $this->value;

        if ($format === self::VALUE_NORMALIZED) {
            $value = $this->filter($value, [self::FILTER_MODE => self::FILTER_OUT]);
        }

        return $value;
    }

    /**
     * {@inheritdoc}
     */
    public function setLabel($value)
    {
        $this->label = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * {@inheritdoc}
     */
    public function setDescription($value)
    {
        $this->description = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * {@inheritdoc}
     */
    public function isEmpty()
    {
        $value = $this->getValue(self::VALUE_NORMALIZED);

        return empty($value);
    }

    /**
     * {@inheritdoc}
     */
    public function isEligible()
    {
        return $this->required || !$this->isEmpty();
    }

    /**
     * {@inheritdoc}
     */
    public function prepare($args = null)
    {
        $this->setOption('required', $this->required);
        $this->setOption('label', $this->label);
        $this->setOption('description', $this->description);

        $this->setAttribute('name', $this->name);

        $this->prepared = true;
        $this->dispatch(new FormEvent(FormEvent::PREPARED));
    }

    /**
     * {@inheritdoc}
     */
    public function isPrepared()
    {
        return $this->prepared;
    }

    /**
     * {@inheritdoc}
     */
    public function validate($data = null, array $options = [])
    {
        $this->resetValidation();

        if ($data) {
            $this->setValue($data, self::VALUE_RAW);
        }

        $this->dispatch(new FormEvent(FormEvent::VALIDATE_ELEMENT));

        foreach ($this->validators as $config) {
            $validator = $config['validator'];
            $o = $config['options'] + $options + ['element_context' => $this];

            $valid = $validator->validate($this->value, $o);

            if (!$valid) {
                $this->validationErrors += $validator->getErrors();

                if ($this->breakChainValidationOnFailure) {
                    return false;
                }
            }
        }

        $this->validationErrors = array_unique($this->validationErrors);

        return $this->hasError();
    }

    /**
     * {@inheritdoc}
     */
    public function isValid()
    {
        return $this->validate();
    }

    /**
     * {@inheritdoc}
     */
    public function reset()
    {
        $this->resetValidation();
        $this->value = null;

        $this->dispatch(new FormEvent(FormEvent::RESET_ELEMENT));
    }
}
