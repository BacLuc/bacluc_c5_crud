<?php

namespace BaclucC5Crud\Controller\Validation;

use function BaclucC5Crud\Lib\collect;

class Validator {
    /**
     * @var ValidationConfiguration
     */
    private $validationConfiguration;

    public function __construct(ValidationConfigurationFactory $validationConfigurationFactory) {
        $this->validationConfiguration = $validationConfigurationFactory->createConfiguration();
    }

    public function validate($post): ValidationResult {
        return new ValidationResult(collect($this->validationConfiguration)
            ->map(function (FieldValidator $fieldValidator) use (
                $post
            ) {
                return $fieldValidator->validate($post);
            })
            ->toArray());
    }
}
