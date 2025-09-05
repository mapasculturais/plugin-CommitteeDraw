<?php

/**
 * @var MapasCulturais\App $app
 * @var MapasCulturais\Themes\BaseV2\Theme $this
 */

use MapasCulturais\i;
?>

<div class="committee-draws-audit">
    <h4 class="bold">{{entity.evaluationMethodConfiguration.name}} - {{entity.committeeName}} - <?php i::_e('sorteio') ?> #{{entity.drawNumber}}</h4>

    <p><?php i::_e('Este sorteio foi realizado de forma') ?> <strong><?php i::_e('auditável') ?></strong>.</p>

    <p><?php i::_e('Isso significa que qualquer pessoa pode verificar de maneira independente que o resultado não foi manipulado.') ?></p>

    <h5 class="bold"><?php i::_e('Como funciona?') ?></h5>

    <ol>
        <li>
            <?php i::_e('Todo sorteio usa uma') ?> <strong><?php i::_e('semente fixa (seed)') ?></strong>,
            <?php i::_e('que é um número único calculado a partir das informações do sorteio:') ?>
            <ul>
                <li><?php i::_e('ID da configuração de avaliação:') ?> <strong>{{entity.evaluationMethodConfiguration.id}}</strong></li>
                <li><?php i::_e('Nome da comissão:') ?> <strong>{{entity.committeeName}}</strong></li>
                <li><?php i::_e('Número do sorteio:') ?> <strong>{{entity.drawNumber}}</strong></li>
            </ul>
            <?php i::_e('Essa combinação gera o valor da') ?> <strong><?php i::_e('seed') ?></strong>:
            <p><strong>{{entity.seed}}</strong></p>
        </li>

        <li>
            <?php i::_e('A partir dessa seed, é feito um shuffle (embaralhamento) da lista de avaliadores.') ?>
        </li>

        <li>
            <?php i::_e('Os primeiros') ?> {{entity.numberOfValuers}} <?php i::_e('nomes da lista embaralhada são escolhidos como resultado final do sorteio.') ?>
        </li>
    </ol>
</div>