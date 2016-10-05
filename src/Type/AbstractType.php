<?php

namespace Application\Type;

use App\Application;
use Symfony\Component\Form\FormFactory;

abstract class AbstractType
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * @var FormFactory
     */
    protected $factory;


    /**
     * @var array
     */
    protected $errors;

    /**
     * AbstractType constructor.
     *
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->factory = $this->app['form.factory'];
    }
}
