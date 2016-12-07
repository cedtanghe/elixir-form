<?php

namespace Elixir\Form;

/**
 * @author Cédric Tanghe <ced.tanghe@gmail.com>
 */
interface CollectionInterface
{
    /**
     * @var string
     */
    const MIN_CARDINALITY = 'min_cardinality';

    /**
     * @var string
     */
    const MAX_CARDINALITY = 'max_cardinality';

    /**
     * @param int $value
     */
    public function setMinCardinality($value);

    /**
     * @return int
     */
    public function getMinCardinality();

    /**
     * @param int $value
     */
    public function setMaxCardinality($value = -1);

    /**
     * @return int
     */
    public function getMaxCardinality();

    /**
     * @param bool $value
     */
    public function setSortable($value);

    /**
     * @return bool
     */
    public function isSortable();
}
