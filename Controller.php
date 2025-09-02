<?php

namespace CommitteeDraw;

use CommitteeDraw\Entities\CommitteeDraw;
use MapasCulturais\App;
use MapasCulturais\Controllers\EntityController;
use MapasCulturais\Traits\ControllerAPI;

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
}
