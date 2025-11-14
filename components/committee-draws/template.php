<?php

/**
 * @var MapasCulturais\App $app
 * @var MapasCulturais\Themes\BaseV2\Theme $this
 */

use MapasCulturais\i;

$this->import('
    mc-loading
    mc-popover
    mc-icon
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

        <template #default="popover">
            <form @submit.prevent="createCommitteeDraw(popover)">
                <div class="grid-12">
                    <div class="col-12 committee-draw-center">
                        <h4 class="bold"><?php i::_e('Sorteio de número') ?> #{{drawNumber}}</h4>
                    </div>

                    <div class="col-12">
                        <a href="<?php $this->asset('committee-draw/template.xlsx') ?>">
                            <mc-icon class="committee-draw-icon" name="download"></mc-icon>
                            <?php i::_e('Baixar modelo de planilha') ?>
                        </a><br>
                        <input class="committee-draw-file" type="file" ref="file">
                    </div>

                    <div class="col-12">
                        <label>
                            <span class="committee-draw-appraiser-text"><?php i::_e('Quantidade de avaliadores a selecionar') ?>: </span>
                            <input class="committee-draw-appraiser input-number" type="number" v-model="numberOfValuers" min="1" max="999" />
                        </label>
                    </div>

                    <div class="col-12">
                        <mc-loading :condition="!!loading">{{loading}}</mc-loading>
                    </div>

                    <div v-if="!loading" class="col-12">
                        <button class="col-6 button button--text committee-draw-button" type="reset" @click="popover.close()"> <?php i::_e("Cancelar") ?> </button>
                        <button class="col-6 button button--primary" type="submit"> <?php i::_e("Confirmar") ?> </button>
                    </div>
                </div>
            </form>
        </template>
    </mc-popover>
</div>