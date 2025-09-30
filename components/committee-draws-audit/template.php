<?php

/**
 * @var MapasCulturais\App $app
 * @var MapasCulturais\Themes\BaseV2\Theme $this
 * @var CommitteeDraw $entity
 */

use CommitteeDraw\Entities\CommitteeDraw;

use MapasCulturais\i;
$this->import('
    mc-icon
');
?>
<div class="committee-draws-audit">
    <h1><?= i::__('Auditoria do Sorteio de Avaliadores') ?></h1>
    
    <p><?= i::__('Detalhes e passos para auditoria do sorteio realizado em') ?> <strong><?= $entity->createTimestamp->format('d/m/Y H:i') ?></strong></p>

    <div class="committee-draws-audit-header">
        <div class="header-content-wrapper">
            <div class="committee-icon">
                <mc-icon  name="exclamation"></mc-icon>
            </div>
            <div class="header-text-container">
                <h2><?= i::__('Explicação simplificada') ?></h2>
            </div>
        </div>
    </div>

    <div class="committee-draws-audit-header">
        <h2><?= i::__('Explicação Técnica para Auditoria em PHP') ?></h2>
        <p><?= i::__('A implementação do sorteio auditável utiliza a função auditableDraw, que depende de um seed determinístico para inicializar o gerador de números pseudoaleatórios do PHP (srand). O seed é criado a partir das seguintes variáveis') ?>:</p>
        <ul>
            <li><strong><code>$evaluati/on_method_configuration_id</code></strong> - <em><?= i::__('ID da configuração da fase de avaliação') ?></em>;</li>
            <li><strong><code>$committee_name</code></strong> - <em><?= i::__('nome da comissão') ?></em>;</li>
            <li><strong><code>$draw_number</code></strong> - <em><?= i::__('número sequencial do sorteio na comissão') ?></em>.</li>
        </ul>
    </div>

    <h3><?= i::__('Passos para auditoria') ?>:</h3>
    <ol class="custom-list">
        <li>
            <h4><?= i::__('Função <strong>auditableDraw</strong> para reproduzir o sorteio') ?>:</h4>
            <?php highlight_string('<?php
function auditableDraw($seed, $valuer_ids, $number_of_valuers): array {
        // Inicializa o gerador com o seed
        srand($seed);

        // embaralha os ids dos avaliadores baseado
        shuffle($valuer_ids); 
        
        // Seleciona os N primeiros avaliadores
        $selected_valuers = array_slice($valuer_ids, 0, $number_of_valuers);

        return $selected_valuers;
}
') ?>
        </li>

        <li>
            <h4><?= i::__('Dados de entrada utilizados neste sorteio') ?>:</h4>
            <?php highlight_string('<?php
// número de avaliadores que devem ser selecionados
$number_of_valuers = ' . $entity->numberOfValuers . ';

// ID da configuração da fase de avaliação
$evaluation_method_configuration_id = ' . $entity->evaluationMethodConfiguration->id . '; 

// nome da comissão
$committee_name = "' . $entity->committeeName . '";

// número sequencial do sorteio na comissão
$draw_number = ' . $entity->drawNumber . ';

// horário do sorteio
$timestamp = "' . $entity->createTimestamp->format('Y-m-d H:i:s') . '";

// lista com todos os ids dos avaliadores enviados na planilha
$valuer_ids = ' . json_encode($entity->inputValuers) . ';

// Ordena os IDs para garantir resultado consistente, independente da ordem de entrada
sort($valuer_ids);

// cria uma string com a lista de ids dos avaliadores, ordenados de maneira crescente
$valuers_string = json_encode($valuer_ids);   
') ?>
        </li>


        <li>
            <h4><?= i::__('Gerar o seed original (exatamente como implementado)') ?>:</h4>
            <?php highlight_string('<?php
// cria o seed para enviar ao auditableDraw
$seed = crc32("$evaluation_method_configuration_id:$committee_name:$draw_number:$timestamp:$valuers_string");
            ') ?>
        </li>

        <li>
            <h4><?= i::__('Comparar o resultado com o registro armazenado no sistema') ?>:</h4>
            <?php highlight_string('<?php
// obtém o resultado do sorteio
$resultado = auditableDraw($seed, $valuer_ids, $number_of_valuers);

// ordena os IDs dos avaliadores sorteados de maneira crescente para facilitar a comparação
sort($reultado);

// exibe o resultado do sorteio (ids dos avaliadores selecionados)
print_r($resultado); // Deve ser idêntico ao resultado original registrado
') ?>

            <pre><?php $v = $entity->outputValuers; sort($v); print_r($v); ?></pre>
        </li>

    </ol>

    <h3>Por que é Auditável?</h3>
    <div class="committee-draws-audit-header">
        <ul>
            <li>O <strong>seed</strong> é derivado de dados imutáveis e públicos (ID da configuração, nome da comissão e número do sorteio);</li>
            <li><strong>srand()</strong> e <strong>shuffle()</strong> são determinísticos quando inicializados com o mesmo seed;</li>
            <li>A função shuffle do PHP usa o gerador Mersenne Twister, que é reproduzível com o mesmo seed.</li>
        </ul>
    </div>
</div>