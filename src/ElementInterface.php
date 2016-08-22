<?php

namespace Elixir\Form;

use Elixir\Filter\FilterizableInterface;
use Elixir\Validator\ValidatableInterface;

/**
 * @author Cédric Tanghe <ced.tanghe@gmail.com>
 */
interface ElementInterface extends FilterizableInterface, ValidatableInterface
{
    /**
     * @var string
     */
    const VALUE_NORMALIZED = 'normalized';

    /**
     * @var string
     */
    const VALUE_RAW = 'raw';

    /**
     * @var string
     */
    const FILTER_MODE = 'filter_mode';

    /**
     * @var int
     */
    const FILTER_IN = 1;

    /**
     * @var int
     */
    const FILTER_OUT = 2;

    /**
     * @var int
     */
    const FILTER_BOTH = 3;

    /**
     * @param string $value
     */
    public function setName($value);

    /**
     * @return string
     */
    public function getName();

    /**
     * {@internal}.
     */
    public function setParent(self $value);

    /**
     * {@internal}.
     */
    public function getParent();

    /**
     * @param string|callable $value
     */
    public function setHelper($value);

    /**
     * @return string|callable
     */
    public function getHelper();

    /**
     * @param mixed $value
     */
    public function setValue($value, $format = self::VALUE_RAW);

    /**
     * @return mixed
     */
    public function getValue($format = self::VALUE_NORMALIZED);

    /**
     * @param string|array $key
     *
     * @return bool
     */
    public function hasAttribute($key);

    /**
     * @param string|array $key
     * @param mixed        $default
     *
     * @return mixed
     */
    public function getAttribute($key, $default = null);

    /**
     * @param string|array $key
     * @param mixed        $value
     */
    public function setAttribute($key, $value);

    /**
     * @param string|array $key
     */
    public function removeAttribute($key);

    /**
     * @param array $data
     */
    public function setAttributes(array $data);

    /**
     * @return array
     */
    public function getAttributes();

    /**
     * @param string|array $key
     *
     * @return bool
     */
    public function hasOption($key);

    /**
     * @param string|array $key
     * @param mixed        $default
     *
     * @return mixed
     */
    public function getOption($key, $default = null);

    /**
     * @param string|array $key
     * @param mixed        $value
     */
    public function setOption($key, $value);

    /**
     * @param string|array $key
     */
    public function removeOption($key);

    /**
     * @param array $data
     */
    public function setOptions(array $data);

    /**
     * @return array
     */
    public function getOptions();

    /**
     * @param mixed $args
     */
    public function prepare($args = null);

    /**
     * @return bool
     */
    public function isPrepared();

    /**
     * @param bool $value
     */
    public function setRequired($value);

    /**
     * @return bool
     */
    public function isRequired();

    /**
     * @return bool
     */
    public function isEmpty();

    /**
     * @return bool
     */
    public function isEligible();

    /**
     * @return bool
     */
    public function isValid();
}
