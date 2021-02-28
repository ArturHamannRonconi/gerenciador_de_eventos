<main>
  <section id="section-consulta">
    <div class="container">
      <form method="POST" action='<?= "{$base}/{$action}" ?>'>
        <div class="form">
          <div class="border">
            <input class="input" type="text" name="pesquisa" placeholder="Pesquisar...">
            <input class="btn-info" type="image" src='<?= "{$assets}/medias/lupa.svg" ?>'>
          </div>
        </div>
      </form>
      <?php if(!empty($instancia)): ?>
        <div class="table">
          <div class="thead">
            <div class="trh">
              <span class="th"><?= $headerTable["id"] ?></span>
              <span class="th"><?= $headerTable["primario"] ?></span>
              <span class="th"><?= $headerTable["secundario"] ?></span>
            </div>
          </div>
          <div class="tbody">
            <div class="tr">
                <div class="td"><?= $instancia->getId() ?></div>
                <div class="td"><?= $instancia->getNome() ?></div>
                <?php if($instancia instanceof models\Participante): ?>
                  <div class="td"><?= $instancia->getSobrenome() ?></div>
                <?php else: ?>  
                  <div class="td"><?= $instancia->getCapacidade() ?></div>
                <?php endif ?>
            </div>
          </div>
        </div>
      <?php endif ?>
      <?php if(!empty($relacao)): ?>
        <div class="table">
          <div class="thead">
            <div class="trh">
              <?php if($action === "consultar/participantes_action"): ?>
                <span class="th">Sala</span>
                <span class="th">Local</span>
              <?php else: ?>
                <span class="th">Nome</span>
                <span class="th">Sobrenome</span>
              <?php endif ?>
              <span class="th">Etapa</span>
            </div>
          </div>
          <div class="tbody">
            <?php foreach($relacao as $relacionado): ?>
              <div class="tr">
                <div class="td"><?= $relacionado["primario"] ?></div>
                <div class="td"><?= $relacionado["secundario"] ?></div>
                <div class="td"><?= $relacionado["etapa"] ?></div>
              </div>
            <?php endforeach ?>
          </div>
        </div>
      <?php endif ?>
      <?php if(!empty($todosOsRegistros)): ?>
        <div class="table">
          <div class="thead">
            <div class="trh">
              <span class="th"><?= $headerTable["id"] ?></span>
              <span class="th"><?= $headerTable["primario"] ?></span>
              <span class="th"><?= $headerTable["secundario"] ?></span>
            </div>
          </div>
          <div class="tbody">
            <?php foreach($todosOsRegistros as $registro): ?>
              <div class="tr">
                <div class="td"><?= $registro->getId() ?></div>
                <div class="td"><?= $registro->getNome() ?></div>
                <?php if($registro instanceof models\Participante): ?>
                  <div class="td"><?= $registro->getSobrenome() ?></div>
                <?php else: ?>  
                  <div class="td"><?= $registro->getCapacidade() ?></div>
                <?php endif ?>
              </div>
            <?php endforeach ?>
          </div>
        </div>
      <?php endif ?>
      <?php if(
        !empty($warning) 
        && $warning !== "participantesNaSala" 
        && $warning !== "salasElocaisDoParticipante" 
        && $warning !== "participantesNoLocal"
      ): ?>
        <div class="warning">
          <span><?= $warnings[$warning] ?></span>
        </div>
      <?php endif ?>
    </div>
  </section>
</main>




