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
        $extracted_valuator_ids = $this->extractIdsFromSpreadsheet($file);

        if($number_of_valuers > count($extracted_valuator_ids)) {
            $this->errorJson(i::__('Número de avaliadores maior que o número de avaliadores disponíveis no arquivo.'));
        }

        $conn = $app->em->getConnection();
        $draw_number = $conn->fetchOne(
            "
            SELECT 
                COUNT(*) + 1 AS next_draw_number
            FROM 
                committee_draw
            WHERE 
                evaluation_method_configuration_id = :evaluation_method_configuration_id
                AND committee_name = :committee_name
            ",
            [
                'evaluation_method_configuration_id' => $evaluation_method_configuration_id,
                'committee_name' => $committee_name
            ],
        );

        $seed = crc32($evaluation_method_configuration_id . $committee_name . $draw_number);
        $auditable_draw = $this->auditableDraw($seed, $extracted_valuator_ids, $number_of_valuers);

        $evaluation_method_configuration = $app->repo('EvaluationMethodConfiguration')->find($evaluation_method_configuration_id);

        // Adiciona os avaliadores sorteados na comissão de avaliação
        foreach($auditable_draw as $evaluator_id) {
            if($evaluator = $app->repo('Agent')->find($evaluator_id)) {
                $is_reviewer_in_committee = false;

                $agent_relations = $evaluation_method_configuration->getAgentRelations();
                foreach ($agent_relations as $relation) {
                    if ($relation->agent->id == $evaluator->id && $relation->group == $committee_name) {
                        $is_reviewer_in_committee = true;
                        break;
                    }
                }

                if(!$is_reviewer_in_committee) {
                    $evaluation_method_configuration->createAgentRelation($evaluator, $committee_name, true, true);
                }
            }
        }

        $committee_draw = new CommitteeDraw();
        $committee_draw->createTimestamp = new \DateTime();
        $committee_draw->user = $app->user;
        $committee_draw->evaluationMethodConfiguration = $evaluation_method_configuration;
        $committee_draw->committeeName = $committee_name;
        $committee_draw->drawNumber = $draw_number;
        $committee_draw->seed = (string)$seed;
        $committee_draw->fileMd5 = $file->md5;
        $committee_draw->file = $file;
        $committee_draw->numberOfValuers = $number_of_valuers;
        $committee_draw->inputValuers = $extracted_valuator_ids;
        $committee_draw->outputValuers = $auditable_draw;
        $committee_draw->save(true);

        $this->json($committee_draw);
    }

    function auditableDraw(int $seed, array $items, int $number_of_valuers) {
        mt_srand($seed);
        
        $shuffled_items = $items;
        shuffle($shuffled_items);
        
        $winners = array_slice($shuffled_items, 0, $number_of_valuers);

        return $winners;
    }

    function extractIdsFromSpreadsheet(File $file) {
        $spreadsheet = IOFactory::load($file->path);
        $sheet = $spreadsheet->getActiveSheet();
        $data = $sheet->toArray();

        array_shift($data);

        $ids = [];
        foreach ($data as $row) {
            if (isset($row[0])) {
                $ids[] = $row[0];
            }
        }

        return $ids;
    }

}
