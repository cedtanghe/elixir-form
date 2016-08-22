<?php

namespace Elixir\Form\Element;

/**
 * @author Cédric Tanghe <ced.tanghe@gmail.com>
 */
interface FileInterface
{
    /**
     * @return bool
     */
    public function hasMultipleFiles();

    /**
     * @return bool
     */
    public function isUploaded();

    /**
     * @return bool
     */
    public function receive();
}
