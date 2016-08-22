<?php

namespace Elixir\Form;

/**
 * @author Cédric Tanghe <ced.tanghe@gmail.com>
 */
interface FieldsetInterface extends FormInterface
{
    /**
     * @param string $value
     */
    public function setLegend($value);

    /**
     * @return string
     */
    public function getLegend();
}
