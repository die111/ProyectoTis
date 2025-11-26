<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // create permission if not exists
        $perm = DB::table('permissions')->where('name', 'view_audits')->first();
        if (!$perm) {
            $id = DB::table('permissions')->insertGetId([
                'name' => 'view_audits',
                'description' => 'Permite ver la bitacora (audits)',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Attach to roles that look like admin
            $roles = DB::table('roles')->whereRaw("LOWER(name) LIKE ?", ['%admin%'])->get();
            if ($roles->isEmpty()) {
                // fallback: role id 1
                $role = DB::table('roles')->where('id', 1)->first();
                if ($role) {
                    DB::table('role_permission')->insert([
                        'role_id' => $role->id,
                        'permission_id' => $id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            } else {
                foreach ($roles as $r) {
                    DB::table('role_permission')->insert([
                        'role_id' => $r->id,
                        'permission_id' => $id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }

    public function down(): void
    {
        $perm = DB::table('permissions')->where('name', 'view_audits')->first();
        if ($perm) {
            DB::table('role_permission')->where('permission_id', $perm->id)->delete();
            DB::table('permissions')->where('id', $perm->id)->delete();
        }
    }
};
