<?php

namespace Application\Type\Contracts;

use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormView;

interface TypeInterface
{
    /**
     * @return Form
     */
    public function getForm();

    /**
     * @return FormView
     */
    public function createView();
}
