<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/phpmailer/src/Exception.php';
require __DIR__ . '/phpmailer/src/PHPMailer.php';
require __DIR__ . '/phpmailer/src/SMTP.php';

$zpravaOK = false;
if (!empty($_SESSION['zpravaOK'])) {
    $zpravaOK = true;
    unset($_SESSION['zpravaOK']);
}

$EMAIL_KAM = "arny.javapami@atlas.cz"; 
$MIN_INTERVAL = 60;
if (empty($_SESSION['csrf_token'])) {
  $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$chyba = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

if (!empty($_POST['website'])) {
exit; 
}

if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
$chyba = "Neplatný formulář.";
}

if (empty($chyba)) {
$cas = time();
if (isset($_SESSION['last_submit']) && ($cas - $_SESSION['last_submit']) < $MIN_INTERVAL) {
$chyba = "Formulář byl odeslán příliš brzy. Zkuste to prosím později.";
}
}

if (empty($chyba)) {
$jmeno = trim($_POST['jmeno'] ?? '');
$email = trim($_POST['email'] ?? '');
$zprava = trim($_POST['zprava'] ?? '');

if (!$jmeno || !$email || !$zprava) {
$chyba = "Vyplňte prosím všechna pole.";
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
$chyba = "Neplatná e‑mailová adresa.";
}
}

if (empty($chyba)) {
    $text = "Jméno: $jmeno\nE‑mail: $email\n\nZpráva:\n$zprava";

try {
    $mail = new PHPMailer(true);
    $mail->CharSet = 'UTF-8';

$mail->isMail();


   $mail->setFrom('web@DANARNY.COM', 'Danarny web');
    $mail->addAddress('arny.javapami@atlas.cz');
    $mail->addReplyTo($email, $jmeno);

    $mail->Subject = 'Zpráva z webu Danarny';
    $mail->Body =
        "Jméno: $jmeno\n" .
        "E-mail: $email\n\n" .
        "Zpráva:\n$zprava";

    $mail->send();

    $_SESSION['last_submit'] = time();
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    $_SESSION['zpravaOK'] = true;

    header("Location: index.php#kontakt");
    exit;

} catch (Exception $e) {
    $chyba = "E-mail se nepodařilo odeslat.";
}

}
}
?>

<!DOCTYPE html>
<html lang="cs">
<head>
<meta name="robots" content="follow, index"/>
<meta name="author" content="Danarny"/>
<link rel="shortcut icon" href="Danarny.ico" type="image/x-icon"/>
<META NAME="keywords" CONTENT="dalmatin, dalmatian, dalmatiner, chovatelská stanice dalmatinů, chovatelská stanice dalmatin, 
chovná stanice dalmatinů, chov dalmatinů, chovka dalmatinů, chovatel dalmatin, chovatel= dalmatinů, chs dalmatin, chst dalmatin, 
štěňata dalmatin, štěně dalmatin, kennel, stanice, stud, dog, mating, decken, deckruede, puppy, puppies, 
welpe, welpen, zucht, zuchtstaette, FCI, nabídka štěňat, coursing, pracovní dalmatin, Písková Lhota, Mladá Boleslav, štěňata, chovatelská stanice, Danarny, štěňátka k odběru, zdraví psi">
  <meta name="description" content="Oficiální stránka Danarny – chovatelská stanice dalmatinů a čínských chocholatých psů, s nabídkou tvorby a 
  správy webů.">

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Danarny – Chov dalmatinů, čínských chocholatých psů a tvorba webů</title>
<style>
  body {
    margin: 0;
    font-family: Arial, Helvetica, sans-serif;
    background: url('most.webp') no-repeat center center fixed;
    background-size: cover;
    color: #fff;
    text-align: center;
  }
  .overlay {
    background-color: rgba(0, 0, 50, 0.6);
    display: block;
    flex-direction: column;
    justify-content: flex-start; 
    overflow-y: auto; 
    align-items: center;
    padding: 20px 20px;
  }
  h1 {
    margin-top: 0;
    font-family: "Comic Sans MS", "Comic Sans";
    font-size: 6rem;
    letter-spacing: 5px;
    color: royalblue;
    text-shadow: 3px 3px 10px black;
    animation: fadeZoom 1.2s ease-out;
    margin-bottom: 0.2em;
  }
  p.tagline {
    font-size: 1.3rem;
    color: #eee;
    margin-bottom: 2em;
  }
  .cards {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 20px;
  }
  .card {
    background-color: rgba(255,255,255,0.1);
    border-radius: 15px;
    padding: 20px;
    width: 280px;
    transition: 0.3s;
    backdrop-filter: blur(5px);
    box-shadow: 0 0 10px rgba(0,0,0,0.4);
  }
  .card:hover {
    transform: scale(1.05);
    background-color: rgba(255,255,255,0.2);
  }
  .card img {
    width: 100%;
    border-radius: 10px;
    margin-bottom: 15px;
  }
  .card h2 {
    color: rgb(11, 4, 116);
    margin-bottom: 10px;
  }
  a {
    color: white;
    text-decoration: none;
  }
  footer {
    margin-top: 2em;
    font-size: 0.9em;
    color: #ccc;
  }
