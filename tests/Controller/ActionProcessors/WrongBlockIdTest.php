<?php

namespace BaclucC5Crud\Test\Controller\ActionProcessors;

use BaclucC5Crud\Controller\ActionRegistryFactory;
use BaclucC5Crud\Controller\CrudController;
use BaclucC5Crud\Entity\ExampleEntity;
use BaclucC5Crud\Test\Constraints\Matchers;
use BaclucC5Crud\Test\DIContainerFactory;
use DI\Container;
use DI\DependencyException;
use DI\NotFoundException;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
class WrongBlockIdTest extends TestCase {
    /**
     * @var CrudController
     */
    private $crudController;

    /**
     * @throws DependencyException
     * @throws NotFoundException
     */
    protected function setUp(): void {
        /** @var EntityManager $entityManager */
        $entityManager = $this->createMock(EntityManager::class);

        /** @var Container $container */
        $container = DIContainerFactory::createContainer($entityManager, ExampleEntity::class);
        ExampleEntityConstants::addReferencedEntityTestValues($container);
        $this->crudController = $container->get(CrudController::class);
    }

    public function testPostFormNewEntry() {
        ob_start();
        $this->crudController->getActionFor(ActionRegistryFactory::POST_FORM, '1')
            ->process([], ExampleEntityConstants::ENTRY_1_POST)
        ;

        $output = ob_get_clean();
        $this->assertStringNotContainsString(ExampleEntityConstants::DATE_VALUE_1, $output);
        $this->assertThat($output, Matchers::stringContainsAll(array_keys(ExampleEntityConstants::ENTRY_1_POST)));
    }
}
