<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Models\Audit;
use Carbon\Carbon;

class CleanOldAudits extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'audits:clean {--days=365 : Number of days to keep} {--dry-run : Show what would be deleted without deleting}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean audits older than specified days';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = (int) $this->option('days');
        $dryRun = $this->option('dry-run');
        
        $this->info("Limpiando auditorías con más de {$days} días...");
        
        $cutoffDate = Carbon::now()->subDays($days);
        
        try {
            // Contar registros que se eliminarán
            $count = Audit::where('created_at', '<', $cutoffDate)->count();
            
            if ($count === 0) {
                $this->info('No hay auditorías antiguas para eliminar');
                return 0;
            }
            
            if ($dryRun) {
                $this->warn("DRY RUN: Se eliminarían {$count} auditorías");
                
                // Mostrar estadísticas
                $this->showStatistics($cutoffDate);
                
                return 0;
            }
            
            // Confirmar si son muchos registros
            if ($count > 10000) {
                if (!$this->confirm("Se eliminarán {$count} registros. ¿Continuar?")) {
                    $this->info('Operación cancelada');
                    return 0;
                }
            }
            
            // Eliminar en lotes para evitar problemas de memoria
            $deleted = 0;
            $batchSize = 1000;
            
            $this->output->progressStart($count);
            
            while (true) {
                $batch = Audit::where('created_at', '<', $cutoffDate)
                    ->limit($batchSize)
                    ->delete();
                
                if ($batch === 0) {
                    break;
                }
                
                $deleted += $batch;
                $this->output->progressAdvance($batch);
            }
            
            $this->output->progressFinish();
            
            $this->info("✓ Eliminadas {$deleted} auditorías anteriores a " . $cutoffDate->format('Y-m-d'));
            
            // Registrar en log
            Log::channel('audit')->info('Limpieza de auditorías completada', [
                'deleted_count' => $deleted,
                'cutoff_date' => $cutoffDate->toDateString(),
                'days_kept' => $days
            ]);
            
            // Mostrar estadísticas restantes
            $remaining = Audit::count();
            $this->info("Auditorías restantes: {$remaining}");
            
            return 0;
            
        } catch (\Exception $e) {
            $this->error('Error al limpiar auditorías: ' . $e->getMessage());
            
            Log::channel('audit')->error('Error en limpieza de auditorías', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return 1;
        }
    }
    
    /**
     * Show statistics about audits to be deleted
     */
    private function showStatistics($cutoffDate)
    {
        $this->newLine();
        $this->info('Estadísticas de auditorías a eliminar:');
        $this->newLine();
        
        // Por acción
        $byAction = Audit::where('created_at', '<', $cutoffDate)
            ->selectRaw('action, COUNT(*) as count')
            ->groupBy('action')
            ->orderBy('count', 'desc')
            ->get();
        
        if ($byAction->isNotEmpty()) {
            $this->table(
                ['Acción', 'Cantidad'],
                $byAction->map(fn($item) => [$item->action, $item->count])
            );
        }
        
        // Por modelo (top 10)
        $byModel = Audit::where('created_at', '<', $cutoffDate)
            ->selectRaw('auditable_type, COUNT(*) as count')
            ->groupBy('auditable_type')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();
        
        if ($byModel->isNotEmpty()) {
            $this->newLine();
            $this->info('Top 10 modelos:');
            $this->table(
                ['Modelo', 'Cantidad'],
                $byModel->map(fn($item) => [
                    class_basename($item->auditable_type),
                    $item->count
                ])
            );
        }
        
        // Oldest and newest
        $oldest = Audit::where('created_at', '<', $cutoffDate)
            ->orderBy('created_at', 'asc')
            ->first();
        
        if ($oldest) {
            $this->newLine();
            $this->info('Auditoría más antigua a eliminar: ' . $oldest->created_at->format('Y-m-d H:i:s'));
        }
    }
}
