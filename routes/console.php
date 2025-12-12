<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Log;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// ============================================
// Tareas Programadas de Backups y Limpieza
// ============================================

// Backup diario de base de datos a las 2:00 AM (comprimido)
Schedule::command('db:backup --compress --keep=7')
    ->dailyAt('02:00')
    ->onSuccess(function () {
        Log::channel('audit')->info('Backup automático completado exitosamente');
    })
    ->onFailure(function () {
        Log::channel('audit')->error('Error en backup automático programado');
    });

// Limpieza mensual de auditorías (primer día del mes a las 3:00 AM)
Schedule::command('audits:clean --days=365')
    ->monthlyOn(1, '03:00')
    ->onSuccess(function () {
        Log::channel('audit')->info('Limpieza de auditorías completada exitosamente');
    })
    ->onFailure(function () {
        Log::channel('audit')->error('Error en limpieza de auditorías');
    });
