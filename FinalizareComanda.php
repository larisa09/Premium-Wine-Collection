<?php
session_start();
include 'Conexiune.php';
if (!isset($_SESSION['utilizator_id'])) {
    header('Location: Login.php?redirect=finalizare&msg=auth_required');
    exit();
}


?>
<!DOCTYPE html>
<html lang="ro">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Finalizare Comandă - Premium Wine</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
.radio-group label {
  display: flex;
  align-items: center;
  justify-content: space-between; /* pune textul și pretul la capetele opuse */
  padding: 8px 12px;
  border: 1px solid #ccc;
  border-radius: 6px;
  margin-bottom: 10px;
  cursor: pointer;
  transition: background-color 0.3s;
}

.radio-group label:hover {
  background-color: #f0f0f0;
}

.radio-group input[type="radio"] {
  /* mărime mai mare pentru bullet */
  width: 20px;
  height: 20px;
  margin-right: 12px;
  cursor: pointer;
}

.pret-livrare {
  font-weight: 600;
  color: #555;
}

/* La metoda de plata sa fie asemanator */
#sectiune4 .radio-group label {
  justify-content: flex-start;
}

#sectiune4 .radio-group label input[type="radio"] {
  margin-right: 15px;
}

  body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(to right, #e0d0cd, #cbbab5);
      margin: 0;
      padding: 20px;
    }

    h1 {
      text-align: center;
    }

    h2 {
      background-color: #333;
      color: white;
      padding: 10px;
      margin: 0;
    }

    form {
      background: white;
      padding: 20px;
      border-radius: 8px;
      max-width: 700px;
      margin: 20px auto;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }

    .section {
      display: none;
      margin-top: 20px;
    }

    .section.active {
      display: block;
    }

    label {
      display: block;
      margin-bottom: 6px;
      font-weight: bold;
    }

    input {
      width: 100%;
      padding: 10px;
      margin-bottom: 20px;
      border: 1px solid #ccc;
      border-radius: 5px;
      box-sizing: border-box;
    }

    .radio-group {
      display: flex;
      flex-direction: column;
      margin-bottom: 20px;
    }

    .back-btn {
  background-color: #999;
  color: white;
  padding: 12px 20px;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  margin-top: 10px;
  margin-right: 10px;
}

.back-btn:hover {
  background-color: #777;
}

    .submit-btn, .next-btn {
      background-color: #333;
      color: white;
      padding: 12px 20px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      margin-top: 10px;
    }

    .submit-btn:hover, .next-btn:hover {
      background-color: #555;
    }

    #detalii-card {
      margin-top: 20px;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
      background-color: #f5f5f5;
    }

    #confirmare {
      display: none;
      text-align: center;
      padding: 30px;
      background: #dff0d8;
      color: #3c763d;
      font-weight: bold;
      border-radius: 8px;
      max-width: 600px;
      margin: 20px auto;
      font-size: 1.2em;
    }
  </style>
</head>
<body>


<form id="formularComanda" method="POST">

  <div class="section active" id="sectiune1">
    <h2>1. PERSONAL INFORMATION</h2>
    <label for="nume">Last Name</label>
    <input type="text" id="nume" name="nume" required>

    <label for="prenume">First Name</label>
    <input type="text" id="prenume" name="prenume" required>

    <label for="email">Email</label>
    <input type="email" id="email" name="email" required>

    <button type="button" class="next-btn" onclick="goToNext(1)">Continue</button>
  </div>

  <div class="section" id="sectiune2">
    <h2>2.DELIVERY ADRESS</h2>
    <label for="adresa">Street</label>
    <input type="text" id="adresa" name="adresa" required>

    <label for="oras">Town</label>
    <input type="text" id="oras" name="oras" required>

    <label for="judet">Country</label>
    <input type="text" id="judet" name="judet" required>

    <label for="cod_postal">Postal Code</label>
    <input type="text" id="cod_postal" name="cod_postal" required>
    <button type="button" class="back-btn" onclick="goToPrevious(2)">Go Back</button>
    <button type="button" class="next-btn" onclick="goToNext(2)">Continue</button>
  </div>

  <div class="section" id="sectiune3">
    <h2>3.DELIVERY METHOD</h2>
    <div class="radio-group">
  <label><input type="radio" name="livrare" value="Sameday" required> Sameday <span class="pret-livrare">20 lei</span></label>
  <label><input type="radio" name="livrare" value="Fancurier"> Fancurier <span class="pret-livrare">15 lei</span></label>
  <label><input type="radio" name="livrare" value="posta"> Poșta Română <span class="pret-livrare">10 lei</span></label>
  <label><input type="radio" name="livrare" value="easy box"> Easy Box <span class="pret-livrare">FREE</span></label>
</div>
    <button type="button" class="back-btn" onclick="goToPrevious(3)">Go Back</button>
    <button type="button" class="next-btn" onclick="goToNext(3)">Continue</button>
  </div>

  <div class="section" id="sectiune4">
    <h2>4.PAYMENT METHOD</h2>
    <div class="radio-group">
       <label><input type="radio" name="plata" value="ramburs"> Ramburs</label>
      <label><input type="radio" name="plata" value="card" required> Card </label>
     
    </div>

    <div id="detalii-card" style="display: none;">
      <label for="nr_card">Card Number</label>
      <input type="text" id="nr_card" name="nr_card" placeholder="0000 0000 0000 0000">

      <label for="nume_card">Name</label>
      <input type="text" id="nume_card" name="nume_card" placeholder="Nume complet">

      <label for="expirare">Good thru</label>
      <input type="text" id="expirare" name="expirare" placeholder="MM/YY">

      <label for="cvv">CVV</label>
      <input type="text" id="cvv" name="cvv" placeholder="123">
    </div>
    <button type="button" class="back-btn" onclick="goToPrevious(4)">Go Back</button>
    <button type="button" class="submit-btn" onclick="finalizeazaComanda()">Send Order</button>
  </div>
