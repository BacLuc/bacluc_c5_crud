<?php


namespace BasicTablePackage\Controller;


use BasicTablePackage\FormViewService;

class ShowFormActionProcessor implements ActionProcessor
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
        return ActionRegistryFactory::ADD_NEW_ROW_FORM;
    }

    function process (array $get, array $post)
    {
        $formView = $this->formViewService->getFormView();
        $this->variableSetter->set("fields", $formView->getFields());
        $this->renderer->render(self::FORM_VIEW);
    }

}