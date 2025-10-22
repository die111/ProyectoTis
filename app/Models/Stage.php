<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Etapa extends Model
{
    /**
     * Nombre de la tabla
     */
    protected $table = 'stages';

    /**
     * Clave primaria personalizada
     */
    protected $primaryKey = 'id_stage';

    /**
     * Asignación masiva permitida
     */
    protected $fillable = [
        'nombre',
        'fechaInicio',
        'fechaFin',
        'id_competicion'
    ];

    /**
     * Conversión de tipos de datos
     */
    protected $casts = [
        'fechaInicio' => 'datetime',
        'fechaFin' => 'datetime',
        'id_etapa' => 'integer',
        'id_competicion' => 'integer',
    ];

    /**
     * Fechas que deben ser tratadas como Carbon instances
     */
    protected $dates = [
        'fechaInicio',
        'fechaFin',
        'created_at',
        'updated_at',
    ];

    /**
     * Validación antes de guardar
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($etapa) {
            if ($etapa->fechaFin <= $etapa->fechaInicio) {
                throw new \InvalidArgumentException('La fecha de fin debe ser posterior a la fecha de inicio.');
            }
        });
    }

    /**
     * Relación con Competicion (una etapa pertenece a una competición)
     */
    public function competicion(): BelongsTo
    {
        return $this->belongsTo(Competicion::class, 'id_competicion', 'id_competicion');
    }

    /* ========== SCOPES ========== */

    /**
     * Scope para filtrar por nombre
     */
    public function scopeByNombre($query, $nombre)
    {
        return $query->where('nombre', 'like', '%' . $nombre . '%');
    }

    /**
     * Scope para etapas activas (entre fechas actuales)
     */
    public function scopeActivas($query)
    {
        $now = now();
        return $query->where('fechaInicio', '<=', $now)
                    ->where('fechaFin', '>=', $now);
    }

    /**
     * Scope para etapas futuras
     */
    public function scopeFuturas($query)
    {
        return $query->where('fechaInicio', '>', now());
    }

    /**
     * Scope para etapas finalizadas
     */
    public function scopeFinalizadas($query)
    {
        return $query->where('fechaFin', '<', now());
    }

    /**
     * Scope para ordenar por fecha de inicio
     */
    public function scopeOrdenadaPorFecha($query, $direccion = 'asc')
    {
        return $query->orderBy('fechaInicio', $direccion);
    }

    /**
     * Scope para etapas de una competición específica
     */
    public function scopeDeCompeticion($query, $competicionId)
    {
        return $query->where('id_competicion', $competicionId);
    }

    /* ========== MÉTODOS DE ESTADO ========== */

    /**
     * Verificar si la etapa está activa
     */
    public function isActiva(): bool
    {
        $now = now();
        return $this->fechaInicio <= $now && $this->fechaFin >= $now;
    }

    /**
     * Verificar si la etapa ya terminó
     */
    public function haTerminado(): bool
    {
        return $this->fechaFin < now();
    }

    /**
     * Verificar si la etapa aún no ha comenzado
     */
    public function noHaComenzado(): bool
    {
        return $this->fechaInicio > now();
    }

    /**
     * Obtener el estado actual de la etapa
     */
    public function getEstado(): string
    {
        if ($this->noHaComenzado()) {
            return 'pendiente';
        }
        
        if ($this->isActiva()) {
            return 'activa';
        }
        
        return 'finalizada';
    }

    /**
     * Obtener el estado formateado para mostrar
     */
    public function getEstadoFormateado(): string
    {
        return match($this->getEstado()) {
            'pendiente' => 'Pendiente',
            'activa' => 'En Curso',
            'finalizada' => 'Finalizada',
            default => 'Desconocido'
        };
    }

    /* ========== MÉTODOS DE CÁLCULO ========== */

    /**
     * Obtener la duración de la etapa en días
     */
    public function getDuracionEnDias(): int
    {
        return $this->fechaInicio->diffInDays($this->fechaFin);
    }

    /**
     * Obtener la duración de la etapa en horas
     */
    public function getDuracionEnHoras(): int
    {
        return $this->fechaInicio->diffInHours($this->fechaFin);
    }

    /**
     * Obtener el progreso de la etapa (porcentaje 0-100)
     */
    public function getProgreso(): float
    {
        if ($this->noHaComenzado()) {
            return 0.0;
        }

        if ($this->haTerminado()) {
            return 100.0;
        }

        $totalMinutos = $this->fechaInicio->diffInMinutes($this->fechaFin);
        $minutosTranscurridos = $this->fechaInicio->diffInMinutes(now());
        
        return $totalMinutos > 0 ? round(($minutosTranscurridos / $totalMinutos) * 100, 2) : 0.0;
    }

    /**
     * Obtener días restantes para que termine la etapa
     */
    public function getDiasRestantes(): int
    {
        if ($this->haTerminado()) {
            return 0;
        }
        
        return now()->diffInDays($this->fechaFin, false);
    }

    /**
     * Obtener días transcurridos desde el inicio
     */
    public function getDiasTranscurridos(): int
    {
        if ($this->noHaComenzado()) {
            return 0;
        }
        
        return $this->fechaInicio->diffInDays(now());
    }

    /* ========== MÉTODOS DE FORMATO ========== */

    /**
     * Formatear fecha de inicio para mostrar
     */
    public function getFechaInicioFormateada(): string
    {
        return $this->fechaInicio->format('d/m/Y H:i');
    }

    /**
     * Formatear fecha de fin para mostrar
     */
    public function getFechaFinFormateada(): string
    {
        return $this->fechaFin->format('d/m/Y H:i');
    }

    /**
     * Obtener rango de fechas formateado
     */
    public function getRangoFechas(): string
    {
        return $this->getFechaInicioFormateada() . ' - ' . $this->getFechaFinFormateada();
    }

    /* ========== MÉTODOS ESTÁTICOS ========== */

    /**
     * Obtener etapas activas
     */
    public static function getEtapasActivas()
    {
        return static::activas()->orderBy('fechaInicio')->get();
    }

    /**
     * Obtener próxima etapa a iniciar
     */
    public static function getProximaEtapa()
    {
        return static::futuras()->orderBy('fechaInicio')->first();
    }

    /**
     * Contar etapas por estado
     */
    public static function contarPorEstado(): array
    {
        $total = static::count();
        $activas = static::activas()->count();
        $futuras = static::futuras()->count();
        $finalizadas = static::finalizadas()->count();

        return [
            'total' => $total,
            'activas' => $activas,
            'futuras' => $futuras,
            'finalizadas' => $finalizadas
        ];
    }
    
    // Relacion uno a muchos con Competicion
    public function competiciones()
    {
        return $this->hasMany(Competicion::class, 'id_competicion');
    }   
}