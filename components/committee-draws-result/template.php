<?php

/**
 * @var MapasCulturais\App $app
 * @var MapasCulturais\Themes\BaseV2\Theme $this
 */

use MapasCulturais\i;

$this->import("
    mc-entities
    entity-card
    mc-avatar
")
?>

<div class="committee-draws-result">
    <mc-entities type="agent" :ids="entity.outputValuers" select="name" order="name ASC">
        <template #default='{entities}'>
            <table>
                <tr>
                    <td>ID</td>
                    <td><?= i::__('Nome do avaliador') ?></td>
                </tr>
                <tr v-for="entity in entities">
                    <td>{{entity.id}}</td>
                    <td><mc-link :entity="entity"></mc-link></td>
                </tr>
            </table>
        </template>
    </mc-entities>
</div>

