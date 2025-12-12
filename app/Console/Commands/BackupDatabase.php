<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class BackupDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:backup {--compress : Compress the backup with gzip} {--keep=7 : Number of backups to keep}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup the database with optional compression and automatic cleanup';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Iniciando backup de base de datos...');
        
        $timestamp = Carbon::now()->format('Y-m-d_His');
        $filename = "backup-{$timestamp}.sql";
        $backupPath = storage_path('app/backups');
        
        // Crear directorio si no existe
        if (!file_exists($backupPath)) {
            mkdir($backupPath, 0755, true);
            $this->info('Directorio de backups creado');
        }
        
        $fullPath = $backupPath . '/' . $filename;
        
        // Obtener configuración de base de datos
        $connection = config('database.default');
        $database = config("database.connections.{$connection}.database");
        $username = config("database.connections.{$connection}.username");
        $password = config("database.connections.{$connection}.password");
        $host = config("database.connections.{$connection}.host");
        $port = config("database.connections.{$connection}.port");
        $driver = config("database.connections.{$connection}.driver");
        
        try {
            if ($driver === 'pgsql') {
                // PostgreSQL
                $this->backupPostgres($host, $port, $database, $username, $password, $fullPath);
            } elseif ($driver === 'mysql') {
                // MySQL
                $this->backupMysql($host, $database, $username, $password, $fullPath);
            } else {
                $this->error("Driver de base de datos no soportado: {$driver}");
                return 1;
            }
            
            // Verificar que el archivo se creó
            if (!file_exists($fullPath) || filesize($fullPath) === 0) {
                throw new \Exception('El archivo de backup está vacío o no se creó');
            }
            
            $size = $this->formatBytes(filesize($fullPath));
            $this->info("✓ Backup creado: {$filename} ({$size})");
            
            // Comprimir si se solicitó
            if ($this->option('compress')) {
                $this->info('Comprimiendo backup...');
                
                // Usar compresión ZIP nativa de PHP (compatible con Windows)
                $zipPath = str_replace('.sql', '.zip', $fullPath);
                $zip = new \ZipArchive();
                
                if ($zip->open($zipPath, \ZipArchive::CREATE) === true) {
                    $zip->addFile($fullPath, basename($fullPath));
                    $zip->close();
                    
                    // Eliminar archivo SQL original
                    unlink($fullPath);
                    
                    $filename = str_replace('.sql', '.zip', $filename);
                    $fullPath = $zipPath;
                    
                    if (file_exists($fullPath)) {
                        $compressedSize = $this->formatBytes(filesize($fullPath));
                        $this->info("✓ Backup comprimido: {$filename} ({$compressedSize})");
                    }
                } else {
                    $this->warn('No se pudo comprimir el backup, se mantendrá sin comprimir');
                }
            }
            
            // Registrar en log
            Log::channel('audit')->info('Backup de base de datos creado', [
                'filename' => $filename,
                'database' => $database,
                'size' => filesize($fullPath),
                'compressed' => $this->option('compress')
            ]);
            
            // Limpiar backups antiguos
            $this->cleanOldBackups($this->option('keep'));
            
            $this->info('✓ Backup completado exitosamente');
            return 0;
            
        } catch (\Exception $e) {
            $this->error('Error al crear backup: ' . $e->getMessage());
            Log::channel('audit')->error('Error en backup de base de datos', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return 1;
        }
    }
    
    /**
     * Backup PostgreSQL database
     */
    private function backupPostgres($host, $port, $database, $username, $password, $path)
    {
        // Intentar encontrar pg_dump en ubicaciones comunes
        $pgDumpPaths = [
            'C:\\Program Files\\PostgreSQL\\15\\bin\\pg_dump.exe',
            'C:\\Program Files\\PostgreSQL\\16\\bin\\pg_dump.exe',
            'C:\\Program Files\\PostgreSQL\\14\\bin\\pg_dump.exe',
            'C:\\Program Files\\PostgreSQL\\13\\bin\\pg_dump.exe',
            'C:\\xampp\\pgsql\\bin\\pg_dump.exe',
            'pg_dump', // En PATH como fallback
        ];
        
        $pgDump = null;
        foreach ($pgDumpPaths as $path_exe) {
            if (file_exists($path_exe) || $path_exe === 'pg_dump') {
                $pgDump = $path_exe;
                break;
            }
        }
        
        if (!$pgDump) {
            throw new \Exception('pg_dump no encontrado. Instale PostgreSQL o agregue pg_dump al PATH del sistema.');
        }
        
        // Configurar variable de entorno para password
        putenv("PGPASSWORD={$password}");
        
        $command = sprintf(
            '"%s" -h %s -p %s -U %s -F p -b -v -f "%s" %s 2>&1',
            $pgDump,
            escapeshellarg($host),
            escapeshellarg($port),
            escapeshellarg($username),
            $path,
            escapeshellarg($database)
        );
        
        exec($command, $output, $returnVar);
        
        // Limpiar variable de entorno
        putenv("PGPASSWORD");
        
        if ($returnVar !== 0) {
            throw new \Exception('Error en pg_dump: ' . implode("\n", $output));
        }
    }
    
    /**
     * Backup MySQL database
     */
    private function backupMysql($host, $database, $username, $password, $path)
    {
        $command = sprintf(
            'mysqldump -h %s -u %s -p%s %s > "%s" 2>&1',
            escapeshellarg($host),
            escapeshellarg($username),
            escapeshellarg($password),
            escapeshellarg($database),
            $path
        );
        
        exec($command, $output, $returnVar);
        
        if ($returnVar !== 0) {
            throw new \Exception('Error en mysqldump: ' . implode("\n", $output));
        }
    }
    
    /**
     * Clean old backups, keeping only the specified number
     */
    private function cleanOldBackups($keep = 7)
    {
        $this->info("Limpiando backups antiguos (manteniendo últimos {$keep})...");
        
        $backupPath = storage_path('app/backups');
        $files = glob($backupPath . '/backup-*.sql*');
        
        if (count($files) <= $keep) {
            $this->info('No hay backups antiguos para eliminar');
            return;
        }
        
        // Ordenar por fecha de modificación (más antiguos primero)
        usort($files, function($a, $b) {
            return filemtime($a) - filemtime($b);
        });
        
        // Eliminar los más antiguos
        $toDelete = array_slice($files, 0, count($files) - $keep);
        $deletedCount = 0;
        $freedSpace = 0;
        
        foreach ($toDelete as $file) {
            $freedSpace += filesize($file);
            if (unlink($file)) {
                $deletedCount++;
            }
        }
        
        if ($deletedCount > 0) {
            $freed = $this->formatBytes($freedSpace);
            $this->info("✓ Eliminados {$deletedCount} backups antiguos (liberados {$freed})");
            
            Log::channel('audit')->info('Backups antiguos eliminados', [
                'deleted_count' => $deletedCount,
                'freed_space' => $freedSpace
            ]);
        }
    }
    
    /**
     * Format bytes to human readable format
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
}
