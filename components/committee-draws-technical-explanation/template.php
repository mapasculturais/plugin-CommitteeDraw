<?php

/**
 * @var MapasCulturais\App $app
 * @var MapasCulturais\Themes\BaseV2\Theme $this
 * @var CommitteeDraw $entity
 */

use CommitteeDraw\Entities\CommitteeDraw;

use MapasCulturais\i;

?>
<div class="committee-draws-technical-explanation">
    <div class="committee-draws-technical-explanation-header">
        <h1><?= i::__('Como o Sorteio de Pareceristas é Transparente e Auditável') ?></h1>
    </div>

    <div class="committee-draws-process">
        <h2><?= i::__('Entendendo o Sorteio como uma Receita Precis') ?></h2>
        <p><?= i::__('Imagine que nosso sistema de sorteio funciona como uma receita de bolo muito detalhada. Se você usar<strong>exatamente os mesmos ingredientes</strong> e seguir<strong>exatamente os mesmos passos,</strong> o resultado final será sempre o mesmo. É esse princípio que garante que nosso sorteio seja completamente transparente e verificável.') ?></p>
    </div>

    <div class="commmitee-draws-ingredients">
        <div class="committee-draws-process">
            <h2><?= i::__('Os "Ingredientes" do Nosso Sorteio') ?></h2>
            <p><?= i::__('Para realizar o sorteio, o sistema utiliza cinco informações fundamentais que funcionam como os "ingredientes" da nossa receita:') ?></p>

            <ol>
                <li><strong><?= i::__('Identificação da fase do edital:</strong> O número único que identifica esta etapa específica do processo') ?> ("ID: <?= $entity->evaluationMethodConfiguration->id ?>") </li>
                <li><strong><?= i::__('Comissão responsável:</strong> O nome do grupo que está realizando a avaliação') ?> (" <?= $entity->committeeName ?> ")</li>
                <li><strong><?= i::__('Número do sorteio:') ?></strong><?= i::__('A sequência deste sorteio dentro da comissão') ?>(" <?= $entity->drawNumber ?> ")</li>
                <li><strong><?= i::__('Data e hora exata:') ?></strong><?= i::__('O momento preciso em que o sorteio foi realizado') ?>: <?= $entity->createTimestamp->format('d/m/Y \à\s H:i') ?></li>
                <li><strong><?= i::__('Lista completa de participantes:') ?></strong> <?= i::__('Todos os avaliadores habilitados para este sorteio') ?>( "<?= $entity->numberOfValuers ?>")</li>
            </ol>
        </div>
    </div>
    <div class="with-vertical-bar">
        <div class="committee-draws-infomation-card">
            <div class="committee-draws-process ">
                <h2><?= i::__('Como o Processo Funciona na Prática') ?></h2>
                <div class="committee-draws-infomation">
                    <h2><?= i::__('Passo 1: Criação do Código Único') ?></h2>
                    <p><?= i::__('O sistema combina todas essas informações em um') ?> <strong><?= i::__('código único ') ?></strong><?= i::__('(chamado de seed), que funciona como uma "impressão digital" do sorteio. Este código garante que cada combinação de informações resulte em um sorteio diferente.') ?></p>
                </div>
            </div>
        </div>
    </div>
    <div class="with-vertical-bar">
        <div class="committee-draws-process">
            <div class="committee-draws-infomation">

                <h2><?= i::__('Passo 2: Embaralhamento Justo') ?></h2>
                <p><?= i::__('Usando esse código único, o sistema organiza a lista de avaliadores de maneira aleatória, mas completamente reproduzível. É como embaralhar cartas seguindo uma sequência específica - sempre que você repetir a mesma sequência, as cartas ficarão na mesma ordem.') ?></p>
            </div>
        </div>
    </div>
    <div class="with-vertical-bar last-step-no-line">
        <div class="committee-draws-process">
            <div class="committee-draws-infomation">
                <h2><?= i::__('Passo 3: Seleção dos Sorteados') ?></h2>
                <p><?= i::__('A partir da lista embaralhada, o sistema seleciona a quantidade necessária de avaliadores (neste caso,') ?> <?= $entity->numberOfValuers ?> <?= i::__('pareceristas.)') ?></p>
            </div>
        </div>
    </div>
    <div class="committee-draws-infomation">
        <div class="committee-draws-process">

            <h2><?= i::__('Por Que Você Pode Confiar Neste Processo') ?></h2>
            <h2><?= i::__('Total Transparência') ?></h2>

            <p><?= i::__('Todas as informações utilizadas no sorteio ficam registradas e disponíveis para consulta. Não há "segredos" ou etapas ocultas.') ?></p>
        </div>
        <div class="committee-draws-process">

            <h2><?= i::__('Resultado Verificável') ?></h2>

            <p><?= i::__('Qualquer pessoa pode repetir o processo usando as mesmas informações e obterá ') ?><strong><?= i::__('exatamente o mesmo resultado.') ?></strong><?= i::__('Se você fizer a verificação e o resultado for diferente, isso significa que alguma informação não está correta.') ?></p>
        </div>
        <div class="committee-draws-process">

            <h2><?= i::__('Imparcialidade Garantida') ?></h2>

            <p><?= i::__('O sistema não tem preferências ou viés. Ele trata todos os participantes de forma igualitária, seguindo apenas as regras matemáticas estabelecidas.') ?></p>
        </div>
        <div class="committee-draws-infomation">
            <div class="committee-draws-process">

                <h2><?= i::__('Como Fazer Sua Própria Verificação') ?></h2>
                <p><strong><?= i::__('Para Não-Técnicos:') ?></strong></p>

                <p><?= i::__('Se você quiser confirmar que o sorteio foi realizado corretamente:') ?></p>

                <ol>
                    <li><strong><?= i::__('Colete as informações:') ?></strong><?= i::__(' Todas estão disponíveis nesta página') ?></li>
                    <li><strong><?= i::__('Use nossa ferramenta de verificação:') ?></strong><?= i::__(' Na aba ao lado, você pode inserir os dados') ?></li>
                    <li><strong><?= i::__('Compare os resultados:') ?></strong> <?= i::__('O sistema mostrará se o resultado confere') ?></li>
                </ol>
            </div>
        </div>
        <div class="committee-draws-process">

            <h2><?= i::__('O Que Esperar:') ?></h2>

            <ul>
                <li><strong><?= i::__('Se todas as informações estiverem corretas:') ?></strong><?= i::__('O resultado será idêntico ao anunciado') ?></li>
                <li><strong><?= i::__('Se houver qualquer diferença') ?></strong> <?= i::__('O resultado será diferente, indicando que algo não está conforme o original') ?></li>
            </ul>
        </div>

        <div class="committee-draws-process">


            <h2><?= i::__('Resultado do Sorteio Atual') ?></h2>
            <p><?= i::__('No sorteio realizado em ') ?><strong><?= $entity->createTimestamp->format('d/m/Y \à\s H:i') ?></strong> <?= i::__('foram selecionados os seguintes avaliadores:') ?></p>

            <ol>
                <?php foreach ($entity->inputValuers as $valuer_id): ?>
                    <li><?= htmlspecialchars($valuer_id) ?></li>
                <?php endforeach; ?>
            </ol>

            <p><?= i::__('Estes números correspondem aos IDs dos pareceristas sorteados. Você pode verificar se esse resultado está correto usando as informações técnicas na próxima aba.') ?></p>
        </div>
        <div class="committee-draws-process">

            <h2><?= i::__('Por Que Essa Transparência é Importante') ?></h2>
            <ol>
                <li><strong><?= i::__('Fortalece a confiança') ?></strong><?= i::__('no processo avaliativo') ?></li>
                <li><strong><?= i::__('Permite que qualquer pessoa') ?></strong><?= i::__('seja um fiscal do processo') ?></li>
                <li><strong><?= i::__('Garante tratamento igualitário') ?></strong><?= i::__(' a todos os participantes') ?></li>
                <li><strong><?= i::__('Assegura que o resultado') ?></strong><?= i::__('reflete apenas o acaso, sem interferências') ?></li>
            </ol>

            <p><?= i::__('Esta abordagem transforma o sorteio de uma "caixa preta" em um processo aberto e democrático, onde a honestidade é verificável matematicamente.') ?></p>
        </div>
    </div>
</div>