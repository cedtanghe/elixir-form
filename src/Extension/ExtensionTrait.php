<?php

namespace Elixir\Form\Extension;

use Elixir\Form\Extension\ExtensionInterface;

/**
 * @author Cédric Tanghe <ced.tanghe@gmail.com>
 */
class ExtensionTrait 
{
    /**
     * @param ExtensionInterface $extension
     */
    public function registerExtension(ExtensionInterface $extension)
    {
        $extension->load($this);
    }
    
    /**
     * @param ExtensionInterface $extension
     */
    public function unregisterExtension(ExtensionInterface $extension)
    {
        $extension->unload();
    }
}
