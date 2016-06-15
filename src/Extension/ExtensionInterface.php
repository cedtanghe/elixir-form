<?php

namespace Elixir\Form\Extension;

use Elixir\Form\ElementInterface;

/**
 * @author Cédric Tanghe <ced.tanghe@gmail.com>
 */
interface ExtensionInterface
{
    /**
     * @param ElementInterface $form
     */
    public function load(ElementInterface $form);
    
    /**
     * @return void
     */
    public function unload();
}
