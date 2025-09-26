<?php

// Path to your bash script
$bashScript = '/home/hadir/updatessl_hadir.sh'; // Ganti dengan jalur skrip bash Anda

// Menjalankan skrip bash
$output = shell_exec("bash $bashScript");

// Output hasil dari skrip bash
echo "<pre>$output</pre>";

?>
