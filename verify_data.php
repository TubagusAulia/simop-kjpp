<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== USERS ===\n";
foreach (\App\Models\User::all() as $u) {
    echo "  [{$u->id}] {$u->name} | {$u->username} | {$u->role}\n";
}

echo "\n=== PROYEK ===\n";
foreach (\App\Models\Proyek::all() as $p) {
    echo "  [{$p->id}] {$p->nama_proyek} | {$p->status} | {$p->start_date} → {$p->due_date}\n";
    echo "    Allocated users: ";
    $names = $p->users->map(fn($u) => "{$u->name} ({$u->role})")->join(', ');
    echo $names . "\n";
}

echo "\n=== ALLOCATIONS PER ROLE ===\n";
$roles = ['admin', 'karyawan', 'client', 'mitra'];
foreach ($roles as $role) {
    $user = \App\Models\User::where('role', $role)->first();
    if (!$user) continue;
    $proyeks = $user->proyeks;
    echo "  {$user->name} ({$role}): " . $proyeks->count() . " proyek\n";
    foreach ($proyeks as $p) {
        echo "    - {$p->nama_proyek} [{$p->status}]\n";
    }
}
