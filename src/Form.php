<?php

namespace Elixir\Form;

use Elixir\Dispatcher\DispatcherTrait;
use Elixir\Form\Extension\ExtensionInterface;
use Elixir\Form\Extension\ExtensionTrait;

/**
 * @author Cédric Tanghe <ced.tanghe@gmail.com>
 */
class Form implements FormInterface, ExtensionInterface
{
    use ElementTrait;
    use DispatcherTrait;
    use ExtensionTrait;

    /**
     * @var string
     */
    const ERROR_DEFAULT = 'error_default';

    /**
     * {@inheritdoc}
     */
    protected $helper = 'form';

    /**
     * @var array
     */
    protected $elements = [];

    /**
     * @var bool
     */
    protected $prepared = false;

    /**
     * @var bool
     */
    protected $submitted = false;

    /**
     * @var bool
     */
    protected $built = false;

    /**
     * @var array
     */
    protected $validationGroup = [];

    /**
     * @param string $name
     */
    public function __construct($name = null)
    {
        if ($name) {
            $this->setName($name);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setValue($value, $format = self::VALUE_RAW)
    {
        $values = (array) $value;

        if ($format === self::VALUE_NORMALIZED) {
            $values = $this->filter($values, [self::FILTER_MODE => self::FILTER_IN]);
        }

        foreach ($this->elements as $element) {
            if ($element instanceof FormInterface) {
                $element->setValue($values, $format);
            } elseif (array_key_exists($values[$element->getName()])) {
                $element->setValue($values[$element->getName()], $format);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getValue($format = self::VALUE_NORMALIZED)
    {
        $values = [];

        foreach ($this->elements as $element) {
            if (array_key_exists($values[$element->getName()])) {
                $values[$element->getName()] = array_merge((array) $values[$element->getName()], $element->getValue($format));
            } else {
                $values[$element->getName()] = $element->getValue($format);
            }
        }

        if ($format === self::VALUE_NORMALIZED) {
            $values = $this->filter($values, [self::FILTER_MODE => self::FILTER_OUT]);
        }

        return $values;
    }

    /**
     * @param string $value
     */
    public function setMethod($value)
    {
        $this->setAttribute('method', $value);
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->getAttribute('method');
    }

    /**
     * @param string $value
     */
    public function setAction($value)
    {
        $this->setAttribute('action', $value);
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->getAttribute('action');
    }

    /**
     * {@inheritdoc}
     */
    public function build($data = null)
    {
        $this->built = true;
        $this->dispatch(new FormEvent(FormEvent::BUILD, ['data' => $data]));
    }

    /**
     * {@inheritdoc}
     */
    public function isBuilt()
    {
        return $this->built;
    }

    /**
     * {@inheritdoc}
     */
    public function hasElement($name, $sub = false)
    {
        foreach ($this->elements as $element) {
            if ($element->getName() === $name) {
                return true;
            } elseif ($sub && ($element instanceof FormInterface) && $element->hasElement($name, true)) {
                return true;
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \InvalidArgumentException
     */
    public function addElement(ElementInterface $element)
    {
        if (!$element->getName()) {
            throw new \InvalidArgumentException('A form element require a name.');
        }

        $this->elements[] = $element;
    }

    /**
     * {@inheritdoc}
     */
    public function getElement($name, $sub = false, $default = null)
    {
        $elements = [];

        foreach ($this->elements as $element) {
            if ($element->getName() === $name) {
                $elements[] = $element;
            } elseif ($sub && ($element instanceof FormInterface)) {
                $el = $element->getElement($name, true, null);

                if ($el) {
                    $elements[] = $el;
                }
            }
        }

        if (count($elements) > 0) {
            return count($elements) === 1 ? $elements[0] : $elements;
        }

        return is_callable($default) ? call_user_func($default) : $default;
    }

    /**
     * {@inheritdoc}
     */
    public function removeElement($name, $sub = false)
    {
        foreach ($this->elements as $index => $element) {
            if ($element->getName() === $name) {
                unset($this->elements[$index]);
            } elseif ($sub && ($element instanceof FormInterface) && $element->hasElement($name, true)) {
                $element->removeElement($name, true);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getElements()
    {
        return $this->elements;
    }

    /**
     * {@inheritdoc}
     */
    public function setElements(array $elements)
    {
        $this->elements = [];

        foreach ($elements as $element) {
            $this->addElement($element);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function populate($data)
    {
        if (!$this->built) {
            $this->build($data);
        }
        
        $e = new FormEvent(FormEvent::PRE_POPULATE, ['data' => $data]);
        $this->dispatch($e);

        $this->setValue($e->getData(), self::VALUE_NORMALIZED);
        $this->dispatch(new FormEvent(FormEvent::POPULATED));
    }

    /**
     * {@inheritdoc}
     */
    public function submit($data = null)
    {
        if (!$this->built) {
            $this->build($data);
        }
        
        if (!$this->prepared) {
            $this->prepare();
        }
        
        $e = new FormEvent(FormEvent::PRE_SUBMIT, ['data' => $data]);
        $this->dispatch($e);

        $data = $e->getData();

        if (!empty($data)) {
            $this->setValue($data, self::VALUE_RAW);
        }
        
        $this->submitted = false;
        $result = $this->validate();
        $this->submitted = true;

        $this->dispatch(new FormEvent(FormEvent::SUBMITTED));

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function isSubmitted()
    {
        return $this->submitted;
    }

    /**
     * {@inheritdoc}
     */
    public function isEmpty()
    {
        foreach ($this->elements as $element) {
            if (!$element->isEmpty()) {
                return false;
            }
        }

        return true;
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
        
        if ($this->parent && $this->getAttribute('enctype') === self::ENCTYPE_MULTIPART) {
            $this->parent->setAttribute('enctype', self::ENCTYPE_MULTIPART);
        }

        if (!$this->getMethod()) {
            $this->setMethod('POST');
        }

        if (!$this->getAction()) {
            $this->setAction('');
        }

        $this->setOption('required', $this->required);
        $this->setAttribute('name', $this->name);
        
        foreach ($this->getElements() as $element) {
            $element->prepare($args);
        }

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
    public function isEligible()
    {
        return $this->required || !$this->isEmpty();
    }

    /**
     * {@inheritdoc}
     */
    public function validate($data = null, array $options = [])
    {
        $options += [
            'revalidate' => false,
        ];

        if ($this->submitted && !$options['revalidate']) {
            return $this->hasError();
        }

        $this->resetValidation();

        if ($data) {
            $this->setValue($data, self::VALUE_RAW);
        }

        $this->dispatch(new FormEvent(FormEvent::VALIDATE_ELEMENT));

        foreach ($this->elements as $element) {
            if (in_array($element->getName(), $this->validationGroup)) {
                continue;
            }

            if (!$element->validate(null, $options)) {
                if (!isset($this->validationErrors['elements'][$element->getName()])) {
                    $this->validationErrors['elements'][$element->getName()] = [];
                }

                $this->validationErrors['elements'][$element->getName()] = array_merge(
                    $this->validationErrors['elements'][$element->getName()],
                    $element->getErrorMessages()
                );

                $this->validationErrors['elements'][$element->getName()] = array_unique($this->validationErrors['elements'][$element->getName()]);

                if ($this->breakChainValidationOnFailure) {
                    return false;
                }
            }
        }

        foreach ($this->validators as $config) {
            $validator = $config['validator'];
            $o = $config['options'] + $options + ['element_context' => $this];

            $valid = $validator->validate($this, $o);

            if (!$valid) {
                if (!isset($this->validationErrors['form'])) {
                    $this->validationErrors['form'] = [];
                }

                $this->validationErrors['form'] = array_merge($this->validationErrors['form'], $validator->getErrors());
                $this->validationErrors['form'] = array_unique($this->validationErrors['form']);

                if ($this->breakChainValidationOnFailure) {
                    return false;
                }
            }
        }

        return $this->hasError();
    }

    /**
     * @param array $members
     * @param array $omitMembers
     * @param array $data
     *
     * @return bool
     */
    public function validateGroup(array $members = [], array $omitMembers = [], array $data = null)
    {
        $result = false;
        $this->validationGroup = [];

        foreach ($this->elements as $element) {
            if (in_array($element->getName(), $omitMembers)) {
                continue;
            } elseif (count($members) > 0 && !in_array($element->getName(), $members)) {
                continue;
            }

            $this->validationGroup[] = $element->getName();
        }

        if (count($this->validationGroup) > 0) {
            $result = $this->validate($data, ['revalidate' => true]);
        }

        $this->validationGroup = [];

        return $result;
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
    public function reset(array $omit = [])
    {
        $this->submitted = false;
        $this->resetValidation();

        foreach ($this->elements as $element) {
            if (!isset($omit[$element->getName()])) {
                $element->reset($omit);
            }
        }

        $this->dispatch(new FormEvent(FormEvent::RESET_ELEMENT));
    }

    /**
     * @ignore
     */
    public function __debugInfo()
    {
        return [
            'name' => $this->name,
            'required' => $this->required,
            'helper' => $this->helper,
            'attributes' => $this->attributes,
            'options' => $this->options,
            'elements' => $this->elements,
        ];
    }
}
