<?php
use MapasCulturais\i;

$this->layout = 'entity';

$this->import('
    committee-draws-audit
    mc-breadcrumb
    mc-container
');

$committee_draw = $this->controller->requestedEntity;
$opportunity = $committee_draw->evaluationMethodConfiguration->opportunity;
$entity_name = sprintf(
    i::__('%s - %s - sorteio #%d'),
    $committee_draw->evaluationMethodConfiguration->name,
    $committee_draw->committeeName,
    $committee_draw->drawNumber
);

$this->breadcrumb = [
    ['label' => i::__('Inicio'), 'url' => $app->createUrl('panel', 'index')],
    ['label' => $opportunity->name, 'url' => $app->createUrl('opportunity', 'single', [$opportunity->id])],
    ['label' => $entity_name, 'url' => $app->createUrl('committeedraw', 'single', [$committee_draw->id])],
];

?>
<div class="main-app committeedraw single">
    <mc-breadcrumb></mc-breadcrumb>
    
    <mc-container>
        <main class="grid-12">
            <div>
                <committee-draws-audit :entity="entity"></committee-draws-audit>
            </div>
        </main>
    </mc-container>
</div>