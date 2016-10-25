<?php

namespace Elixir\Form\Element;

use Elixir\Filter\FilterInterface;
use Elixir\Form\FormEvent;
use Elixir\Security\CSRF as CSRFContext;
use Elixir\Validator\CSRF as CSRFValidator;
use Elixir\Validator\ValidatorInterface;

/**
 * @author Cédric Tanghe <ced.tanghe@gmail.com>
 */
class CSRF extends Input
{
    /**
     * @var ValidatorInterface
     */
    protected $CSRFValidator;
    
    /**
     * @var array
     */
    protected $CSRFValidatorOptions = [];
    
     /**
     * @param ValidatorInterface $validator
     * @param array $options
     * @throws \InvalidArgumentException
     */
    public function setCSRFValidator(ValidatorInterface $validator, array $options = [])
    {
        $this->CSRFValidator = $validator;
        
        if (!method_exists($this->CSRFValidator, 'createToken'))
        {
            throw new \InvalidArgumentException('The validator is invalid, a method "createToken" is required.');
        }
        
        if(count($options) > 0)
        {
            $this->setCSRFValidatorOptions($options);
        }
    }
    
    /**
     * @return ValidatorInterface
     */
    public function getCSRFValidator()
    {
        if(null === $this->CSRFValidator)
        {
            $this->CSRFValidator = new CSRFValidator(new CSRFContext());
        }
        
        return $this->CSRFValidator;
    }
    
    /**
     * @param array $options
     */
    public function setCSRFValidatorOptions(array $options = [])
    {
        $this->CSRFValidatorOptions = $options;
    }
    
    /**
     * @return array
     */
    public function getCSRFValidatorOptions()
    {
        return $this->CSRFValidatorOptions;
    }
    
    /**
     * {@inheritdoc}
     */
    public function addValidator(ValidatorInterface $validator, array $options = [])
    {
        $this->setCSRFValidator($validator);
        $this->setCSRFValidatorOptions($options);
    }
    
    /**
     * {@inheritdoc}
     * @throws \LogicException
     */
    public function addFilter(FilterInterface $filter, array $options = [])
    {
        throw new \LogicException('No filter available for CSRF field.');
    }
    
    /**
     * {@inheritdoc}
     */
    public function prepare($args = null)
    {
        $this->setRequired(true);
        $this->setType(self::HIDDEN);
        
        $this->setValue(
            call_user_func_array(
                [$this->getCSRFValidator(), 'createToken'], 
                [$this->name, $this->getCSRFValidatorOptions()]
            ), 
            self::VALUE_RAW
        );
        
        $this->setAttribute('name', $this->name);
        
        $this->prepared = true;
        $this->dispatch(new FormEvent(FormEvent::PREPARED));
    }
    
    /**
     * {@inheritdoc}
     */
    public function validate($data = null, array $options = [])
    {
        $this->resetValidation();

        if (!empty($data)) {
            $this->setValue($data, self::VALUE_RAW);
        }

        $this->dispatch(new FormEvent(FormEvent::VALIDATE_ELEMENT));

        foreach ($this->validators as $config) {
            $validator = $config['validator'];
            $o = $config['options'] + $options + ['element_context' => $this];

            $valid = $validator->validate($this->getName(), $o);
            
            if (!$valid) {
                $this->validationErrors += $validator->getErrors();

                if ($this->breakChainValidationOnFailure) {
                    return false;
                }
            }
        }

        $this->validationErrors = array_unique($this->validationErrors);

        return $this->hasError();
    }
}
