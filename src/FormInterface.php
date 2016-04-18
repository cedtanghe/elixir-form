<?php

namespace Elixir\Form;

use Elixir\Form\ElementInterface;
use Elixir\Form\FieldInterface;

/**
 * @author Cédric Tanghe <ced.tanghe@gmail.com>
 */
interface FormInterface extends ElementInterface
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
     * @var string
     */
    const METHOD_OPTIONS = 'method_options';
    
    /**
     * @var string
     */
    const ENCTYPE_OPTIONS = 'enctype_options';
    
    /**
     * {@internal}
     */
    public function setParent(self $form);

    /**
     * {@internal}
     */
    public function getParent();

    /**
     * @param string $name
     * @return boolean
     */
    public function hasElement($name);
    
    /**
     * @param FieldInterface|FormInterface $element
     */
    public function addElement($element);
    
    /**
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public function getElement($name, $default = null);
    
    /**
     * @return void
     */
    public function removeElement($name);
    
    /**
     * @return array
     */
    public function getElements();
    
    /**
     * @param array $elements
     */
    public function setElements(array $elements);
    
    /**
     * @param array|\ArrayAccess $data
     */
    public function populate($data);
    
    /**
     * @param array|\ArrayAccess $data
     */
    public function bind($data);

    /**
     * @param array $omit
     */
    public function reset(array $omit = []);
}
