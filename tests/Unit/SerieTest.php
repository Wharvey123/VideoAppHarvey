<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Serie;
use App\Models\Video;
use App\Models\User; // Import the User model in case you need to assign the user_id
use Illuminate\Foundation\Testing\RefreshDatabase;

class SerieTest extends TestCase
{
    use RefreshDatabase;

    public function test_serie_have_videos()
    {
        // Arrange: Create a series instance.
        $serie = Serie::factory()->create();

        // Create a user to associate with videos if required by the Video factory/model
        $user = User::factory()->create();

        // Act: Create 3 videos associated with the series.
        Video::factory()->count(3)->create([
            'series_id' => $serie->id,
            'user_id'   => $user->id, // Providing a valid user_id if required by the model.
        ]);

        // Refresh the series model to make sure the videos relationship is updated.
        $serie->refresh();

        // Retrieve the videos count using the relationship.
        $videosCount = $serie->videos()->count();

        // Assert: Verify the series has exactly 3 videos.
        $this->assertEquals(3, $videosCount);
    }
}
