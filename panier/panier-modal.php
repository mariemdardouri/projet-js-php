
<!-- MODAL -->
<div class="modal" id="panier-modal" style="display: none;">
    <div class="modal-content">
        <span class="close" onclick="fermerModal()">&times;</span>
        <p>✅ Ajouté au panier</p>
        <div class="modal-details">
        <img src="" alt="Produit sélectionné" id="modal-image" />
        <div>
            <span id="taille-select"></span>
        </div>
        </div>
        <a href="../commander.php"><button class="commander">PASSER COMMANDE</button></a>
        <button class="voir-panier" onclick="ouvrirPanierComplet()">VOIR PANIER</button>
    </div>
</div>
<div id="overlay" class="overlay" onclick="fermerTousModals()"></div>
<!-- MODAL PANIER -->
<div id="modal-panier-complet" class="modalPanier" style="display: none;">
  <div class="modal-header">
    <h2>Panier <span id="cart-count-panier">(0)</span></h2>
    <span class="close" onclick="fermerPanierComplet()">&times;</span>
  </div>

  <p class="livraison">Tu vas pouvoir profiter de la livraison standard gratuite à domicile</p>

  <div id="contenu-panier" class="articles-panier">
    <div class="article">
      <img src="" alt="">
      <div class="infos-article">
      </div>
      <div class="actions">
      </div>
    </div>
  </div>

    <!-- Pied de page -->
    <div class="btnTotal">
        <div class="" id="sous-total-container" style="display: none;">
            <p class="sous-total"><strong>Sous-total</strong> <span id="sous-total">0.00 TND</span></p>
            <p class="livraison"><strong>Frais d'envoi</strong> <span class="livraison-gratuite">GRATUIT</span></p>
        </div>
        <p class="total"><strong>Total</strong><span id="total-panier">0.00 TND</span></p>
        <a href="../commander.php"><button class="btn-commander">PASSER COMMANDE</button></a>
    </div>
  </div>
</div>
<!-- MODAL MODIFIER ARTICLE -->
<div id="modal-modifier" class="modalPanier" style="display: none; flex-direction: column;">
  <div class="modal-content">
    <span class="close" onclick="fermerModalModifier()">&times;</span>
    <h2>Modifier</h2>
    <div id="modifier-contenu"></div>
    <button onclick="enregistrerModifications()" class="btn-commander" style="margin-top: auto;">ENREGISTRER</button>
  </div>
</div>
