<?php

namespace Elixir\Form\Extension;

use Elixir\Form\ElementInterface;
use Elixir\Form\FormEvent;

/**
 * @author Cédric Tanghe <ced.tanghe@gmail.com>
 */
class Reference implements ExtensionInterface
{
    /**
     * @var ElementInterface
     */
    protected $form;

    /**
     * @var ElementInterface
     */
    protected $inputReference;

    /**
     * @var string
     */
    protected $format;

    /**
     * @var array
     */
    protected $references = [];

    /**
     * @param ElementInterface $inputReference
     * @param string           $format
     */
    public function __construct(ElementInterface $inputReference, $format)
    {
        $this->inputReference = $inputReference;
        $this->format = $format;
    }

    /**
     * @return string
     */
    public function getInputReference()
    {
        return $this->inputReference;
    }

    /**
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * {@inheritdoc}
     */
    public function load(ElementInterface $form)
    {
        $this->form = $form;
        $this->form->addListener(FormEvent::PRE_POPULATE, [$this, 'onPrePopulate']);
        $this->form->addListener(FormEvent::PRE_SUBMIT, [$this, 'onPreSubmit']);
    }

    /**
     * @internal
     *
     * @param FormEvent $e
     */
    public function onPrePopulate(FormEvent $e)
    {
        $data = $e->getData();

        if (isset($data[$this->inputReference->getName()])) {
            $this->references = [];

            $pattern = preg_replace_callback(
                '/{([^}]+)}/',
                function ($matches) {
                    return '(?P<'.$this->protect($matches[1]).'>.*)';
                },
                $this->format
            );

            if (preg_match('/^'.preg_quote($pattern, '/').'$/', $data[$this->inputReference->getName()], $matches)) {
                foreach ($matches as $key => $value) {
                    if (isset($this->references[$key])) {
                        $data[$this->references[$key]] = $value;
                    }
                }
            }
        }

        $e->setData($data);
    }

    /**
     * @internal
     *
     * @param FormEvent $e
     */
    public function onPreSubmit(FormEvent $e)
    {
        $data = $e->getData();

        $value = preg_replace_callback('/{([^}]+)}/', function ($matches) use ($data) {
            if (array_key_exists($matches[1], $data)) {
                return $data[$matches[1]];
            }

            $item = $this->form->getElement($matches[1]);

            return $item->getValue(ElementInterface::VALUE_RAW);
        },
        $this->format);

        $data[$this->inputReference->getName()] = $value;
        $e->setData($data);
    }

    /**
     * @param string $value
     *
     * @return string
     */
    protected function protect($value)
    {
        $key = str_replace(str_split('.\+*?[^]$(){}=!<>|:-%'), '', $value);
        $this->references[$key] = $value;

        return $key;
    }

    /**
     * {@inheritdoc}
     */
    public function unload()
    {
        if ($this->form) {
            $this->form->removeListener(FormEvent::PRE_POPULATE, [$this, 'onPrePopulate']);
            $this->form->removeListener(FormEvent::PRE_SUBMIT, [$this, 'onPreSubmit']);
        }
    }
}
