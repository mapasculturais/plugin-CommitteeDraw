<?php
use MapasCulturais\i;

// $this->layout = 'entity';

$this->import('
    mc-container
');

$committee_draw = $this->controller->requestedEntity;
$opportunity = $committee_draw->evaluationMethodConfiguration->opportunity;

$this->breadcrumb = [
    ['label' => i::__('Inicio'), 'url' => $app->createUrl('panel', 'index')],
    ['label' => $opportunity->name, 'url' => $app->createUrl('opportunity', 'single', [$opportunity->id])],
    ['label' => i::__('Sorteio de número') . ' ' . $committee_draw->drawNumber, 'url' => $app->createUrl('committeedraw', 'single', [$committee_draw->id])],
];
?>
<div class="main-app">
    <!-- <mc-breadcrumb></mc-breadcrumb> -->

    <mc-container>
        <main class="grid-12">
            <div >
                
            </div>
        </main>
    </mc-container>
</div>