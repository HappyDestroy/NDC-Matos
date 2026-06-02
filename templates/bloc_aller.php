<?php if ($hide_association): ?>
      <div class="liste-matos">
        À répartir :

        <ul>
          <?php foreach ($resultat['repartition']['aller'] as $entry): ?>
            <?php foreach ($entry['materiels'] ?? [] as $m): ?>
              <li>
                <strong>
                  <?= htmlspecialchars($m['demandeur']) ?>
                </strong>
                :
                <?= htmlspecialchars($m['materiel']) ?>
                <?php if (!empty($m['commentaire']) && $show_comment): ?>
                  <div class="commentaire">Commentaire: <?= htmlspecialchars($m['commentaire']) ?></div>
                <?php endif; ?>
              </li>
            <?php endforeach ?>
          <?php endforeach ?>
        </ul>
      </div>
    <?php endif ?>

    <div class="bloc-container <?= $hide_association ? 'hide-association' : ''; ?>" id="bloc-container-aller">
      <?php foreach ($resultat['repartition']['aller'] as $personne => $entry): ?>
        <?php $materiels = $entry['materiels'] ?? []; ?>
        <div class="bloc">
          <div>
            <b><?= htmlspecialchars($personne) ?></b>
            <?php if (!empty($entry['responsable'])): ?>
              <small>Responsable <?= htmlspecialchars(strtolower($entry['responsable'])) ?></small>
            <?php endif; ?>
          </div>
          <div>Prend pour : </div>
          <div>
            <?php if (count($materiels) > 0 && !$hide_association): ?>
              <?php foreach ($materiels as $m): ?>
                <div class="personne-container">
                  <div class="personne-name">
                    <?= htmlspecialchars($m['demandeur']) ?>
                  </div>
                  <div class="personne-demande">
                    <?php $demande = explode(',', $m['materiel']); ?>
                    <?php foreach ($demande as $d): ?>
                      <div class="demande-line">
                        <div class="fake-checkbox">☐</div>
                        <div class="materiel-item"><?= htmlspecialchars(trim($d)) ?></div>
                        <div class="materiel-numero">N° : ..........</div>
                      </div>
                    <?php endforeach; ?>
                  </div>
                  
                  <?php if (!empty($m['commentaire']) && $show_comment): ?>
                    <div class="break"></div>
                    <div class="commentaire">Commentaire: <?= htmlspecialchars($m['commentaire']) ?></div>
                  <?php endif; ?>
                </div>
              <?php endforeach; ?>
            <?php elseif (!$hide_association): ?>
              Personne 🥳
            <?php endif; ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>



    <table class="repartition-table <?= $hide_association ? 'hide-association' : ''; ?>" id="repartition-table-aller">
      <thead>
        <tr>
          <th colspan="2">Personne allant au local</th>
          <th>Matériel à récupérer</th>
        </tr>
      </thead>

      <tbody>
        <?php foreach ($resultat['repartition']['aller'] as $personne => $entry): ?>
          <?php $materiels = $entry['materiels'] ?? []; ?>
          <tr>
            <td>
              <strong><?= htmlspecialchars($personne) ?></strong>
              <?php if (!empty($entry['responsable'])): ?>
                <small>Responsable <?= htmlspecialchars(strtolower($entry['responsable'])) ?></small>
              <?php endif; ?>
            </td>
            <td>Prend pour&nbsp;:</td>
            <td>
              <?php if (!$hide_association): ?>
                <?php if (count($materiels) > 0): ?>
                  <?php foreach ($materiels as $m): ?>
                    <div class="personne-container">
                      <div class="personne-name">
                        <?= htmlspecialchars($m['demandeur']) ?>
                      </div>
                      <div class="personne-demande">
                        <?php $demande = explode(',', $m['materiel']); ?>
                        <?php foreach ($demande as $d): ?>
                          <div class="demande-line">
                            <div class="fake-checkbox">☐</div>
                            <div class="materiel-item"><?= htmlspecialchars(trim($d)) ?></div>
                            <div class="materiel-numero">N° : ..........</div>
                          </div>
                        <?php endforeach; ?>
                      </div>

                      <?php if (!empty($m['commentaire']) && $show_comment): ?>
                        <div class="break"></div>
                        <div class="commentaire">Commentaire: <?= htmlspecialchars($m['commentaire']) ?></div>
                      <?php endif; ?>
                    </div>
                  <?php endforeach; ?>

                <?php elseif (!$hide_association): ?>
                  Personne 🥳
                <?php endif; ?>
              <?php else: ?>
                <?php for ($i = 0; $i < 4; $i++): ?>
                  <div class="placeholder">
                    <div class="placeholder-name"></div>
                    <div class="placeholder-demande">
                      <div class="placeholder-line">
                        <div class="placeholder-label">Bloc n°</div>
                        <div class="placeholder-value"></div>
                      </div>
                      <div class="placeholder-line">
                        <div class="placeholder-label">Stab n°</div>
                        <div class="placeholder-value"></div>
                      </div>
                      <div class="placeholder-line">
                        <div class="placeholder-label">Détendeur n°</div>
                        <div class="placeholder-value"></div>
                      </div>
                    </div>
                  </div>
                <?php endfor; ?>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>