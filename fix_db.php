<?php
require __DIR__."/vendor/autoload.php";
$app = require_once __DIR__."/bootstrap/app.php";
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$users = App\Models\User::all();
foreach ($users as $u) {
    if ($u->office_id) {
        $u->vehicles()->update(["office_id" => $u->office_id]);
    }
}
echo "All vehicles synced with their user's office_id!\n";
?>
