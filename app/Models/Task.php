<?php

namespace App\Models;

use App\Models\Scopes\PriorityScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;

#[ScopedBy([PriorityScope::class])]
class Task extends Model
{
    /**
     * The attributes that are mass assignable.
     * 
     * @var array
     */
    protected $fillable = [
        'name', 
        'priority', 
        'project_id'
    ];

    /**
     * Get the project that owns the task.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Scope a query to only include tasks of a given priority.
     * 
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $priority
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('name', 'like', '%'.$search.'%');
        });

        $query->when($filters['priority'] ?? null, function ($query, $priority) {
            $query->where('priority', $priority);
        });

        $query->when($filters['project_id'] ?? null, function ($query, $project_id) {
            $query->where('project_id', $project_id);
        });
    }
}
