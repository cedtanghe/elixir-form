<?php

namespace Elixir\Form\Element;

/**
 * @author Cédric Tanghe <ced.tanghe@gmail.com>
 */
interface FileInterface
{
    /**
     * @return boolean
     */
    public function isUploaded();
    
    /**
     * @return boolean
     */
    public function receive();
}
