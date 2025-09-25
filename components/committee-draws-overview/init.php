<?php

use Doctrine\DBAL\ArrayParameterType;

$all_phases = $entity->allPhases ?? [];
$evaluation_method_configuration_ids = [];

foreach ($all_phases as $phase) {
    if ($phase->evaluationMethodConfiguration) {
        $evaluation_method_configuration_ids[] = $phase->evaluationMethodConfiguration->id;
    }
}

if ($evaluation_method_configuration_ids) {
    $conn = $app->em->getConnection();
    $committee_draws = $conn->fetchAll(
        "
        SELECT 
            *
        FROM 
            committee_draw
        WHERE 
            evaluation_method_configuration_id IN (:ids)
    ",
        [
            'ids' => $evaluation_method_configuration_ids
        ],
        [
            'ids' => ArrayParameterType::INTEGER
        ]
    );

    // Ordenar pelo timestamp

    $this->jsObject['config']['committeeDrawsOverview'] = $committee_draws;
}
