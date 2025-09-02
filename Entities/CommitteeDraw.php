<?php

namespace CommitteeDraw\Entities;

use Doctrine\ORM\Mapping as ORM;
use MapasCulturais\Entities\User;
use MapasCulturais\Entities\File;
use MapasCulturais\Entities\EvaluationMethodConfiguration;

/**
 * @ORM\Table(name="committee_draw")
 * @ORM\Entity(repositoryClass="MapasCulturais\Repository")
 */
class CommitteeDraw extends \MapasCulturais\Entity
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="create_timestamp", type="datetime")
     */
    protected $createTimestamp;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="MapasCulturais\Entities\User", fetch="LAZY")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE", nullable=false)
     */
    protected $user;

    /**
     * @var EvaluationMethodConfiguration
     *
     * @ORM\ManyToOne(targetEntity="MapasCulturais\Entities\EvaluationMethodConfiguration", fetch="LAZY")
     * @ORM\JoinColumn(name="evaluation_method_configuration_id", referencedColumnName="id", onDelete="CASCADE", nullable=false)
     */
    protected $evaluationMethodConfiguration;

    /**
     * @var string
     *
     * @ORM\Column(name="committee_name", type="string")
     */
    protected $committeeName;

    /**
     * @var int
     *
     * @ORM\Column(name="draw_number", type="integer")
     */
    protected $drawNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="seed", type="string")
     */
    protected $seed;

    /**
     * @var string
     *
     * @ORM\Column(name="file_md5", type="string", length=32)
     */
    protected $fileMd5;

    /**
     * @var File
     *
     * @ORM\ManyToOne(targetEntity="MapasCulturais\Entities\File", fetch="LAZY")
     * @ORM\JoinColumn(name="file_id", referencedColumnName="id", onDelete="SET NULL", nullable=true)
     */
    protected $file;

    /**
     * @var int
     *
     * @ORM\Column(name="number_of_valuers", type="integer")
     */
    protected $numberOfValuers;

    /**
     * @var array
     *
     * @ORM\Column(name="input_valuers", type="json")
     */
    protected $inputValuers = [];

    /**
     * @var array
     *
     * @ORM\Column(name="output_valuers", type="json")
     */
    protected $outputValuers = [];
}
