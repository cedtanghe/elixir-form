<?php

namespace Elixir\Form;

use Elixir\Dispatcher\DispatcherInterface;
use Elixir\Dispatcher\DispatcherTrait;
use function Elixir\STDLib\camelize;

/**
 * @author Cédric Tanghe <ced.tanghe@gmail.com>
 */
class FormFactory implements DispatcherInterface
{
    use DispatcherTrait;
    
    /**
     * @param array $config
     *
     * @return ElementInterface
     *
     * @throws \InvalidArgumentException
     */
    public function create(array $config)
    {
        $type = isset($config['type']) ? $config['type'] : (is_string($config) ? $config : null);
        unset($config['type']);

        $name = isset($config['name']) ? $config['name'] : null;
        unset($config['name']);

        if (!$type) {
            throw new \InvalidArgumentException('No type defined');
        }

        if ($type instanceof ElementInterface) {
            $element = $type;
        } elseif (is_callable($type)) {
            $element = call_user_func_array($type, [$name]);
        } else {
            $element = new $type($name);
        }

        if (null !== $name && null === $element->getName()) {
            $element->setName($name);
        }

        if (isset($config['elements'])) {
            foreach ((array) $config['elements'] as &$element) {
                if (is_array($element)) {
                    $element = $this->create($element);
                }
            }
        }

        if (isset($config['subscriber'])) {
            $element->addSubscriber($config['subscriber']);
            unset($config['subscriber']);
        }

        if (isset($config['listeners'])) {
            foreach ($config['listeners'] as $event => $callback) {
                $element->addListener($event, $callback);
            }
        }

        foreach ($config as $method => $arguments) {
            $m = 'set'.camelize($method);

            if (method_exists($element, $m)) {
                if (!is_array($arguments)) {
                    $arguments = [$arguments];
                }

                call_user_func_array([$element, $m], $arguments);
            }
        }

        $this->dispatch(new FormEvent(FormEvent::ELEMENT_CREATED, ['element' => $element]));

        return $element;
    }
}
