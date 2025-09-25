<?php

/**
 * @var MapasCulturais\App $app
 * @var MapasCulturais\Themes\BaseV2\Theme $this
 */

use MapasCulturais\i;

?>

<div class="file-info-header">

    <div class="file-info-row-group">
        <div class="file-info-row">
            <span class="label"><?= i::__('Comissão:') ?></span>
            <span class="value-red"><?= $entity->committeeName ?></span>
        </div>

        <div class="file-info-row">
            <span class="label"><?= i::__('Número do sorteio:') ?></span>
            <span class="value-red"><?= $entity->drawNumber ?></span>
        </div>

        <div class="file-info-row">
            <span class="label"><?= i::__('Avaliadores sorteados') ?>:</span>
            <span class="value-red"><?= $entity->numberOfValuers ?></span>
        </div>
        <div class="file-info-row">
            <span class="label"><?= i::__('Data do sorteio:') ?></span>
            <span class="value-red"><?= $entity->createTimestamp->format('d/m/Y \à\s H:i') ?></span>
        </div>
    </div>
    <div class="short-line"></div>

    <div class="file-info-row-footer">
        <div>
            <span class="label"><?= i::__('Assinatura da lista de candidatos (md5):') ?></span>
            <span class="value-red"><?= $entity->fileMd5 ?></span>
        </div>
        <div>
            <a class="download-btn"
                href="<?= $entity->file->url ?>">
                <?= i::__('Baixar lista de candidatos') ?>
            </a>
        </div>
    </div>
</div>