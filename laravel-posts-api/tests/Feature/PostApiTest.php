<?php

namespace Tests\Feature;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_post_api_lifecycle(): void
    {
        $createResponse = $this->postJson('/api/posts', [
            'title' => 'My first post',
            'content' => 'This is the body of my first post.',
        ]);

        $createResponse
            ->assertStatus(201)
            ->assertJsonPath('data.title', 'My first post')
            ->assertJsonPath('data.content', 'This is the body of my first post.');

        $post = Post::firstOrFail();

        $this->getJson("/api/posts/{$post->id}")
            ->assertOk()
            ->assertJsonPath('title', 'My first post');

        $this->putJson("/api/posts/{$post->id}", [
            'title' => 'Updated title',
            'content' => 'Updated content',
        ])
            ->assertOk()
            ->assertJsonPath('data.title', 'Updated title')
            ->assertJsonPath('data.content', 'Updated content');

        $this->deleteJson("/api/posts/{$post->id}")
            ->assertOk()
            ->assertJsonPath('message', 'Post deleted successfully');

        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }
}
