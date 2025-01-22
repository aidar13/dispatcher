<?php

declare(strict_types=1);

namespace App\Module\Take\DTO\IntegrationOneC;

use App\Module\Take\Models\OrderTake;
use Illuminate\Support\Carbon;

final class UpdateOrderTakeOneCDTO
{
    public string $orderNumber;
    public string $orderCreateYear;
    public ?string $invoiceNumber;
    public ?int $courierId;
    public string $directionCode;
    public int $places;
    public int|float $weight;
    public int|float $volume;
    public string $comment;
    public ?string $status;
    public ?string $invoiceCode1C;
    public ?string $takenAt;
    public ?int $waitListStatusCode;

    public function toArray(): array
    {
        $data = [
            'НомерЗаказа'    => $this->orderNumber,
            'ГодЗаказа'      => $this->orderCreateYear,
            'КодКурьера'     => $this->courierId,
            'КодНаправление' => $this->directionCode,
            'КоличествоМест' => $this->places,
            'ФизВес'         => $this->weight,
            'Объем'          => $this->volume,
            'Комментарий'    => $this->comment,
            'Статус'         => $this->status,
            'ДатаЗабора'     => $this->takenAt,
            'ЛистОжидания'   => $this->waitListStatusCode
        ];

        if ($this->invoiceCode1C) {
            $data['СистемныйНомер'] = $this->invoiceCode1C;
        }

        if ($this->invoiceNumber) {
            $data['НомерНакладной'] = $this->invoiceNumber;
        }

        return $data;
    }

    public static function fromModel(OrderTake $model): self
    {
        $self = new self();
        $self->setOrderNumber($model->order->getNumber());
        $self->setOrderCreateYear($model->order->created_at->format('Y'));
        $self->setInvoiceNumber($model->invoice->invoice_number);
        $self->setInvoiceCode1C($model->invoice->code_1c);
        $self->setCourierId($model->courier_id);
        $self->setDirectionCode((string)$model->invoice->direction_id);
        $self->setWeight($model->invoice->cargo->weight);
        $self->setPlaces($model->invoice->cargo->places);
        $self->setVolume($model->invoice->cargo->volume);
        $self->setComment($model->courier_comment ?? '');
        $self->setStatus($model->getStatusForOneC());
        $self->setTakenAt($model->getTakenAtByStatuses()?->created_at->format('Y-m-d H:i:s') ?: null);
        $self->setWaitListStatusCode($model->waitListStatus?->code);

        return $self;
    }

    public function setOrderNumber(string $orderNumber): void
    {
        $this->orderNumber = $orderNumber;
    }

    public function setOrderCreateYear(string $orderCreateYear): void
    {
        $this->orderCreateYear = $orderCreateYear;
    }

    public function setInvoiceNumber(?string $invoiceNumber): void
    {
        $this->invoiceNumber = $invoiceNumber;
    }

    public function setCourierId(?int $courierId): void
    {
        $this->courierId = $courierId;
    }

    public function setDirectionCode(string $directionCode): void
    {
        $this->directionCode = $directionCode;
    }

    public function setPlaces(int $places): void
    {
        $this->places = $places;
    }

    public function setWeight(float|int $weight): void
    {
        $this->weight = $weight;
    }

    public function setVolume(float|int $volume): void
    {
        $this->volume = $volume;
    }

    public function setComment(string $comment): void
    {
        $this->comment = $comment;
    }

    public function setStatus(?string $status): void
    {
        $this->status = $status;
    }

    public function setInvoiceCode1C(?string $invoiceCode1C): void
    {
        $this->invoiceCode1C = $invoiceCode1C;
    }

    public function setTakenAt(?string $takenAt): void
    {
        $this->takenAt = $takenAt;
    }

    public function setWaitListStatusCode(?int $waitListStatusCode): void
    {
        $this->waitListStatusCode = $waitListStatusCode;
    }
}
