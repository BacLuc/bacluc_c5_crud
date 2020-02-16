<?php

namespace Concrete\Package\BasicTablePackage\Block\BasicTableBlockPackaged;

use BasicTablePackage\Adapters\Concrete5\DIContainerFactory;
use BasicTablePackage\Controller\ActionProcessor;
use BasicTablePackage\Controller\ActionRegistryFactory;
use BasicTablePackage\Controller\BasicTableController;
use BasicTablePackage\Controller\Validation\DropdownFieldValidator;
use BasicTablePackage\Controller\Validation\FieldValidator;
use BasicTablePackage\Controller\Validation\ValidationResult;
use BasicTablePackage\Controller\Validation\ValidationResultItem;
use BasicTablePackage\Entity\ExampleConfigurationEntity;
use BasicTablePackage\Entity\ExampleEntity;
use BasicTablePackage\Entity\ExampleEntityDropdownValueSupplier;
use BasicTablePackage\FieldConfigurationOverride\EntityFieldOverrideBuilder;
use BasicTablePackage\View\FormType;
use BasicTablePackage\View\FormView\DropdownField;
use BasicTablePackage\View\FormView\Field as FormField;
use BasicTablePackage\View\TableView\DropdownField as DropdownTableField;
use BasicTablePackage\View\TableView\Field as TableField;
use Concrete\Core\Block\BlockController;
use Concrete\Core\Error\ErrorList\ErrorList;
use Concrete\Core\Page\Page;
use Concrete\Core\Routing\Redirect;
use Concrete\Package\BasicTablePackage\Controller as PackageController;
use DI\DependencyException;
use DI\NotFoundException;
use Exception;

class Controller extends BlockController
{

    /**
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function view()
    {
        $this->processAction($this->createBasicTableController()->getActionFor(ActionRegistryFactory::SHOW_TABLE));
    }

    private function processAction(ActionProcessor $actionProcessor, ...$additionalParams)
    {
        return $actionProcessor->process($this->request->get(null) ?: [],
            $this->request->post(null) ?: [],
            array_key_exists(0, $additionalParams) ? $additionalParams[0] : null);
    }

    /**
     * @return BasicTableController
     * @throws DependencyException
     * @throws NotFoundException
     * @throws Exception
     */
    private function createBasicTableController(): BasicTableController
    {
        $entityManager = PackageController::getEntityManagerStatic();
        $entityClass = ExampleEntity::class;
        $entityFieldOverrides = new EntityFieldOverrideBuilder($entityClass);

        $dropdownField = "dropdowncolumn";
        $valueSupplier = new ExampleEntityDropdownValueSupplier();
        $entityFieldOverrides->forField($dropdownField)
                             ->forType(FormField::class)
                             ->useFactory(DropdownField::createDropdownField($dropdownField, $valueSupplier))
                             ->forType(TableField::class)
                             ->useFactory(DropdownTableField::createDropdownField($valueSupplier))
                             ->forType(FieldValidator::class)
                             ->useFactory(DropdownFieldValidator::createDropdownFieldValidator($valueSupplier))
                             ->buildField();

        $container = DIContainerFactory::createContainer($this,
            $entityManager,
            $entityClass,
            $entityFieldOverrides->build(),
            FormType::$BLOCK_VIEW);
        return $container->get(BasicTableController::class);
    }

