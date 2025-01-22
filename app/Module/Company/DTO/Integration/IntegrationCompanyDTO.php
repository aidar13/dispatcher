<?php

declare(strict_types=1);

namespace App\Module\Company\DTO\Integration;

final class IntegrationCompanyDTO
{
    public int $id;
    public string $name;
    public ?string $shortName;
    public string $contactPhone;
    public string $contactName;
    public ?string $contactEmail;
    public ?string $contractNumber;
    public string $jurAddress;
    public string $factAddress;
    public string $bank;
    public string $bankAccount;
    public string $bin;
    public string $bik;
    public ?string $ndsNumber;
    public ?string $code1c;
    public int $managerId;

    public static function fromEvent($event): self
    {
        $self                 = new self();
        $self->id             = $event->DTO->id;
        $self->name           = $event->DTO->name;
        $self->shortName      = $event->DTO->shortName;
        $self->contactPhone   = $event->DTO->contactPhone;
        $self->contactName    = $event->DTO->contactName;
        $self->contactEmail   = $event->DTO->contactEmail;
        $self->contractNumber = $event->DTO->contractNumber;
        $self->jurAddress     = $event->DTO->jurAddress;
        $self->factAddress    = $event->DTO->factAddress;
        $self->bank           = $event->DTO->bank;
        $self->bankAccount    = $event->DTO->bankAccount;
        $self->bin            = $event->DTO->bin;
        $self->bik            = $event->DTO->bik;
        $self->ndsNumber      = $event->DTO->ndsNumber;
        $self->code1c         = $event->DTO->code;
        $self->managerId      = $event->DTO->managerId;

        return $self;
    }
}
