<?php 

# Copyright (C) 2019 Torsten Markmann
# Mail: info@uplinked.net 
# WWW: edudocs.org uplinked.net

# This program is free software: you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation, either version 3 of the License, or
# any later version.

# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.

# You should have received a copy of the GNU General Public License
# along with this program.  If not, see <https://www.gnu.org/licenses/>.

?>

<ul>
  <li><span style="font-size:175%;">Tresor öffnen:</span></li>

<?php
// require config.php for vault_token_base and vault_url
require("config/config.php");

$userid=\OC::$server->getUserSession()->getUser()->getUID();
$uri = explode('/', $_SERVER['REQUEST_URI']);
$instance = $uri[1];
$file_name = $CONFIG['vault_token_base'].$userid.".token";
$cookie_name = "vaulttoken_".$CONFIG['vault_instance']."_".$userid;
$vault_link = $CONFIG['vault_url']."/login?user=$userid";

$token = substr(str_replace(['+', '/', '='], '', base64_encode(random_bytes(32))), 0, 32);

$secure_connection = FALSE;
if (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == "on")) {
  $secure_connection = TRUE;
}

setcookie($cookie_name, $token, time() + (600), "/", $_SERVER['HTTP_HOST'], $secure_connection, TRUE);

file_put_contents($file_name, $token);

echo "<li><a href='$vault_link' target='_new' alt='Zum Tresor'>";
echo "<img src='/$instance/apps/vaultkey/img/vault-tb.png' class='vaultlink' /></a></li>";

?>

  <li style="margin-bottom:1em;">Ihr Sicherheits-Token ist 10 Minuten gültig.</li>
  <li><a href="<?php echo $CONFIG['htaccess.RewriteBase']; ?>apps/vaultkey/" class="refreshbutton">Neuen Token erzeugen</a></li>
</ul>
