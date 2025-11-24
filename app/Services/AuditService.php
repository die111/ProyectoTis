<?php
namespace App\Services;

use App\Models\Audit;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AuditService
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function record($model, string $action, array $meta = [])
    {
        if ($model instanceof Audit) {
            return;
        }

        $user = Auth::user();
        $old = method_exists($model, 'getOriginal') ? $model->getOriginal() : null;
        $new = $model->getAttributes();

        $old = $this->filterAttributes($old);
        $new = $this->filterAttributes($new);

        $meta = array_merge($this->gatherMeta(), $meta);

        Audit::create([
            'auditable_type' => get_class($model),
            'auditable_id'   => $model->getKey(),
            'user_id'        => $user ? $user->id : null,
            'action'         => $action,
            'old_values'     => $old,
            'new_values'     => $new,
            'meta'           => $meta,
            'created_at'     => now(),
        ]);
    }

    protected function filterAttributes($attrs)
    {
        if (!is_array($attrs)) return null;
        unset($attrs['password'], $attrs['remember_token']);
        return $attrs;
    }

    protected function gatherMeta()
    {
        return [
            'ip' => $this->request->ip(),
            'user_agent' => $this->request->userAgent(),
            'route' => optional($this->request->route())->getName() ?? $this->request->path(),
            'url' => $this->request->fullUrl(),
        ];
    }
}
