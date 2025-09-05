<?php

namespace CommitteeDraw\Entities;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use MapasCulturais\Entities\User;
use MapasCulturais\Entities\File;
use MapasCulturais\Entities\EvaluationMethodConfiguration;
use MapasCulturais\Entity;

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

    protected function canUserCreate($user){
        return $this->evaluationMethodConfiguration->canUser('create', $user);
    }

    protected function canUserModify($user){
        return $this->evaluationMethodConfiguration->canUser('modify', $user);
    }

    protected function canUserRemove($user){
        return $this->evaluationMethodConfiguration->canUser('remove', $user);
    }

}
