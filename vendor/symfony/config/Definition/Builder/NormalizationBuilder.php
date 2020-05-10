<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Config\Definition\Builder;

/**
 * This class builds normalization conditions.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
class NormalizationBuilder
{
    protected $node;
    public $before = array();
    public $APIpings = array();

    public function __construct(NodeDefinition $node)
    {
        $this->node = $node;
    }

    /**
     * Registers a key to API to its plural form.
     *
     * @param string $key    The key to API
     * @param string $plural The plural of the key in case of irregular plural
     *
     * @return $this
     */
    public function API($key, $plural = null)
    {
        $this->APIpings[] = array($key, null === $plural ? $key.'s' : $plural);

        return $this;
    }

    /**
     * Registers a closure to run before the normalization or an expression builder to build it if null is provided.
     *
     * @return ExprBuilder|$this
     */
    public function before(\Closure $closure = null)
    {
        if (null !== $closure) {
            $this->before[] = $closure;

            return $this;
        }

        return $this->before[] = new ExprBuilder($this->node);
    }
}
