<?php

namespace Elixir\Form;

use Elixir\Dispatcher\DispatcherInterface;
use Elixir\Form\Field\FieldInterface;

/**
 * @author Cédric Tanghe <ced.tanghe@gmail.com>
 */
interface FormInterface extends DispatcherInterface 
{
    /**
     * @var string
     */
    const METHOD_GET = 'get';

    /**
     * @var string
     */
    const METHOD_POST = 'post';

    /**
     * @var string
     */
    const ENCTYPE_URLENCODED = 'application/x-www-form-urlencoded';

    /**
     * @var string
     */
    const ENCTYPE_MULTIPART = 'multipart/form-data';

    /**
     * @var string
     */
    const ENCTYPE_TEXT_PLAIN = 'text/plain';

    /**
     * @internal
     */
    public function setParent(self $form);

    /**
     * @internal
     */
    public function getParent();
    
    /**
     * @param string $name
     */
    public function setName($name);

    /**
     * @return string
     */
    public function getName();

    /**
     * @param string|callable $value
     */
    public function setHelper($value);

    /**
     * @return string|callable
     */
    public function getHelper();

    /**
     * @return array
     */
    public function getAttributes();

    /**
     * @param array $data
     */
    public function setAttributes(array $data);

    /**
     * @return array
     */
    public function getOptions();

    /**
     * @param array $data
     */
    public function setOptions(array $data);

    /**
     * @param FieldInterface|FormInterface $item
     */
    public function add($item);
    
    /**
     * @param string $name
     * @return FieldInterface|FormInterface
     */
    public function get($name);

    /**
     * @param string $name
     */
    public function remove($name);
    
    /**
     * @return array
     */
    public function all();
    
    /**
     * @param array $data
     */
    public function bindValues(array $data);
    
    /**
     * @return array
     */
    public function getValues();
    
    /**
     * @param array $data
     * @return boolean
     */
    public function submit(array $data = null);

    /**
     * @param array $data
     */
    public function bindErrors(array $data);
    
    /**
     * @return boolean
     */
    public function hasError();

    /**
     * @return array
     */
    public function getErrorMessages();
    
    /**
     * @return boolean
     */
    public function isEligible();
    
    /**
     * @return void
     */
    public function prepare();

    /**
     * @return boolean
     */
    public function isPrepared();

    /**
     * @return boolean
     */
    public function isEmpty();
    
    /**
     * @param array $omit
     */
    public function reset(array $omit = []);
}
