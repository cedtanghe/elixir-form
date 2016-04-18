<?php

namespace Elixir\Form;

use Elixir\Form\ElementInterface;

/**
 * @author Cédric Tanghe <ced.tanghe@gmail.com>
 */
interface FieldInterface extends ElementInterface
{
    /**
     * @var string
     */
    const LABEL_OPTIONS = 'label_options';
    
    /**
     * @var string
     */
    const DESCRIPTION_OPTIONS = 'description_options';
    
    /**
     * @param string $value
     */
    public function setLabel($value);
    
    /**
     * @retrun string
     */
    public function getLabel();
    
    /**
     * @param string $value
     */
    public function setDescription($value);
    
    /**
     * @retrun string
     */
    public function getDescription();
    
    /**
     * @return void
     */
    public function reset();
}
