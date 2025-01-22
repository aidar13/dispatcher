<?php

declare(strict_types=1);

namespace App\Module\Company\Models;

use App\Models\User;
use Carbon\Carbon;
use Database\Factories\CompanyFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $name
 * @property string|null $short_name
 * @property string $bin
 * @property string $contact_phone
 * @property string $contact_name
 * @property string|null $contact_email
 * @property string $jur_address
 * @property string $fact_address
 * @property string|null $nds_number
 * @property string $bank
 * @property string $bik
 * @property string $bank_account
 * @property int|null $manager_id
 * @property string|null $code_1c
 * @property string|null $contract_number
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read User $manager
 */
final class Company extends Model
{
    use HasFactory;
    use SoftDeletes;

    public const COMPANY_SPARK_DELIVERY_ID = 777;
    public const COMPANY_JPOST_ID = 153;

    protected static function newFactory(): CompanyFactory
    {
        return CompanyFactory::new();
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setShortName(?string $short_name): void
    {
        $this->short_name = $short_name;
    }

    public function setBin(string $bin): void
    {
        $this->bin = $bin;
    }

    public function setContactPhone(string $contact_phone): void
    {
        $this->contact_phone = $contact_phone;
    }

    public function setContactName(string $contact_name): void
    {
        $this->contact_name = $contact_name;
    }

    public function setContactEmail(?string $contact_email): void
    {
        $this->contact_email = $contact_email;
    }

    public function setJurAddress(string $jur_address): void
    {
        $this->jur_address = $jur_address;
    }

    public function setFactAddress(string $fact_address): void
    {
        $this->fact_address = $fact_address;
    }

    public function setNdsNumber(?string $nds_number): void
    {
        $this->nds_number = $nds_number;
    }

    public function setBank(string $bank): void
    {
        $this->bank = $bank;
    }

    public function setBik(string $bik): void
    {
        $this->bik = $bik;
    }

    public function setBankAccount(string $bank_account): void
    {
        $this->bank_account = $bank_account;
    }

    public function setCode1c(?string $code_1c): void
    {
        $this->code_1c = $code_1c;
    }

    public function setContractNumber(?string $contract_number): void
    {
        $this->contract_number = $contract_number;
    }

    public function setManagerId(int $manager_id): void
    {
        $this->manager_id = $manager_id;
    }

    public function getName(): ?string
    {
        return $this->short_name ?: $this->name;
    }

    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }
}
