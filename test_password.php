<?php
// Test de vérification du mot de passe pour le joueur approuvé
$storedHash = '$2y$10$YShmh7y4OZ/SXV/Vj57yseooU5DnRNUhXLIk177OdGe6pB9RMkTMK';
$emailPassword = '7433f6317f3461f8';

$result = password_verify($emailPassword, $storedHash);
echo "Résultat de la vérification: " . ($result ? "SUCCÈS" : "ÉCHEC") . "<br>";

// Vérifier si des caractères peuvent causer des problèmes
echo "Longueur du mot de passe: " . strlen($emailPassword) . "<br>";
echo "Caractères du mot de passe: ";
for ($i = 0; $i < strlen($emailPassword); $i++) {
    echo "'" . $emailPassword[$i] . "' (" . ord($emailPassword[$i]) . ") ";
}
?>
