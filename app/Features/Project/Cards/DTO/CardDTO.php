<?php
declare(strict_types=1);

namespace App\Features\Project\Cards\DTO;

class CardDTO
{
    public string $id;
    public string $code;
    public string $sectionId;
    public ?string $tagId;
    public string $responsible;
    public ?string $projectId;
    public ?string $tagProjectId;
    public string $description;
    public string $status;
    public ?string $limitDate;
}
