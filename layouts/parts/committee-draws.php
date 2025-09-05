<?php
/**
 * @var MapasCulturais\App $app
 * @var MapasCulturais\Themes\BaseV2\Theme $this
 */


$this->import('
    committee-draws
');
?>

<committee-draws :entity="entity" :committee-name="group"></committee-draws>