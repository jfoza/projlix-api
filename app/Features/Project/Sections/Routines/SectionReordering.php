<?php

namespace App\Features\Project\Sections\Routines;

use Illuminate\Support\Facades\DB;

class SectionReordering
{
    private string $sectionId;
    private int $newOrder;
    private string $projectId;

    public function execute(): void
    {
        DB::select('SELECT project.reorder_sections(?, ?, ?)', [
            $this->getSectionId(),
            $this->getNewOrder(),
            $this->getProjectId(),
        ]);
    }

    public function getSectionId(): string
    {
        return $this->sectionId;
    }

    public function setSectionId(string $sectionId): self
    {
        $this->sectionId = $sectionId;

        return $this;
    }

    public function getNewOrder(): int
    {
        return $this->newOrder;
    }

    public function setNewOrder(int $newOrder): self
    {
        $this->newOrder = $newOrder;

        return $this;
    }

    public function getProjectId(): string
    {
        return $this->projectId;
    }

    public function setProjectId(string $projectId): self
    {
        $this->projectId = $projectId;

        return $this;
    }
}
