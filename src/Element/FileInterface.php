<?php

namespace Elixir\Form\Element;

use Elixir\Filter\FilterInterface;

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
     * @param string|FilterInterface $targetPath
     *
     * @return bool
     */
    public function receive($targetPath = null);
}