@keyframes fadeZoom {
  0% { transform: scale(0.8); opacity: 0; }
  100% { transform: scale(1); opacity: 1; }
}

  @media (max-width: 768px) {
    h1 { font-size: 3.5rem; }
    .card { width: 90%; }
  }
.formular {
  max-width: 500px;
  margin: 40px auto;
  background: rgba(255,255,255,0.95);
  padding: 20px;
  border-radius: 12px;
  color: #000;
}
label { display:block; margin-top:12px; }
input, textarea { width:100%; padding:8px; box-sizing: border-box; }
button {
margin-top:15px;
width:100%;
padding:10px;
background:#0B0474;
color:white;
border:none;
border-radius:8px;
}
.chyba { color:red; margin-top:10px; }
.uspech { color: #0B0474; font-weight:bold; }

.send-animation {
    display: none;
    text-align: center;
    margin-top: 30px;
    position: relative;
}

.send-animation.active {
    display: block;
}

.paper {
    width: 80px;
    height: 100px;
    background: #ffffff;
    border: 2px solid #ccc;
    margin: 0 auto;
    animation: fold 1.5s ease forwards;
}

.envelope {
    width: 90px;
    height: 60px;
    background: #2f5fd7;
    margin: -40px auto 0;
    opacity: 0;
    animation: fly 2.0s ease forwards;
    animation-delay: 1.5s;
}

.send-text {
    margin-top: 15px;
    color: #555;
    font-size: 0.95rem;
    opacity: 0;
    animation: fadeIn 0.4s ease forwards;
    animation-delay: 3.5s;
}

/* Animace */
@keyframes fold {
    0% { transform: scale(1); }
    100% { transform: scale(0.6) rotate(10deg); }
}

@keyframes fly {
    0% {
        opacity: 1;
        transform: translateY(0);
    }
    100% {
        opacity: 0;
        transform: translate(80px, -60px);
    }
}

@keyframes fadeIn {
    to { opacity: 1; }
}

/* Mobil – jemnější */
@media (max-width: 600px) {
    .paper { width: 60px; height: 80px; }
    .envelope { width: 70px; height: 45px; }
}

</style>
</head>
<body>
  <div class="overlay">
    <h1>Danarny</h1>
    <p class="tagline">Spojujeme svět psů a webů</p>
   <div class="cards">

      <a href="../dalmatini.php" class="card">
        <img src="dalmatin.png" alt="Dalmatin" width="50">
        <h2>Dalmatini</h2>
        <p>Chovatelská stanice plná puntíků a radosti ze života.</p>
      </a>

      <a href="../cinani.php" class="card">
        <img src="cinan.png" alt="Čínský chocholatý pes" width="50">
        <h2>Čínský chocholatý pes</h2>
        <p>Elegantní, citlivé a oddané plemeno s jedinečným kouzlem.</p>
      </a>
 
      <a href="weby/weby.html" class="card">
        <img src="pc1.webp" alt="Tvorba a správa webů" width="50">
        <h2>Správa webů</h2>
        <p>Nabízím tvorbu, údržbu a modernizaci webových stránek.</p>
      </a>
<br><br>
    </div>
<h2 id="kontakt">kontakt</h2><br>
<h3>Máte zájem o štěňátko, webové stránky nebo se chcete na něco zeptat? Napište mi!</h3>
<h3>Ráda vám odpovím co nejdříve to bude možné.</h3>
<h3>Kateřina Burešová, okres Mladá Boleslav, arny.javapami@atlas.cz, tel 604 577 135</h3>
<h3><a href="https://www.facebook.com/katerina.buresova.718" target="_blank">Facebook Kateřina Burešová</a></h3>

<div class="formular">

<?php if ($zpravaOK): ?>
<p class="uspech">Zpráva byla úspěšně odeslána 🐾</p>
<?php else: ?>

<?php if ($chyba): ?>
<div class="chyba"><?= htmlspecialchars($chyba) ?></div>
<?php endif; ?>

<form method="post">
<label>Jméno
<input type="text" name="jmeno" required>
</label>

<label>E‑mail
<input type="email" name="email" required>
</label>

<label>Zpráva
<textarea name="zprava" required></textarea>
</label>

<input type="text" name="website" tabindex="-1" autocomplete="off" style="position:absolute; left:-9999px;">

<input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

<button type="submit">Odeslat</button>
</form>
<div class="send-animation" aria-hidden="true">
    <div class="paper"></div>
    <div class="envelope"></div>
    <p class="send-text">Zpráva byla odeslána</p>
</div>

<?php endif; ?>

</div>
    <footer>© 2025 Danarny | Všechna práva vyhrazena</footer>
  </div>
  <script>
document.querySelector("form").addEventListener("submit", function () {
    document.querySelector(".send-animation").classList.add("active");
});
</script>

</body>
</html>
