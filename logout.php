<?php
require 'vendor/autoload.php';

use App\Auth;
use App\Database;

$auth = new Auth(Database::connect());
$user = $auth->getUser();
if ($user !== null) {
    $auth->logout($user['id']);
}
header('Location: login.php');
exit;
