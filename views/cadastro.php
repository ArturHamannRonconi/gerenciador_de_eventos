<main>
  <section id="section-cadastro">
    <div class="container">
      <form method="POST" action='<?= "{$base}/{$action}" ?>'>
        <div class="form">
          <input class="input" type="text" name="nome" placeholder="Nome">
          <input class="input" type="<?= $type ?>" min="1" name="<?= $name ?>" placeholder="<?= ucfirst($name) ?>">
          <input class="btn-info" type="submit" value="Cadastrar">
          <?php if(!empty($warning)): ?>
            <div class="warning">
              <?= $warnings[$warning] ?>
            </div>
          <?php endif ?>
        </div>
      </form>
    </div>
  </section>
</main>