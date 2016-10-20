<?php

namespace Elixir\Form\Element;

/**
 * @author Cédric Tanghe <ced.tanghe@gmail.com>
 */
trait MultipleChoiceTrait
{
    /**
     * @var string
     */
    protected $dataType = self::DATA_USE_KEYS;

    /**
     * @var array
     */
    protected $data = [];
    
    /**
     * {@inheritdoc}
     */
    public function setValue($value, $format = self::VALUE_RAW)
    {
        $in = function ($value) {
            foreach ((array) $value as $v) {
                foreach ($this->data as $key => $value) {
                    if (is_array($value)) {
                        if ($in($v, $value, $this->dataType)) {
                            return true;
                        }
                    } else {
                        if ($this->dataType === self::DATA_USE_KEYS) {
                            if ($key == $v) {
                                return true;
                            }
                        } elseif ($value == $v) {
                            return true;
                        }
                    }
                }
            }

            return false;
        };

        parent::setValue($value, $format);

        if (null !== $this->value && !$in($this->value)) {
            $this->value = null;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setDataType($value)
    {
        $this->dataType = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function getDataType()
    {
        return $this->dataType;
    }

    /**
     * {@inheritdoc}
     */
    public function setData($value, $dataType = null)
    {
        $this->data = $value;

        if (null !== $dataType) {
            $this->setDataType($dataType);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        return $this->getData();
    }

    /**
     * {@inheritdoc}
     */
    public function prepare($args = null)
    {
        $this->setOption('data', $this->data);
        $this->setOption('data-use-keys', $this->dataType);

        parent::prepare($args);
    }
}
