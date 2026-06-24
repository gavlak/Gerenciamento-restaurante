<?php
// Execute este arquivo UMA VEZ para gerar o hash da senha do admin.
// Acesse: http://localhost/PhpProjs/gerar_hash.php
// Copie o hash gerado e substitua no database.sql, depois delete este arquivo.

$senha = 'secret123';
$hash  = password_hash($senha, PASSWORD_BCRYPT);

echo "<pre>\n";
echo "Senha: $senha\n";
echo "Hash:  $hash\n\n";
echo "Cole este hash no INSERT do database.sql\n";
echo "</pre>";
