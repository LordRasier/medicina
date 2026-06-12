<?php

namespace Tests\Feature;

use App\honorario as Honorario;
use App\Mail\honorario as HonorarioMail;
use App\sub_menu;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class HonorarioImportTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_import_honorario_file_for_matching_user_code()
    {
        Mail::fake();
        Storage::fake('local');

        $admin = $this->createUserWithAccess('/honorarios/create');
        $professional = $this->createUser(['code' => 'MED123']);
        $file = UploadedFile::fake()->create('Planilla - 2026-06-20 - MED123-detalle.pdf', 20, 'application/pdf');

        $this->actingAs($admin)
            ->put('/honorarios/create/store', ['file' => [$file]])
            ->assertOk()
            ->assertViewHas('result', true);

        $honorario = Honorario::where('user_id', $professional->id)->first();

        $this->assertNotNull($honorario);
        $this->assertSame('2026-06-20', $honorario->fecha);
        Storage::assertExists($honorario->file);
        Mail::assertSent(HonorarioMail::class, 1);
    }

    public function test_admin_can_view_honorario_import_form()
    {
        $admin = $this->createUserWithAccess('/honorarios/create');

        $this->actingAs($admin)
            ->get('/honorarios/create')
            ->assertOk()
            ->assertSee('Cargar honorarios')
            ->assertSee('Formato esperado');
    }

    public function test_import_skips_files_with_invalid_legacy_name()
    {
        Mail::fake();
        Storage::fake('local');

        $admin = $this->createUserWithAccess('/honorarios/create');
        $file = UploadedFile::fake()->create('archivo-sin-formato.pdf', 20, 'application/pdf');

        $this->actingAs($admin)
            ->put('/honorarios/create/store', ['file' => [$file]])
            ->assertOk()
            ->assertViewHas('result', true);

        $this->assertDatabaseCount('honorarios', 0);
        Mail::assertNothingSent();
    }

    private function createUserWithAccess(string $url): User
    {
        $user = $this->createUser();

        $subMenu = sub_menu::where('url', $url)->firstOrFail();
        $user->access()->attach($subMenu->id);

        return $user;
    }

    private function createUser(array $overrides = []): User
    {
        $user = new User();
        $user->code = uniqid('TST');
        $user->name = 'Test User';
        $user->doc = uniqid('doc');
        $user->email = uniqid('test') . '@example.com';
        $user->password = Hash::make('password');
        $user->specialty = 1;
        $user->type = 1;
        $user->dedication = 1;

        foreach ($overrides as $key => $value) {
            $user->{$key} = $value;
        }

        $user->save();

        return $user;
    }
}
