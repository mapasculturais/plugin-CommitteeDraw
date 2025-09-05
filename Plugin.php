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

        // Exibição da listagem de sorteios na single da oportunidade
        $app->hook('template(opportunity.single.entity-seals):begin', function() {
            $entity = $this->controller->requestedEntity;

            if($entity->getClassName() == Opportunity::class) {
                $this->part('committee-draws-overview');
            }
        });

        // Exibição do componente de sorteio na tela de comissão de avaliação
        $app->hook('component(opportunity-evaluation-committee).select-entity:end', function() {
            $this->part('committee-draws');
        });
    }

    static function getInstance()
    {
        return self::$instance;
    }
}
