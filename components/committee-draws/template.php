<?php

/**
 * @var MapasCulturais\App $app
 * @var MapasCulturais\Themes\BaseV2\Theme $this
 */

use MapasCulturais\i;

$this->import('
    mc-popover
');
?>

<div class="committee-draws">
    <mc-popover openside="down-right">
        <template #button="{toggle}">
            <button class="button button--icon button--primary button--md" @click="toggle">
                <mc-icon name="add"></mc-icon>
                <?php i::_e('Sorteio de avaliadores') ?>
            </button>
        </template>

        <template #default="{popover, close}">
            <form @submit.prevent="createCommitteeDraw($event)">
                <div class="grid-12">
                    <div class="col-12">
                        <h4 class="bold"><?php i::_e('Sorteio de número') ?> #{{drawNumber}}</h4>
                    </div>

                    <div class="col-12">
                        <label>
                            <span><?php i::_e('Quantidade de avaliadores selecionados') ?></span>
                            <input type="number" v-model="numberOfValuers" />
                        </label>
                    </div>

                    <div class="col-12">
                        <input type="file" ref="file">
                    </div>

                    <div class="col-12">
                        <button class="col-6 button button--text" type="reset" @click="close"> <?php i::_e("Cancelar") ?> </button>
                        <button class="col-6 button button--primary" type="submit" @click="close"> <?php i::_e("Confirmar") ?> </button>
                    </div>
                </div>
            </form>
        </template>
    </mc-popover>
</div>