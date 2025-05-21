<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Commande - Exemple</title>
  <link rel="stylesheet" href="commander.css" />
</head>
<body>

<div class="progress-bar">
  <div class="active" id="stepIndicator1">Mode d'expédition</div>
  <div id="stepIndicator2">Mode de paiement</div>
  <div id="stepIndicator3">Résumé</div>
</div>

<div class="container-box">
  <form id="checkoutForm">

    <!-- Étape 1 -->
    <div class="step-box step active" id="step1">
      <h2>Choisis un mode d’expédition</h2>
      <div class="shipping-option" id="shippingOption" tabindex="0">
        <div class="left">
          <div class="title">Livraison à domicile</div>
          <div>Reçois-le Mardi 10</div>
        </div>
        <div class="price">9.90 TND</div>
      </div>

      <div id="addressForm">
        <label for="addressInput">Adresse de livraison :</label>
        <textarea id="addressInput" placeholder="Entrez votre adresse complète" required></textarea>
      </div>

      <button type="button" class="btn-continue" id="btnStep1" disabled>Continuer</button>
    </div>

    <!-- Étape 2 -->
    <div class="step-box step" id="step2">
      <h2>Choisis un mode de paiement</h2>
      <div class="payment-options">
        <label>
          <input type="radio" name="payment" value="livraison" checked />
          Paiement à la livraison
        </label>
        <label>
          <input type="radio" name="payment" value="carte" />
          Carte bancaire
        </label>
      </div>

      <div class="card-form" id="cardForm">
        <input type="text" placeholder="Nom sur la carte" id="cardName" />
        <input type="text" placeholder="Numéro de carte" maxlength="16" id="cardNumber" />
        <input type="text" placeholder="Date d'expiration (MM/AA)" id="cardExpiry" />
        <input type="text" placeholder="CVV" maxlength="3" id="cardCVV" />
      </div>
      <div class="fixed-buttons">
      <button type="button" class="btn-continue" onclick="goToStep(3)">Continuer</button>
      <button type="button" class="btn-continue" style="background:#555; margin-right: 10px;" onclick="goToStep(1)">Retour</button>
      </div>
    </div>

    <!-- Étape 3 -->
    <div class="step-box step" id="step3">
      <h2>Résumé</h2>
      <div class="summary-box" id="summaryBox"></div>
      <div class="buttons">
        <button type="button" class="btn-continue" style="background:#555;" onclick="goToStep(2)">Retour</button>
       
          <button type="submit" class="btn-continue confirm-btn">Confirmer la commande</button>
       
      </div>
    </div>

  </form>
</div>

<script>
  const steps = ['step1', 'step2', 'step3'];
  let currentStep = 0;

  const shippingOption = document.getElementById('shippingOption');
  const btnStep1 = document.getElementById('btnStep1');
  const addressForm = document.getElementById('addressForm');
  const addressInput = document.getElementById('addressInput');
  let addressEntered = false;

  shippingOption.addEventListener('click', () => {
    shippingOption.classList.add('selected');
    btnStep1.disabled = false;
  });

  btnStep1.addEventListener('click', () => {
    if (!addressEntered) {
      addressForm.style.display = 'block';
      btnStep1.textContent = 'Valider l’adresse';
      addressEntered = true;
      btnStep1.disabled = true;
    } else {
      if (addressInput.value.trim() === '') {
        alert("Veuillez entrer votre adresse !");
        btnStep1.disabled = true;
        return;
      }
      goToStep(2);
    }
  });

  addressInput.addEventListener('input', () => {
    btnStep1.disabled = addressInput.value.trim() === '';
  });

  function goToStep(stepNum) {
    currentStep = stepNum - 1;
    steps.forEach((id, idx) => {
      document.getElementById(id).classList.toggle('active', idx === currentStep);
      document.getElementById('stepIndicator' + (idx + 1)).classList.toggle('active', idx === currentStep);
    });
    if (currentStep === 2) updateSummary();
  }

  const paymentRadios = document.querySelectorAll('input[name="payment"]');
  const cardForm = document.getElementById('cardForm');

  paymentRadios.forEach(radio => {
    radio.addEventListener('change', () => {
      if (radio.value === 'carte' && radio.checked) {
        cardForm.style.display = 'flex';
        cardForm.querySelectorAll('input').forEach(input => input.required = true);
      } else {
        cardForm.style.display = 'none';
        cardForm.querySelectorAll('input').forEach(input => input.required = false);
      }
    });
  });

  if (document.querySelector('input[name="payment"]:checked').value === 'livraison') {
    cardForm.style.display = 'none';
    cardForm.querySelectorAll('input').forEach(input => input.required = false);
  }

  function updateSummary() {
    const summaryBox = document.getElementById('summaryBox');
    const shippingMethod = 'Livraison à domicile';
    const shippingPrice = '9.90 TND';
    const deliveryDate = 'Mardi 10';
    const paymentChoice = document.querySelector('input[name="payment"]:checked').value;
    const paymentText = paymentChoice === 'livraison' ? 'Paiement à la livraison' : 'Carte bancaire';
    const addressText = addressInput.value.trim();

    summaryBox.innerHTML = `
      <p><strong>Mode d'expédition :</strong> ${shippingMethod} (${shippingPrice})</p>
      <p><strong>Date de livraison estimée :</strong> ${deliveryDate}</p>
      <p><strong>Adresse :</strong> ${addressText}</p>
      <p><strong>Mode de paiement :</strong> ${paymentText}</p>
    `;
  }

  document.getElementById('checkoutForm').addEventListener('submit', e => {
    e.preventDefault();
    alert("Commande confirmée !");
  });
</script>

</body>
</html>