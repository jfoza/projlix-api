<?php
declare(strict_types=1);

namespace Tests\Unit\Resources;

use App\Shared\Libraries\Uuid;
use App\Shared\Utils\Hash;
use Illuminate\Support\Collection;
use Tests\Unit\Mocks\ProfileMocks;
use Tests\Unit\Mocks\ProjectMocks;

class AuthUserMock
{
    public string $id;
    public string $name;
    public string $email;
    public string $password;
    public bool $active;
    public Collection $profiles;
    public TeamUserMock $teamUser;

    public function __construct()
    {
        $this->setId(Uuid::uuid4Generate());
        $this->setName('User Mock');
        $this->setEmail('user-mock@email.com');
        $this->setPassword(Hash::generateHash('12345'));
        $this->setActive(true);
        $this->setProfiles(Collection::make([ProfileMocks::getProjectManager()]));
        $this->setTeamUser();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): void
    {
        $this->active = $active;
    }

    public function getProfiles(): Collection
    {
        return $this->profiles;
    }

    public function setProfiles(Collection $profiles): void
    {
        $this->profiles = $profiles;
    }

    public function getTeamUser(): TeamUserMock
    {
        return $this->teamUser;
    }

    public function setTeamUser(): void
    {
        $this->teamUser = new TeamUserMock(
            Uuid::uuid4Generate(),
            $this->getId(),
            ProjectMocks::getAllProjects()
        );
    }
}
