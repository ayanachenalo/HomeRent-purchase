<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Afaan default English haa ta'u
$selected_lang = $_SESSION['lang'] ?? 'en';

// Faayila afaanii isa sirrii filachuu
if ($selected_lang == 'or') {
    include 'lang_or.php';
} elseif ($selected_lang == 'am') {
    include 'lang_am.php';
} else {
    include 'lang_en.php';
}
?>