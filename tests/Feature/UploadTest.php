<?php

namespace Tests\Feature;

use App\Models\Accessories;
use App\Models\Modal;
use App\Models\Room;
use App\Models\User;
use Database\Seeders\DemoSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Tests\TestCase;

class UploadTest extends TestCase
{
    use RefreshDatabase;

    private array $publicImgBefore;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DemoSeeder::class);
        Storage::fake('public');
        $this->actingAs(User::find(1));
        $this->publicImgBefore = $this->publicImgFiles();
    }

    private function publicImgFiles(): array
    {
        $files = [];
        foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator(public_path('img'), RecursiveDirectoryIterator::SKIP_DOTS)) as $file) {
            $files[] = $file->getPathname();
        }
        sort($files);

        return $files;
    }

    private function assertPublicImgUnchanged(): void
    {
        $this->assertSame($this->publicImgBefore, $this->publicImgFiles(), 'upload wrote under public_path(img)');
    }

    private function assertStoredOnPublicDiskOnly(string $path): void
    {
        Storage::disk('public')->assertExists($path);
        $this->assertPublicImgUnchanged();
    }

    public function test_add_room_stores_image_on_public_disk()
    {
        $this->post('/admin/add-room', [
            'RoomName' => 'Upload room',
            'RoomNumber' => '9',
            'RoomAmount' => 4,
            'Image_room' => UploadedFile::fake()->image('upload-add-room.jpg'),
        ])->assertJson(['code' => 1]);

        $this->assertSame('upload-add-room.jpg', Room::where('RoomName', 'Upload room')->value('Image_room'));
        $this->assertStoredOnPublicDiskOnly('img/Image_Room/upload-add-room.jpg');
    }

    public function test_update_room_replaces_image_on_public_disk()
    {
        $room = Room::first();
        Storage::disk('public')->put('img/Image_Room/'.$room->Image_room, 'old');

        $this->post('/admin/updateRoomDetails', [
            'rid' => $room->RoomID,
            'RoomName' => $room->RoomName,
            'RoomNumber' => $room->RoomNumber,
            'RoomAmount' => $room->RoomAmount,
            'Image_room_update' => UploadedFile::fake()->image('upload-update-room.jpg'),
        ])->assertJson(['code' => 1]);

        Storage::disk('public')->assertMissing('img/Image_Room/'.$room->Image_room);
        $this->assertStoredOnPublicDiskOnly('img/Image_Room/upload-update-room.jpg');
    }

    public function test_delete_room_keeps_image_on_public_disk()
    {
        $room = Room::first();
        Storage::disk('public')->put('img/Image_Room/'.$room->Image_room, 'old');

        $this->post('/admin/deleteRoom', ['room_id' => $room->RoomID])->assertJson(['code' => 1]);

        $this->assertNull(Room::find($room->RoomID));
        Storage::disk('public')->assertExists('img/Image_Room/'.$room->Image_room);
    }

    public function test_delete_accessory_keeps_image_on_public_disk()
    {
        $acc = Accessories::create(['Name' => 'Doomed acc', 'Quantity' => 1, 'Image_acc' => 'doomed-acc.jpg']);
        Storage::disk('public')->put('img/Image_Accessories/doomed-acc.jpg', 'old');

        $this->post('/admin/deleteAcc', ['acc_id' => $acc->AccessoriesID])->assertJson(['code' => 1]);

        $this->assertNull(Accessories::find($acc->AccessoriesID));
        Storage::disk('public')->assertExists('img/Image_Accessories/doomed-acc.jpg');
    }

    public function test_add_accessory_stores_image_on_public_disk()
    {
        $this->post('/admin/add-acc', [
            'AccName' => 'Upload acc',
            'AccQuantity' => 2,
            'Image_acc' => UploadedFile::fake()->image('upload-add-acc.jpg'),
        ])->assertJson(['code' => 1]);

        $this->assertStoredOnPublicDiskOnly('img/Image_Accessories/upload-add-acc.jpg');
    }

    public function test_update_accessory_replaces_image_on_public_disk()
    {
        $acc = Accessories::create(['Name' => 'Old acc', 'Quantity' => 1, 'Image_acc' => 'old-acc.jpg']);
        Storage::disk('public')->put('img/Image_Accessories/old-acc.jpg', 'old');

        $this->post('/admin/updateAccDetails', [
            'aid' => $acc->AccessoriesID,
            'AccName' => 'Old acc',
            'AccQuantity' => 1,
            'Image_acc_update' => UploadedFile::fake()->image('upload-update-acc.jpg'),
        ])->assertJson(['code' => 1]);

        Storage::disk('public')->assertMissing('img/Image_Accessories/old-acc.jpg');
        $this->assertStoredOnPublicDiskOnly('img/Image_Accessories/upload-update-acc.jpg');
    }

    public function test_add_user_stores_no_picture()
    {
        $this->post('/admin/add-users', [
            'Name' => 'Upload user',
            'email' => 'upload-user@example.test',
            'password' => 'secret',
            'DepartmentID' => 1,
            'roleID' => 2,
            'picture' => UploadedFile::fake()->image('upload-add-user.jpg'),
        ])->assertJson(['code' => 1]);

        $this->assertNull(User::where('email', 'upload-user@example.test')->firstOrFail()->getAttributes()['picture']);
        $this->assertSame([], Storage::disk('public')->allFiles());
        $this->assertPublicImgUnchanged();
    }

    public function test_change_profile_picture_stores_image_on_public_disk()
    {
        $this->post('/admin/change-profile-picture', [
            'admin_image' => UploadedFile::fake()->image('avatar.jpg'),
        ])->assertJson(['status' => 1]);

        $picture = User::find(1)->getAttributes()['picture'];
        $this->assertStringStartsWith('UIMG_', $picture);
        $this->assertStoredOnPublicDiskOnly('users/images/'.$picture);
    }

    public function test_update_modal_stores_image_on_public_disk()
    {
        $modal = Modal::create(['image' => 'old-modal.jpg', 'text' => 'notice']);
        Storage::disk('public')->put('img/Image_Room/old-modal.jpg', 'old');

        $this->post('/updateModalDetails', [
            'mid' => $modal->id,
            'text' => 'notice',
            'Image_modal_update' => UploadedFile::fake()->image('upload-modal.jpg'),
        ])->assertJson(['code' => 1]);

        Storage::disk('public')->assertMissing('img/Image_Room/old-modal.jpg');
        $this->assertStoredOnPublicDiskOnly('img/Image_Room/upload-modal.jpg');
    }
}
