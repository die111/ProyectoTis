<?php
namespace App\Traits;

use App\Services\AuditService;

trait Auditable
{
    public static function bootAuditable()
    {
        static::created(function ($model) {
            app(AuditService::class)->record($model, 'created');
        });

        static::updated(function ($model) {
            app(AuditService::class)->record($model, 'updated');
        });

        static::deleted(function ($model) {
            app(AuditService::class)->record($model, 'deleted');
        });
    }
}
