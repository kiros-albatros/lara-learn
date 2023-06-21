<?php

namespace App\Helpers;

use App\Contracts\DataTransferObjects\PaginatedListDto;
use App\Exceptions\EntityValidationException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

abstract class DomainModelService
{
    /**
     * Вывести список в удобном для обработке виде
     *
     * @param Builder $query
     * @param int $perPage
     * @param array $linksQueryString
     *
     * @return PaginatedListDto
     */
    protected function toPaginatedListDto(
        Builder $query,
        int $perPage = 25,
        array $linksQueryString = []
    ): PaginatedListDto {
        $paginator = $query
            ->paginate($perPage)
            ->appends($linksQueryString);

        return new PaginatedListDto(
        // Преобразуем все модели в DTO
            data: $paginator->getCollection()->map(fn(DomainModel $entity) => $entity->toDto())->all(),
            currentPage: $paginator->currentPage(),
            lastPage: $paginator->lastPage(),
            perPage: $paginator->perPage(),
            total: $paginator->total(),
            links: $paginator->linkCollection()->toArray()
        );
    }

    /**
     * Заполнить значения модели из DTO-объекта (без сохранения)
     *
     * @param DomainModel $model
     * @param array $values
     *
     * @return DomainModel
     *
     * @throws EntityValidationException|ValidationException
     */
    public function validateAndFill(DomainModel $model, array $values): DomainModel
    {
        // Убираем значения, которые не сохраняются
        $values = array_filter($values, fn($value) => $value !== null);

        $fillableRules = $model->fillableRules();
        if ($model->exists) {
            // Если обновляем существующую модель, то валидируем только те поля, которые обновляются
            // В том числе, чтобы не усложнять unique-валидацию
            $values = array_filter($values, function ($value, $key) use ($model) {
                $oldValue = $model->{$key};
                if (is_array($value) or is_array($oldValue)) {
                    // Для массивов используем неточное сравнение, чтобы игнорировать порядок ключей
                    return $value != $oldValue;
                } else {
                    // Для всех остальных типов используем точное сравнение
                    return $value !== $oldValue;
                }
            }, ARRAY_FILTER_USE_BOTH);
            $fillableRules = array_intersect_key($fillableRules, $values);
        }

        $validator = Validator::make($values, $fillableRules);
        if ($validator->fails()) {
            $validationErrors = [];
            foreach ($validator->messages()->toArray() as $vKey => $vMessage) {
                // Убираем принадлежность ключей к JSON-полям, приводим к формату обычных полей
                $vKey = preg_replace('~^[A-Za-z\_\d]+\-\>~', '', $vKey);
                $validationErrors[$vKey] = $vMessage;
            }
            throw new EntityValidationException($validationErrors);
        }

        $validated = $validator->validated();

        // TODO Правильная обработка заполнения из "meta->" полей
        $model->fill($validated);

        return $model;
    }
}
