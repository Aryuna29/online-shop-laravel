<?php

namespace App\Services\Clients\DTO;

class YougileTaskDto
{
    public function __construct(
       private string $title,
       private string $columnId,
       private string $description,
    )
    {
    }


    public function getDescription(): string
    {
        return $this->description;
    }

    public function getColumnId(): string
    {
        return $this->columnId;
    }

    public function getTitle(): string
    {
        return $this->title;
    }


    public function getArray(): array
    {
        return [
            'title' => $this->getTitle(),
            'columnId' => $this->getColumnId(),
            'description' => $this->getDescription(),
        ];
    }
}
