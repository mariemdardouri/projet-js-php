 
 //selectionner taille pour ajoute au panier
       function ajouterAuPanier(span) {
            const id_vetement = span.getAttribute('data-id'); // Changé de id à id_vetement
            const nom = span.getAttribute('data-nom');
            const prix = span.getAttribute('data-prix');
            const image = span.getAttribute('data-image');
            const taille = span.getAttribute('data-taille');

            let cart = JSON.parse(localStorage.getItem("cart")) || [];

            let existing = cart.find(item => 
                (item.id_vetement || item.id) == id_vetement && 
                item.taille === taille
            );
            if (existing) {
                existing.quantite += 1;
            } else {
                cart.push({ 
                    id_vetement: id_vetement, // Toujours utiliser id_vetement
                    nom: nom,
                    prix: parseFloat(prix), // Convertir en nombre
                    image: image,
                    taille: taille,
                    quantite: 1
                });
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
            const sousTotalSpan = document.getElementById("sous-total");
            const sousTotalContainer = document.getElementById("sous-total-container"); 
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
                                <span>
                                    <button onclick="ouvrirModalModifier(${index})"><i class="bi bi-pencil"></i></button>|<button onclick="supprimerDuPanier(${index})"><i class="bi bi-trash3"></i></button>
                                </span>
                            </div>
                        </div>
                    `;
                });
            }
            if (total >= 199) {
                    sousTotalContainer.style.display = "block";
                    sousTotalSpan.innerText = total.toFixed(2);
                } else {
                    sousTotalContainer.style.display = "none";
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