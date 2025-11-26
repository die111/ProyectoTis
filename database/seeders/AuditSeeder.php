<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Audit;
use App\Models\User;
use App\Models\Inscription;
use App\Models\Evaluation;
use Illuminate\Support\Arr;

class AuditSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();
        $inscriptions = Inscription::all();
        $evaluations = Evaluation::all();

        $actions = ['created', 'updated', 'deleted'];

        // If there are no models yet, create a few placeholder audits
        if ($users->isEmpty()) {
            Audit::create([
                'auditable_type' => 'App\\Models\\User',
                'auditable_id' => 1,
                'user_id' => null,
                'action' => 'created',
                'old_values' => null,
                'new_values' => ['name' => 'Admin ejemplo', 'email' => 'admin@example.com'],
                'meta' => ['ip' => '127.0.0.1', 'route' => 'seeder.example'] ,
                'created_at' => now(),
            ]);
            return;
        }

        // Create random audits using existing models
        for ($i = 0; $i < 20; $i++) {
            $type = Arr::random(['inscription','evaluation','user']);
            if ($type === 'inscription' && $inscriptions->isNotEmpty()) {
                $model = Arr::random($inscriptions->toArray());
                $auditable_type = Inscription::class;
                $auditable_id = $model['id'];
            } elseif ($type === 'evaluation' && $evaluations->isNotEmpty()) {
                $model = Arr::random($evaluations->toArray());
                $auditable_type = Evaluation::class;
                $auditable_id = $model['id'];
            } else {
                $model = Arr::random($users->toArray());
                $auditable_type = User::class;
                $auditable_id = $model['id'];
            }

            $action = Arr::random($actions);

            $old = null;
            $new = null;
            if ($action === 'created') {
                $old = null;
                $new = ['sample_field' => 'valor '.$i];
            } elseif ($action === 'updated') {
                $old = ['nota' => rand(1,20)];
                $new = ['nota' => rand(21,40)];
            } else {
                $old = ['deleted' => true];
                $new = null;
            }

            Audit::create([
                'auditable_type' => $auditable_type,
                'auditable_id' => $auditable_id,
                'user_id' => $users->random()->id,
                'action' => $action,
                'old_values' => $old,
                'new_values' => $new,
                'meta' => ['ip' => '127.0.0.'.rand(2,200), 'route' => 'seeder.run'],
                'created_at' => now()->subMinutes(rand(0,5000)),
            ]);
        }
    }
}
