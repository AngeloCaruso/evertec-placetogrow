<?php

declare(strict_types=1);

namespace App\Actions\DataImports;

use App\Enums\Imports\ImportEntity;
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

        $import = new $instance();
        $import->import($model->file);

        foreach ($import->failures() as $failure) {
            $message = implode('|', $failure->errors());
            $errors[] = "Error on line {$failure->row()}. {$message}";
        }

        foreach ($import->errors() as $error) {
            $errors[] = $error;
        }

        $model->errors = $errors;
        $model->update();

        return $model;
    }
}
