<?php

/**
 * @var MapasCulturais\App $app
 * @var MapasCulturais\Themes\BaseV2\Theme $this
 */

use MapasCulturais\i;

?>

<div v-if="list?.length > 0" class="committee-draws-overview">
    <h4 class="bold">
        <?php i::_e('Sorteio de pareceristas') ?>
    </h4>

    <ul>
        <li v-for="item in list" :key="item.id">
            <a :href="getUrl(item)">
                {{ getEvaluationNameById(item) }} - {{ item.committee_name }} - <?php i::_e('sorteio') ?> {{ item.draw_number }}
            </a>
        </li>
    </ul>
</div>