<?php

namespace App\Helpers;

use Illuminate\Database\Eloquent\Model;

abstract class DomainModel extends Model
{
    /**
     * Получить DTO-представление объекта для передачи из сервисного слоя.
     *
     * @return mixed DTO-объект, представляющий модель
     */
    abstract public function toDto(): mixed;

    /**
     * Заполняемые поля и правила валидации при сохранении из Data Transfer Objects
     * @return array
     */
    abstract public function fillableRules(): array;

    /**
     * Список mass-assignable полей берем из общей спецификации полей-правил.
     * @inheritDoc
     */
    public function getFillable(): ?array
    {
        return array_keys($this->fillableRules());
    }
}
