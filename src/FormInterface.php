<?php

namespace Elixir\Form;

use Elixir\Form\ElementInterface;

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
     * @param string $name
     * @return boolean
     */
    public function hasElement($name);
    
    /**
     * @param ElementInterface $element
     */
    public function addElement(ElementInterface $element);
    
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
