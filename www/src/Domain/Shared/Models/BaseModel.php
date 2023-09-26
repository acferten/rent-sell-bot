<?php

namespace Domain\Shared\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

abstract class BaseModel extends Model
{
    use HasFactory;

    public $incrementing = true;
    public $timestamps = true;

    protected static function newFactory()
    {
        $parts = Str::of(get_called_class())->explode("\\");
        $domain = $parts[1];
        $model = $parts->last();
        $folderModel = $parts[$parts->count()-2];

        return app("Database\\Factories\\{$domain}\\{$folderModel}\\{$model}Factory");
    }
}