</form>


<div class="confirmation-container" id="confirmare">
  <div class="success-icon">✓</div>
  <h1>Order successfully finished!</h1>
  <div class="thank-you">Thank you for choosing Premium Wine Collection!</div>
  
  <div class="order-number">
   Order Number: #<?php echo rand(100000, 999999); ?>
  </div>
  
  <div class="confirmation-message">
    Your order has been prossesed.
  </div>
  
  <div class="order-details">
    <h3>Order details</h3>
    
    <div class="detail-row">
      <div class="detail-label">Name:</div>
      <div class="detail-value" id="confirm-nume"></div>
    </div>
    
    <div class="detail-row">
      <div class="detail-label">Email:</div>
      <div class="detail-value" id="confirm-email"></div>
    </div>
    
    <div class="detail-row">
      <div class="detail-label"> Delivery Adress:</div>
      <div class="detail-value" id="confirm-adresa"></div>
    </div>
    
    <div class="detail-row">
      <div class="detail-label">Delivery method:</div>
      <div class="detail-value" id="confirm-livrare"></div>
    </div>
    
    <div class="detail-row">
      <div class="detail-label">Payment method:</div>
      <div class="detail-value" id="confirm-plata"></div>
    </div>
    
    <div class="detail-row">
      <div class="detail-label">Date:</div>
      <div class="detail-value"><?php echo date('d.m.Y H:i'); ?></div>
    </div>
    
    <div class="detail-row">
      <div class="detail-label">Status:</div>
      <div class="detail-value" style="color: #28a745; font-weight: bold;">Confirmed</div>
    </div>
  </div>
  
  <a href="index.php" class="btn-primary"> Back to Home Page</a>
</div>


<script>
  function goToNext(currentSection) {
  const section = document.getElementById('sectiune' + currentSection);
  const inputs = section.querySelectorAll('input[required]');
  let valid = true;

  inputs.forEach(input => {
    if (!input.checkValidity()) {
      input.reportValidity();
      valid = false;
    }
  });

  if (valid) {
    section.classList.remove('active');
    const nextSection = document.getElementById('sectiune' + (currentSection + 1));
    if (nextSection) nextSection.classList.add('active');
  }
}

function goToPrevious(currentSection) {
  const section = document.getElementById('sectiune' + currentSection);
  const prevSection = document.getElementById('sectiune' + (currentSection - 1));

  if (prevSection) {
    section.classList.remove('active');
    prevSection.classList.add('active');
  }
}



  document.addEventListener("DOMContentLoaded", function () {
    const metodePlata = document.getElementsByName("plata");
    metodePlata.forEach(radio => {
      radio.addEventListener("change", function () {
        const detaliiCard = document.getElementById("detalii-card");
        if (this.value === "card") {
          detaliiCard.style.display = "block";
          detaliiCard.querySelectorAll("input").forEach(input => input.required = true);
        } else {
          detaliiCard.style.display = "none";
          detaliiCard.querySelectorAll("input").forEach(input => input.required = false);
        }
      });
    });
  });

  
  function finalizeazaComanda() {
  const formular = document.getElementById("formularComanda");
  const inputs = formular.querySelectorAll("input[required], input[type=radio]:checked");
  let valid = true;

  inputs.forEach(input => {
    if (!input.checkValidity()) {
      input.reportValidity();
      valid = false;
    }
  });

  if (valid) {
    const formData = new FormData(formular);

    fetch("trimite_comanda.php", {
      method: "POST",
      body: formData
    })
    .then(response => response.json())
    .then(data => {
  if (data.success) {
  if (data.success) {

   
  document.querySelector("#confirmare .order-number").textContent = "Număr comandă: #" + data.nr_comanda;

  const nume = document.getElementById('nume').value + ' ' + document.getElementById('prenume').value;
  const email = document.getElementById('email').value;
  const adresa = document.getElementById('adresa').value + ', ' + document.getElementById('oras').value + ', ' + document.getElementById('judet').value + ', ' + document.getElementById('cod_postal').value;

  const livrareRadio = document.querySelector('input[name="livrare"]:checked');
  const livrare = livrareRadio ? livrareRadio.value : '';

  const plataRadio = document.querySelector('input[name="plata"]:checked');
  const plata = plataRadio ? (plataRadio.value === 'card' ? 'Card bancar' : 'Ramburs la livrare') : '';

  

  document.getElementById('confirm-nume').textContent = nume;
  document.getElementById('confirm-email').textContent = email;
  document.getElementById('confirm-adresa').textContent = adresa;
  document.getElementById('confirm-livrare').textContent = livrare;
  document.getElementById('confirm-plata').textContent = plata;

  
  document.getElementById("formularComanda").style.display = "none";
  document.getElementById("confirmare").style.display = "block";
}

  } else if (data.redirect) {
    window.location.href = data.redirect; 
  } else {
    alert("Eroare: " + (data.error || "Comanda nu a fost procesată."));
  }
})
    .catch(error => {
      console.error("Eroare:", error);
      alert("Eroare de rețea sau server.");
    });
  }
}

  
</script>

</body>

</html>
