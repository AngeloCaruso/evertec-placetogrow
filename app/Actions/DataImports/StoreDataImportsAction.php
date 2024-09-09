<?php

declare(strict_types=1);

namespace App\Actions\DataImports;

use App\Enums\Imports\ImportEntity;
use App\Jobs\ProcessDataImport;
use Illuminate\Database\Eloquent\Model;

class StoreDataImportsAction
{
    public static function exec(array $data, Model $model): ?Model
    {
        $entity = ImportEntity::tryFrom($data['entity']);

        if (!$entity) {
            return null;
        }

        $model = $model->create($data);
        $instance = $entity->getImportable();

        ProcessDataImport::dispatch(new $instance($model), $model);

        return $model;
    }
}
