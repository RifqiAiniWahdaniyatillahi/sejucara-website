<?php

namespace Tests\Feature;

use App\Models\ChatConversation;
use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ChatTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_send_a_chat_message(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $response = $this->actingAs($user)->postJson(route('chat.store'), [
            'message' => 'Apakah produk cascara lemon masih tersedia?',
        ]);

        $response->assertOk()
            ->assertJsonPath('message', 'Apakah produk cascara lemon masih tersedia?')
            ->assertJsonPath('is_mine', true);

        $this->assertDatabaseHas('chat_conversations', ['user_id' => $user->id]);
        $this->assertDatabaseHas('chat_messages', [
            'sender_id' => $user->id,
            'message' => 'Apakah produk cascara lemon masih tersedia?',
        ]);
    }

    public function test_admin_can_reply_and_user_marks_reply_as_read(): void
    {
        $user = User::factory()->create(['is_admin' => false]);
        $admin = User::factory()->create(['is_admin' => true]);
        $conversation = ChatConversation::create(['user_id' => $user->id]);

        $this->actingAs($admin)->postJson(route('admin.chat.store', $conversation), [
            'message' => 'Produk masih tersedia.',
        ])->assertOk();

        $message = ChatMessage::where('chat_conversation_id', $conversation->id)->firstOrFail();
        $this->assertNull($message->read_at);

        $this->actingAs($user)->get(route('chat.index'))->assertOk();

        $this->assertNotNull($message->fresh()->read_at);
    }

    public function test_user_can_send_a_chat_image(): void
    {
        Storage::fake('public');

        $user = User::factory()->create(['is_admin' => false]);

        $response = $this->actingAs($user)->postJson(route('chat.store'), [
            'message' => '',
            'image' => UploadedFile::fake()->image('produk-rusak.jpg', 800, 600),
        ]);

        $response->assertOk()
            ->assertJsonPath('is_mine', true)
            ->assertJson(fn ($json) => $json
                ->where('message', '')
                ->whereNot('image_url', null)
                ->etc()
            );

        $message = ChatMessage::firstOrFail();

        $this->assertNotNull($message->image_path);
        Storage::disk('public')->assertExists($message->image_path);
    }

    public function test_regular_user_cannot_access_admin_chat(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $this->actingAs($user)
            ->get(route('admin.chat.index'))
            ->assertForbidden();
    }

    public function test_admin_cannot_create_a_customer_conversation_from_user_route(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)
            ->get(route('chat.index'))
            ->assertForbidden();
    }
}
