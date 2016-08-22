<?php

namespace Elixir\Form;

/**
 * @author Cédric Tanghe <ced.tanghe@gmail.com>
 */
interface FormInterface extends ElementInterface
{
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
     * @param array|\ArrayAccess $data
     */
    public function build($data = null);

    /**
     * @return bool
     */
    public function isBuilt();

    /**
     * @param string $name
     * @param bool   $sub
     *
     * @return bool
     */
    public function hasElement($name, $sub = false);

    /**
     * @param ElementInterface $element
     */
    public function addElement(ElementInterface $element);

    /**
     * @param string $name
     * @param bool   $sub
     * @param mixed  $default
     *
     * @return mixed
     */
    public function getElement($name, $sub = false, $default = null);

    /**
     * @param string $name
     * @param bool   $sub
     */
    public function removeElement($name, $sub = false);

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
     *
     * @return bool
     */
    public function submit($data = null);

    /**
     * @return bool
     */
    public function isSubmitted();

    /**
     * @param array $omit
     */
    public function reset(array $omit = []);
}
