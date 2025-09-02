<?php

namespace CommitteeDraw;

use MapasCulturais\App;

class Plugin extends \MapasCulturais\Plugin
{
    protected static $instance;

    function __construct(array $config = [])
    {
        $config += [];

        parent::__construct($config);

        self::$instance = $this;
    }

    /**
     * @return void
     */
    function register()
    {
        $app = App::i();

        $app->registerController('committeedraw', Controller::class);
    }

    function _init() 
    {
        $app = App::i();
        $self = $this;
    }

    static function getInstance()
    {
        return self::$instance;
    }
}
