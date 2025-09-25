<?php

namespace CommitteeDraw;

use MapasCulturais\App;
use MapasCulturais\Definitions;
use MapasCulturais\Entities\File;
use MapasCulturais\Entities\Opportunity;
use MapasCulturais\i;
use PhpOffice\PhpSpreadsheet\IOFactory;

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

        $app->hook('mapas.printJsObject:before', function () use($app) {
            $this->jsObject['EntitiesDescription']['committeedraw'] = Entities\CommitteeDraw::getPropertiesMetadata();
        }, 100);

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

        // Adiciona css na single de sorteio
        $app->hook('GET(committeedraw.single):before', function() use($app) {
            $app->view->enqueueStyle('app-v2', 'committee-draws-audit', 'css/committee-draws-audit.css');
        });

         //Adiciona css no template committe-draws-result
        $app->hook('GET(committeedraw.single):before', function() use($app) {
            $app->view->enqueueStyle('app-v2', 'commitee-draws-result', 'css/commitee-draws-result.css');
        });

        //Adiciona css no template file-info
        $app->hook('GET(committeedraw.single):before', function() use($app) {
            $app->view->enqueueStyle('app-v2', 'committee-draws-info', 'css/committee-draws-info.css');
        });

        // Validação do arquivo de sorteio
        $app->hook('entity(OpportunityFile).upload.filesSave:before', function(File $file) use($app) {
            /** @var ControllerUploads $this */
            if($file->group != 'committeeDraw') {
                return;
            }

            $spreadsheet = IOFactory::load($file->tmpFile['tmp_name']);
            $sheet = $spreadsheet->getActiveSheet();

            $highest_row = $sheet->getHighestRow();
            $agent_ids = [];
            for ($row = 2; $row <= $highest_row; $row++) {
                $id = $sheet->getCell('A'.$row)->getValue();

                if (!$id) {
                    $this->errorJson(sprintf(i::__("Linha %d inválida: id ausente."), $row));
                }

                $agent_ids[] = (int) $id;
            }

            foreach ($agent_ids as $agent_id) {
                $agent = $app->repo('Agent')->find($agent_id);

                if (!$agent || $agent->type->id != 1) {
                    $this->errorJson(sprintf(i::__("Agente de id %d inválido."), $agent_id));
                }
            }
        });
    }

    static function getInstance()
    {
        return self::$instance;
    }
}
