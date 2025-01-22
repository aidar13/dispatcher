<?php

declare(strict_types=1);

namespace Tests\Feature\File;

use App\Models\User;
use App\Module\File\Commands\CreateFileCommand;
use App\Module\File\Commands\DeleteFileCommand;
use App\Module\File\Models\File;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

final class FileTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function testCreateFile()
    {
        Storage::fake('s3');

        $fakeFile = UploadedFile::fake()->image('avatar.png');

        /** @var File $file */
        $file = File::factory()->make(['id' => 1]);

        dispatch(new CreateFileCommand(
            $fakeFile,
            $file->type,
            $file->path,
            $fakeFile->getClientOriginalName(),
            $file->client_id,
            $file->client_type,
            $file->user_id,
        ));

        $this->assertDatabaseHas('files', [
            'id'            => $file->id,
            'type'          => $file->type,
            'original_name' => $fakeFile->getClientOriginalName(),
            'client_id'     => $file->client_id,
            'client_type'   => $file->client_type,
            'user_id'       => $file->user_id,
        ]);
    }

    public function testDeleteFile()
    {
        Storage::fake('s3');

        /** @var File $file */
        $file = File::factory()->create();

        dispatch(new DeleteFileCommand(
            $file->id
        ));

        $this->assertSoftDeleted('files', [
            'id' => $file->id,
        ]);
    }

    public function testUploadFile()
    {
        Storage::fake('s3');

        /** @var User $user */
        $user = User::factory()->create();

        $fakeFile = UploadedFile::fake()->image('avatar.png');

        /** @var File $file */
        $file = File::factory()->make(['id' => 1]);

        $data = [
            'type'     => $this->faker->randomElement([
                File::TYPE_COURIER_IDENTIFICATION_CARD,
                File::TYPE_COURIER_DRIVER_LICENSE,
                File::TYPE_CAR_TECHNICAL_PASSPORT,
            ]),
            'clientId' => $file->client_id,
            'file'     => $fakeFile,
        ];

        $this->actingAs($user)
            ->post(
                route('file.upload'),
                $data,
            );

        $this->assertDatabaseHas('files', [
            'type'          => $data['type'],
            'original_name' => $fakeFile->getClientOriginalName(),
            'client_id'     => $data['clientId'],
            'client_type'   => File::getClientType($data['type']),
            'user_id'       => $user->id,
        ]);
    }

    public function testDeleteFileWithoutAws()
    {
        /** @var File $file */
        $file = File::factory()->create();

        $this->delete(route('file.destroy', $file->uuid_hash));

        $this->assertSoftDeleted('files', [
            'id' => $file->id,
        ]);
    }
}
