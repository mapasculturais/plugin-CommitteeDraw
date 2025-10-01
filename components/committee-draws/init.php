<?php
$committee_draws = [];
if ($evaluation_method_configuration = $opportunity->evaluationMethodConfiguration) {
    $conn = $app->em->getConnection();
    $committee_draws = $conn->fetchAll(
        "
            SELECT 
                committee_name,
                COUNT(*) + 1 AS next_draw_number
            FROM 
                committee_draw
            WHERE 
                evaluation_method_configuration_id = :evaluation_method_configuration_id
            GROUP BY 
                committee_name
            ORDER BY 
                committee_name;
        ",
        [
            'evaluation_method_configuration_id' => $evaluation_method_configuration->id
        ],
    );
}

$this->jsObject['config']['committeeDraws'] = $committee_draws;
