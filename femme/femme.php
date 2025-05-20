<?php
include '../php/config.php';
session_start();
$user = $_SESSION['user'] ?? null;
$vetements = $pdo->query("SELECT * FROM vetements WHERE sexe = 'femme'")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Femme - Vêtements Chic</title>
    <link rel="stylesheet" href="femme.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body>
    <div id="mySidebar" class="sidebar">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
    </div>  
    <header>
        <nav class="nav">
            <span class="menu-icon" onclick="openNav()">&#9776;</span>
            <a href="femme.php">Femmes</a>
            <a href="../homme/homme.php">Hommes</a>
        </nav>
        <div class="logo">Vêtements Chic</div>
        <div class="search-icons">
            <input type="text" placeholder="Rechercher...">
            <a href="#" class="panier-icon-global" title="Voir panier" onclick="ouvrirPanierComplet()">
                <i class="bi bi-cart-fill" style="font-size: 24px; position: relative;"></i>
                <span class="cart-count" id="cart-count">0</span>
            </a>
            <?php if ($user): ?>
                <span>Bienvenue, <?= htmlspecialchars($user['prenom']) ?> !</span>
                <form action="../php/logout.php" method="post" style="display:inline;">
                    <button type="submit">Déconnexion</button>
                </form>
            <?php else: ?>
                <a href="../connexion.html"><button>Connexion</button></a>
            <?php endif; ?>
            <a href="#" title="Favoris">❤</a>
        </div>
    </header>

    <section class="categories">
        <a href="femme.php" class="category-btn">Tous</a>
        <a href="jeanf.php" class="category-btn">Jeans</a>
        <a href="vestef.php" class="category-btn">Vestes</a>
        <a href="pullf.php" class="category-btn">Pulls</a>
        <a href="chemisef.php" class="category-btn">Chemises</a>
        <a href="accessoiref.php" class="category-btn">Accessoires</a>
        <a href="chaussuref.php" class="category-btn">Chaussures</a>
    </section>

    <section class="product-grid">
        <?php foreach ($vetements as $vetement): ?>
            <?php
            $stmtTailles = $pdo->prepare("SELECT * FROM taillesv WHERE id_vetement = ?");
            $stmtTailles->execute([$vetement['id_vetement']]);
            $tailles = $stmtTailles->fetchAll();
            ?>
            <div class="product-item">
                <div class="image-container">
                    <img src="../images/<?php echo htmlspecialchars($vetement['image']); ?>" alt="<?php echo htmlspecialchars($vetement['nom']); ?>">
                    <div class="size-overlay">
                        <label>Sélectionne une taille :</label>
                        <div class="size-options">
                            <?php foreach ($tailles as $taille): ?>
                                <a href="#" 
                                class="panier-icon" 
                                title="Ajouter au panier" 
                                onclick="ajouterAuPanier(this)" 
                                data-id="<?= $vetement['id_vetement'] ?>"
                                data-nom="<?= htmlspecialchars($vetement['nom']) ?>"
                                data-prix="<?= $vetement['prix'] ?>"
                                data-image="<?= htmlspecialchars($vetement['image']) ?>"
                                data-taille="<?= $taille['taille'] ?>">
                                <span class="size"><?php echo htmlspecialchars($taille['taille']); ?></span>
                                </a>
                                
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <div class="product-name"><?php echo htmlspecialchars($vetement['nom']); ?></div>
                <p class="price"><?php echo number_format($vetement['prix'], 2); ?> TND</p>
            </div>
        <?php endforeach; ?>
    </section>
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
        <button class="commander">PASSER COMMANDE</button>
        <a href="panier.php"><button class="voir-panier">VOIR PANIER</button></a>
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
        <button>✏️</button>
        <button>🗑️</button>
      </div>
    </div>
  </div>

  <!-- Pied de page -->
  <div class="btnTotal">
      <p><strong>Sous-total : : <span>0.00</span> TND</strong></p>
      <p><strong>Frais d'envoi :</strong> <span class="gratuit">GRATUIT</span></p>
      <p><strong>Total : <span id="total-panier">0.00</span> TND</strong></p>
    </div>
    <button class="btn-commander">PASSER COMMANDE</button>
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

    <script>
        //Nav bar 
        function openNav() {
            document.getElementById("mySidebar").style.width = "250px";
        }

        function closeNav() {
            document.getElementById("mySidebar").style.width = "0";
        }
        //selectionner taille pour ajoute au panier
       function ajouterAuPanier(span) {
            const id = span.getAttribute('data-id');
            const nom = span.getAttribute('data-nom');
            const prix = span.getAttribute('data-prix');
            const image = span.getAttribute('data-image');
            const taille = span.getAttribute('data-taille');

            let cart = JSON.parse(localStorage.getItem("cart")) || [];

            let existing = cart.find(item => item.id === id && item.taille === taille);
            if (existing) {
                existing.quantite += 1;
            } else {
                cart.push({ id, nom, prix, image, taille, quantite: 1 });
            }
            localStorage.setItem("cart", JSON.stringify(cart));
            document.getElementById("cart-count").innerText = cart.reduce((sum, item) => sum + item.quantite, 0);

            document.getElementById("taille-select").innerText = "Taille " + taille;
            document.querySelector("#panier-modal img").src = "../images/" + image;
            document.querySelector("#panier-modal .modal-details div").innerHTML =
                `<strong>${parseFloat(prix).toFixed(2)} TND</strong><br>${nom}<br><span>Taille ${taille}</span>`;

            document.getElementById("panier-modal").style.display = "flex";
        }

        window.onload = function() {
            let cart = JSON.parse(localStorage.getItem("cart")) || [];
            document.getElementById("cart-count").innerText = cart.length;
        };
        function fermerModal() {
            document.getElementById("panier-modal").style.display = "none";
        }
        //Panier
        function ouvrirPanierComplet() {
            const panier = JSON.parse(localStorage.getItem("cart")) || [];
            const contenu = document.getElementById("contenu-panier");
            const totalSpan = document.getElementById("total-panier");
            const panierCountText = document.querySelector("#cart-count-panier");
            contenu.innerHTML = "";

            let total = 0;
            panierCountText.innerText = `(${panier.length})`;

            if (panier.length === 0) {
                contenu.innerHTML = "<p>Votre panier est vide.</p>";
            } else {
                panier.forEach((item, index) => {
                    total += parseFloat(item.prix) * item.quantite;

                    contenu.innerHTML += `
                        <div class="article">
                            <img src="../images/${item.image}" alt="${item.nom}">
                            <div class="infos-article">
                                <p class="prix">${parseFloat(item.prix).toFixed(2)} TND</p>
                                <p>${item.nom}</p>
                                <p class="taille">Taille : ${item.taille} | Quantité : ${item.quantite}</p>
                            </div>
                            <div class="actions">
                                <button onclick="ouvrirModalModifier(${index})"><i class="bi bi-pencil"></i></button>
                                <button onclick="supprimerDuPanier(${index})"><i class="bi bi-trash3"></i></button>
                            </div>
                        </div>
                    `;
                });
            }

            totalSpan.innerText = total.toFixed(2);
            document.getElementById("modal-panier-complet").style.display = "flex";
            document.getElementById("overlay").style.display = "block";
        }

        function fermerPanierComplet() {
            document.getElementById("modal-panier-complet").style.display = "none";
            document.getElementById("overlay").style.display = "none";
        }
        //Supprimer du Panier
        function supprimerDuPanier(index) {
            let panier = JSON.parse(localStorage.getItem("cart")) || [];
            panier.splice(index, 1);
            localStorage.setItem("cart", JSON.stringify(panier));
            document.getElementById("cart-count").innerText = panier.length;
            ouvrirPanierComplet(); // recharge le panier
        }
        //Modifier panier
        let indexArticleAModifier = -1;

        function ouvrirModalModifier(index) {
        const panier = JSON.parse(localStorage.getItem("cart")) || [];
        const article = panier[index];
        indexArticleAModifier = index;

        const tailles = [32, 34, 36, 38, 40, 42];
        const tailleHTML = tailles.map(t =>
            `<span class="${t == article.taille ? 'active' : ''}" onclick="selectionnerTaille(this)">${t}</span>`
        ).join('');

        document.getElementById("modifier-contenu").innerHTML = `
            <div>
            <img src="../images/${article.image}" alt="${article.nom}" />
            <p><strong>${parseFloat(article.prix).toFixed(2)} TND</strong><br>${article.nom}</p>

            <h4>Taille</h4>
            <div class="taille-selector">${tailleHTML}</div>

            <h4>Quantité</h4>
            <div class="quantite-control">
                <button onclick="changerQuantite(-1)">-</button>
                <span id="quantite-modif">${article.quantite}</span>
                <button onclick="changerQuantite(1)">+</button>
            </div>
            </div>
        `;

        document.getElementById("modal-modifier").style.display = "flex";
        }

        function fermerModalModifier() {
        document.getElementById("modal-modifier").style.display = "none";
        }
        function selectionnerTaille(el) {
            document.querySelectorAll(".taille-selector span").forEach(s => s.classList.remove("active"));
            el.classList.add("active");
        }

        function changerQuantite(delta) {
            const span = document.getElementById("quantite-modif");
            let val = parseInt(span.innerText);
            val = Math.max(1, val + delta);
            span.innerText = val;
        }
        function enregistrerModifications() {
            const panier = JSON.parse(localStorage.getItem("cart")) || [];
            const tailleChoisie = document.querySelector(".taille-selector .active").innerText;
            const quantite = parseInt(document.getElementById("quantite-modif").innerText);

            if (indexArticleAModifier >= 0 && indexArticleAModifier < panier.length) {
                panier[indexArticleAModifier].taille = tailleChoisie;
                panier[indexArticleAModifier].quantite = quantite;
            }

            localStorage.setItem("cart", JSON.stringify(panier));
            fermerModalModifier();
            ouvrirPanierComplet();
            document.getElementById("cart-count").innerText = panier.reduce((sum, item) => sum + item.quantite, 0);
        }
        window.onload = function() {
            let cart = JSON.parse(localStorage.getItem("cart")) || [];
            document.getElementById("cart-count").innerText = cart.reduce((sum, item) => sum + item.quantite, 0);
        };    
    </script>

</body>
</html>
