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

        $file_group = new Definitions\FileGroup(
            'committeeDraw',
            ['text/csv', 'application/excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel'],
            i::__('O arquivo não é válido'),
        );
        $app->registerFileGroup('opportunity', $file_group);
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
