<?php

namespace Tests\Feature;

use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VideosTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that users can view existing videos.
     */
    public function test_users_can_view_videos()
    {
        // Create a video in the database
        $video = Video::factory()->create([
            'title' => 'Test Video',
            'description' => 'This is a test video.',
            'published_at' => now(),
        ]);

        // Perform a GET request to the video's route
        $response = $this->get(route('video.show', $video->id));

        // Assert the response is successful and contains video details
        $response->assertStatus(200);
        $response->assertSee($video->title);
        $response->assertSee($video->description);
    }

    /**
     * Test that users cannot view non-existing videos.
     */
    public function test_users_cannot_view_not_existing_videos()
    {
        // Perform a GET request to a non-existing video's route
        $response = $this->get(route('video.show', 999));

        // Assert the response returns a 404 status code
        $response->assertStatus(404);
    }
}
