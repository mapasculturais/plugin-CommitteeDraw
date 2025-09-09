<?php

namespace CommitteeDraw;

use CommitteeDraw\Entities\CommitteeDraw;
use MapasCulturais\App;
use MapasCulturais\Controllers\EntityController;
use MapasCulturais\Entities\File;
use MapasCulturais\i;
use MapasCulturais\Traits\ControllerAPI;
use PhpOffice\PhpSpreadsheet\IOFactory;

class Controller extends EntityController
{
    use ControllerAPI;

    public function __construct()
    {
        parent::__construct();
        $this->entityClassName = CommitteeDraw::class;
    }

    function GET_edit() {
        App::i()->pass();
    }

    function POST_drawCommitteeReviewers() {
        $this->requireAuthentication();
        
        $app = App::i();

        $evaluation_method_configuration_id = $this->data['evaluationMethodConfigurationId'];
        $number_of_valuers = $this->data['numberOfValuers'];
        $file_id = $this->data['fileId'];
        $committee_name = $this->data['committeeName'];

        $file = $app->repo('File')->find($file_id);
        
        if(!$file) {
            $this->errorJson(i::__('Arquivo não encontrado.'));
        }

        $evaluation_method_configuration = $app->repo('EvaluationMethodConfiguration')->find($evaluation_method_configuration_id);

        $committee_draw = new CommitteeDraw($evaluation_method_configuration, $committee_name, $file, $number_of_valuers, $app->user);
        
        if($number_of_valuers > count($committee_draw->inputValuers)) {
            $this->errorJson(i::__('Número de avaliadores maior que o número de avaliadores disponíveis no arquivo.'));
        }
        
        $committee_draw->save(true);
        $committee_draw->createValuersRelations();

        $this->json($committee_draw);
    }

}
