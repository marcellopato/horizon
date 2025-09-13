<?php

namespace Tests\Unit;

use App\Models\SubmissionAnswer;
use Tests\TestCase;

/**
 * Unit tests for SubmissionAnswer helpers.
 *
 * @category Tests
 * @package  Unit
 * @author   Horizon Team <dev@horizon.local>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/marcellopato/horizon
 */
class SubmissionAnswerModelTest extends TestCase
{
    /**
     * It should return correct MIME type based on file extension.
     *
     * @return void
     */
    public function testGetVideoMimeTypeFromExtension(): void
    {
        $answer = new SubmissionAnswer(['video_path' => 'videos/answer-1.mp4']);
        $this->assertSame('video/mp4', $answer->getVideoMimeType());

        $answer->video_path = 'videos/answer-2.WEBM';
        $this->assertSame('video/webm', $answer->getVideoMimeType());

        $answer->video_path = 'videos/answer-3.unknown';
        // default fallback should be webm
        $this->assertSame('video/webm', $answer->getVideoMimeType());
    }
}
