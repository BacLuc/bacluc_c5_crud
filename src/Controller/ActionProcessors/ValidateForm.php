<?php


namespace BasicTablePackage\Controller\ActionProcessors;


use BasicTablePackage\Controller\ActionProcessor;
use BasicTablePackage\Controller\ActionRegistryFactory;
use BasicTablePackage\Controller\Validation\Validator;

class ValidateForm implements ActionProcessor
{
    /**
     * @var Validator
     */
    private $validator;

    /**
     * PostFormActionProcessor constructor.
     * @param Validator $validator
     */
    public function __construct(
        Validator $validator
    ) {
        $this->validator = $validator;
    }

    function getName(): string
    {
        return ActionRegistryFactory::VALIDATE_FORM;
    }

    function process(array $get, array $post, ...$additionalParameters)
    {
        return $this->validator->validate($post);
    }

}