    /**
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function action_add_new_row_form()
    {
        $this->processAction($this->createBasicTableController()
                                  ->getActionFor(ActionRegistryFactory::ADD_NEW_ROW_FORM));
    }

    /**
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function action_edit_row_form($ignored, $editId)
    {
        $this->processAction($this->createBasicTableController()
                                  ->getActionFor(ActionRegistryFactory::EDIT_ROW_FORM),
            $editId);
    }

    /**
     * Attention: all action method are called twice.
     * Because this is a form submission, we stop after the function is executed
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function action_post_form($ignored, $editId = null)
    {
        $this->processAction($this->createBasicTableController()->getActionFor(ActionRegistryFactory::POST_FORM),
            $editId);
        if ($this->blockViewRenderOverride == null) {
            Redirect::page(Page::getCurrentPage())->send();
            exit();
        }
    }

    /**
     * @param $ignored
     * @param $toDeleteId
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function action_delete_entry($ignored, $toDeleteId)
    {
        $this->processAction($this->createBasicTableController()->getActionFor(ActionRegistryFactory::DELETE_ENTRY),
            $toDeleteId);
        Redirect::page(Page::getCurrentPage())->send();
        exit();
    }

    /**
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function action_cancel_form()
    {
        $this->processAction($this->createBasicTableController()->getActionFor(ActionRegistryFactory::SHOW_TABLE));
    }

    /**
     * @param $ignored
     * @param $toShowId
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function action_show_details($ignored, $toShowId)
    {
        $this->processAction($this->createBasicTableController()
                                  ->getActionFor(ActionRegistryFactory::SHOW_ENTRY_DETAILS),
            $toShowId);
    }

    /**
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function add()
    {
        $this->processAction($this->createConfigController()
                                  ->getActionFor(ActionRegistryFactory::ADD_NEW_ROW_FORM));
    }

    /**
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function edit()
    {
        $this->processAction($this->createConfigController()
                                  ->getActionFor(ActionRegistryFactory::EDIT_ROW_FORM),
            $this->bID);
    }

    /**
     * @param array|string|null $args
     * @return bool|ErrorList|void
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function validate($args)
    {

        /** @var $validationResult ValidationResult */
        $validationResult = $this->processAction($this->createConfigController()
                                                      ->getActionFor(ActionRegistryFactory::VALIDATE_FORM),
            $this->bID);
        /** @var $e ErrorList */
        $e = $this->app->make(ErrorList::class);
        foreach ($validationResult as $validationResultItem) {
            /** @var $validationResultItem ValidationResultItem */
            foreach ($validationResultItem->getMessages() as $message) {
                $e->add($validationResultItem->getName() . ": " . $message,
                    $validationResultItem->getName(),
                    $validationResultItem->getName());
            }
        }
        return $e;
    }

    /**
     * @param array $args
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function save($args)
    {
        parent::save($args);
        $this->processAction($this->createConfigController()
                                  ->getActionFor(ActionRegistryFactory::POST_FORM),
            $this->bID);
    }

    /**
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function delete()
    {
        parent::delete();
        $this->processAction($this->createConfigController()
                                  ->getActionFor(ActionRegistryFactory::DELETE_ENTRY),
            $this->bID);
    }

    /**
     * @return BasicTableController
     * @throws DependencyException
     * @throws NotFoundException
     * @throws Exception
     */
    private function createConfigController(): BasicTableController
    {
        $entityManager = PackageController::getEntityManagerStatic();
        $entityClass = ExampleConfigurationEntity::class;
        $entityFieldOverrides = new EntityFieldOverrideBuilder($entityClass);

        $dropdownField = "dropdowncolumn";
        $valueSupplier = new ExampleEntityDropdownValueSupplier();
        $entityFieldOverrides->forField($dropdownField)
                             ->forType(FormField::class)
                             ->useFactory(DropdownField::createDropdownField($dropdownField, $valueSupplier))
                             ->forType(TableField::class)
                             ->useFactory(DropdownTableField::createDropdownField($valueSupplier))
                             ->forType(FieldValidator::class)
                             ->useFactory(DropdownFieldValidator::createDropdownFieldValidator($valueSupplier))
                             ->buildField();

        $container = DIContainerFactory::createContainer($this,
            $entityManager,
            $entityClass,
            $entityFieldOverrides->build(),
            FormType::$BLOCK_CONFIGURATION);
        return $container->get(BasicTableController::class);
    }

}