<?php
use MapasCulturais\i;

$this->layout = 'entity';

$this->import('
    committee-draws-overview
    committee-draws-audit
    committee-draws-info
    committee-draws-result
    committee-draws-technical-explanation
    entity-header
    mc-breadcrumb
    mc-container
    mc-tabs
    mc-title
');

$committee_draw = $this->controller->requestedEntity;
$opportunity = $committee_draw->evaluationMethodConfiguration->opportunity->firstPhase;
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
<div class="main-app single-1">
    <mc-breadcrumb></mc-breadcrumb>
    <entity-header :entity="entity.evaluationMethodConfiguration.opportunity.parent || entity.evaluationMethodConfiguration.opportunity">
        <template #description>
            <mc-title><?= $entity_name ?></mc-title>
        </template>
    </entity-header>
    
    <committee-draws-info :entity="entity"></committee-draws-info>

    <mc-tabs class="tabs" sync-hash>
        <mc-tab icon="exclamation" label="<?= i::_e('Resultado do sorteio') ?>" slug="result">
            <mc-container>
                <committee-draws-result :entity="entity"></committee-draws-result> 
                
            </mc-container>
        </mc-tab>                             
        <mc-tab icon="info" label="<?= i::_e('Explicação Técnica para Auditoria em PHP') ?>" slug="audit">
            <mc-container>
                <committee-draws-audit :entity="entity"></committee-draws-audit>
            </mc-container>
        </mc-tab>

        <mc-tab icon="info" label="<?= i::_e('Como funciona o sorteio de avaliadores e sua auditoria') ?>" slug="explanation">
            <mc-container>
                <committee-draws-technical-explanation :entity="entity"></committee-draws-technical-explanation>
            </mc-container>
        </mc-tab>
    </mc-tabs>
</div>