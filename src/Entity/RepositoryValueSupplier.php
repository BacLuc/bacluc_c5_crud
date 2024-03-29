<?php

namespace BaclucC5Crud\Entity;

use function BaclucC5Crud\Lib\collect;

class RepositoryValueSupplier implements ValueSupplier {
    /**
     * @var Repository
     */
    private $repository;

    public function __construct(Repository $repository) {
        $this->repository = $repository;
    }

    public function getValues(): array {
        return collect($this->repository->getAll())
            ->keyBy(function (Identifiable $value) {
                return $value->getId();
            })
            ->toArray()
        ;
    }
}
