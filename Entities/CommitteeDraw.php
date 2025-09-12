<?php

namespace CommitteeDraw\Entities;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use MapasCulturais\App;
use MapasCulturais\Entities\User;
use MapasCulturais\Entities\EvaluationMethodConfiguration;
use MapasCulturais\Entities\File;
use MapasCulturais\Entities\OpportunityFile;
use MapasCulturais\Entity;
use PhpOffice\PhpSpreadsheet\IOFactory;

/**
 * @ORM\Table(name="committee_draw")
 * @ORM\Entity(repositoryClass="MapasCulturais\Repository")
 */
class CommitteeDraw extends \MapasCulturais\Entity
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="committee_draw_id_seq", allocationSize=1, initialValue=1)
     */
    public $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="create_timestamp", type="datetime")
     */
    protected DateTime $createTimestamp;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="MapasCulturais\Entities\User", fetch="LAZY")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE", nullable=false)
     */
    protected User $user;

    /**
     * @var EvaluationMethodConfiguration
     *
     * @ORM\ManyToOne(targetEntity="MapasCulturais\Entities\EvaluationMethodConfiguration", fetch="LAZY")
     * @ORM\JoinColumn(name="evaluation_method_configuration_id", referencedColumnName="id", onDelete="CASCADE", nullable=false)
     */
    protected EvaluationMethodConfiguration $evaluationMethodConfiguration;

    /**
     * @var string
     *
     * @ORM\Column(name="committee_name", type="string")
     */
    protected string $committeeName;

    /**
     * @var int
     *
     * @ORM\Column(name="draw_number", type="integer")
     */
    protected int $drawNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="seed", type="string")
     */
    protected string $seed;

    /**
     * @var string
     *
     * @ORM\Column(name="file_md5", type="string", length=32)
     */
    protected string $fileMd5;

    /**
     * @var File
     *
     * @ORM\ManyToOne(targetEntity="MapasCulturais\Entities\File", fetch="LAZY")
     * @ORM\JoinColumn(name="file_id", referencedColumnName="id", onDelete="SET NULL", nullable=true)
     */
    protected File $file;

    /**
     * @var int
     *
     * @ORM\Column(name="number_of_valuers", type="integer")
     */
    protected int $numberOfValuers;

    /**
     * @var array
     *
     * @ORM\Column(name="input_valuers", type="json")
     */
    protected array $inputValuers = [];

    /**
     * @var array
     *
     * @ORM\Column(name="output_valuers", type="json")
     */
    protected array $outputValuers = [];

    /**
     * @var int
     *
     * @ORM\Column(name="status", type="smallint",  nullable=false)
     */
    protected int $status = Entity::STATUS_ENABLED;

    public static function getControllerId()
    {
        return 'committeedraw';
    }

    public function __construct(EvaluationMethodConfiguration $evaluation_method_configuration, string $committee_name, OpportunityFile $file, int $number_of_valuers, ?User $user = null)
    {
        parent::__construct();

        $app = App::i();
        
        $this->evaluationMethodConfiguration = $evaluation_method_configuration;
        $this->committeeName = $committee_name;
        $this->file = $file;
        $this->fileMd5 = $file->md5;
        $this->numberOfValuers = $number_of_valuers;
        $this->user = $user ?: $app->user;
        $this->drawNumber = self::nextDrawNumber($evaluation_method_configuration, $committee_name);
        $this->seed = $this->generateSeed();
        $this->inputValuers = $this->extractIdsFromSpreadsheet();
        $this->outputValuers = $this->auditableDraw();
    }

    /**
     * Ids dos agentes da planilha
     *
     * @return int[] 
     */
    public function extractIdsFromSpreadsheet(): array {
        $spreadsheet = IOFactory::load($this->file->path);
        $sheet = $spreadsheet->getActiveSheet();
        $data = $sheet->toArray();

        array_shift($data);

        $ids = [];
        foreach ($data as $row) {
            if (isset($row[0])) {
                $ids[] = (int) $row[0];
            }
        }

        return $ids;
    }

    public function generateSeed() : int {
        $evaluation_method_configuration_id = $this->evaluationMethodConfiguration->id;
        $committee_name = $this->committeeName;
        $draw_number = $this->drawNumber;
        
        $valuers_ids = $this->inputValuers;
        sort($valuers_ids);
        $valuers_ids = json_encode($valuers_ids);

        $timestamp = $this->createTimestamp->format('Y-m-d H:i:s');

        $seed = crc32("$evaluation_method_configuration_id:$committee_name:$draw_number:$timestamp:$valuers_ids");

        return $seed;
    }

    /** 
     * Retorna os ids dos agentes sorteados
     *
     * @return int[] 
     */
    public function auditableDraw(): array {
        $valuer_ids = $this->inputValuers;

        // Ordena os IDs para garantir resultado consistente, independente da ordem de entrada
        sort($valuer_ids);
        
        // Inicializa o gerador com o seed
        srand($this->seed);

        // embaralha os ids dos avaliadores baseado
        shuffle($valuer_ids); 
        
        // Seleciona os N primeiros avaliadores
        $selected_valuers = array_slice($valuer_ids, 0, $this->numberOfValuers);

        return $selected_valuers;
    }

    public static function nextDrawNumber(EvaluationMethodConfiguration $evaluation_method_configuration, string $committee_name) : int{
        $app = App::i();
        
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
                'evaluation_method_configuration_id' => $evaluation_method_configuration->id,
                'committee_name' => $committee_name
            ],
        );

        return $draw_number;
    }

    public function createValuersRelations(): void {
        $this->evaluationMethodConfiguration->checkPermission('manageEvaluationCommittee');
        $app = App::i();

        foreach($this->outputValuers as $evaluator_id) {
            if($evaluator = $app->repo('Agent')->find($evaluator_id)) {
                $is_reviewer_in_committee = false;

                $agent_relations = $this->evaluationMethodConfiguration->getAgentRelations();
                foreach ($agent_relations as $relation) {
                    if ($relation->agent->id == $evaluator->id && $relation->group == $this->committeeName) {
                        $is_reviewer_in_committee = true;
                        break;
                    }
                }

                if(!$is_reviewer_in_committee) {
                    $this->evaluationMethodConfiguration->createAgentRelation($evaluator, $this->committeeName, true, true);
                }
            }
        }
    }

    protected function canUserCreate($user){
        return $this->evaluationMethodConfiguration->canUser('manageEvaluationCommittee', $user);
    }

    protected function canUserModify($user){
        return $this->evaluationMethodConfiguration->canUser('manageEvaluationCommittee', $user);
    }

    protected function canUserRemove($user){
        return $this->evaluationMethodConfiguration->canUser('manageEvaluationCommittee', $user);
    }

}
