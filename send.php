<?php
session_start();

if (!empty($_POST['website'])) {
    exit;
}

// ochrana proti opakovanému odeslání
$limit = 30;
if (isset($_SESSION['last_submit']) && (time() - $_SESSION['last_submit'] < $limit)) {
    die("Formulář byl odeslán příliš rychle.");
}
$_SESSION['last_submit'] = time();
if (
    empty($_POST['name']) ||
    empty($_POST['email']) ||
    empty($_POST['subject']) ||
    empty($_POST['message'])
) {
    die("Nejsou vyplněna všechna pole.");
}
if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    die("Neplatná e-mailová adresa.");
}


    $to = "arny.javapami@atlas.cz";

    $name = htmlspecialchars($_POST["name"]);
    $email = htmlspecialchars($_POST["email"]);
    $subject = htmlspecialchars($_POST["subject"]);
    $message = htmlspecialchars($_POST["message"]);

    $headers = "From: Teva okna <info@tevaokna.cz>\r\n";
    $headers .= "Reply-To: $email\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    $body  = "Jméno: $name\n";
    $body .= "E-mail: $email\n\n";
    $body .= "Zpráva:\n$message";

    if (mail($to, $subject, $body, $headers)) {
        header("Location: dekujeme.html");
        exit;
    } else {
        echo "Došlo k chybě při odesílání zprávy.";
    }
?>
