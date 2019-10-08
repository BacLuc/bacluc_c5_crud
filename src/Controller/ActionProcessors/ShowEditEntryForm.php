<?php


namespace BasicTablePackage\Controller\ActionProcessors;


use BasicTablePackage\Controller\ActionProcessor;
use BasicTablePackage\Controller\ActionRegistryFactory;
use BasicTablePackage\Controller\Renderer;
use BasicTablePackage\Controller\VariableSetter;
use BasicTablePackage\FormViewService;

class ShowEditEntryForm implements ActionProcessor
{
    const FORM_VIEW = "view/form";
    /**
     * @var FormViewService
     */
    private $formViewService;
    /**
     * @var VariableSetter
     */
    private $variableSetter;
    /**
     * @var Renderer
     */
    private $renderer;

    /**
     * ShowFormActionProcessor constructor.
     * @param FormViewService $formViewService
     * @param VariableSetter $variableSetter
     * @param Renderer $renderer
     */
    public function __construct (FormViewService $formViewService, VariableSetter $variableSetter, Renderer $renderer)
    {
        $this->formViewService = $formViewService;
        $this->variableSetter = $variableSetter;
        $this->renderer = $renderer;
    }


    function getName (): string
    {
        return ActionRegistryFactory::EDIT_ROW_FORM;
    }

    function process (array $get, array $post, ...$additionalParameters)
    {
        $editId = null;
        if (count($additionalParameters) == 1) {
            $editId = $additionalParameters[0];
        }
        $formView = $this->formViewService->getFormView($editId);
        $this->variableSetter->set("fields", $formView->getFields());
        $this->variableSetter->set("editId", $editId);
        $this->renderer->render(self::FORM_VIEW);
    }

}