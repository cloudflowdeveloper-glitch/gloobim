<?php

namespace App\Services;

use FFMpeg\FFMpeg;
use FFMpeg\FFProbe;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\Coordinate\Dimension;
use FFMpeg\Format\Video\X264;

/**
 * Video processing service using php-ffmpeg/php-ffmpeg (Composer).
 * 
 * Wraps FFmpeg/FFProbe binaries through an object-oriented PHP API
 * instead of raw shell_exec() calls.
 *
 * Package: composer require php-ffmpeg/php-ffmpeg
 * Docs:     https://github.com/PHP-FFMpeg/PHP-FFMpeg
 */
class VideoProcessor
{
    private ?FFMpeg $ffmpeg = null;
    private ?FFProbe $ffprobe = null;
    private int $reelDuration; // seconds per reel
    private array $config;

    public function __construct(int $reelDuration = 30)
    {
        $this->reelDuration = min(60, max(10, $reelDuration)); // clamp 10-60s
        $this->config = $this->buildConfig();

        if ($this->isAvailable()) {
            try {
                $this->ffmpeg  = FFMpeg::create($this->config);
                $this->ffprobe = FFProbe::create($this->config);
            } catch (\Exception $e) {
                // Binary detection failed — leave null, isAvailable() returns false
                error_log('VideoProcessor: FFmpeg init failed — ' . $e->getMessage());
            }
        }
    }

    // -----------------------------------------------------------------------
    // Public API — same interface as before, now powered by php-ffmpeg
    // -----------------------------------------------------------------------

    public function isAvailable(): bool
    {
        return !empty($this->config['ffmpeg.binaries'] ?? null);
    }

    /**
     * Get video duration in seconds using FFProbe.
     */
    public function getDuration(string $videoPath): float
    {
        if (!$this->ffprobe || !file_exists($videoPath)) {
            return 0;
        }

        try {
            return (float) $this->ffprobe->format($videoPath)->get('duration');
        } catch (\Exception $e) {
            error_log('VideoProcessor::getDuration failed: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Generate a thumbnail from video at specified time.
     */
    public function generateThumbnail(
        string $videoPath,
        string $outputPath,
        float $atSecond = 1
    ): bool {
        if (!$this->ffmpeg || !file_exists($videoPath)) {
            return false;
        }

        try {
            $video = $this->ffmpeg->open($videoPath);
            $video
                ->frame(TimeCode::fromSeconds($atSecond))
                ->save($outputPath);

            return file_exists($outputPath) && filesize($outputPath) > 0;
        } catch (\Exception $e) {
            error_log('VideoProcessor::generateThumbnail failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Split a video into reel-length segments.
     * Returns array of segment metadata.
     *
     * NOTE: Uses FFMpeg\Media\Video::clip() which re-encodes each segment
     * (cleaner API, but slower than stream-copy). For bulk splits with
     * hundreds of segments, consider a dedicated batch processor.
     */
    public function splitIntoReels(
        string $videoPath,
        string $outputDir,
        string $baseName = 'reel'
    ): array {
        if (!$this->ffmpeg || !file_exists($videoPath)) {
            return [];
        }

        $duration = $this->getDuration($videoPath);
        if ($duration <= 0) {
            return [];
        }

        // Don't split short videos
        if ($duration <= $this->reelDuration + 5) {
            return [[
                'path'     => $videoPath,
                'start'    => 0,
                'duration' => $duration,
                'index'    => 1,
            ]];
        }

        if (!is_dir($outputDir)) {
            @mkdir($outputDir, 0755, true);
        }

        $segments    = [];
        $segmentCount = (int) ceil($duration / $this->reelDuration);
        $format      = new X264();
        $format->setKiloBitrate(2000)
               ->setAudioCodec('aac')
               ->setAudioKiloBitrate(128);

        // Open once and create clips
        $video = $this->ffmpeg->open($videoPath);

        for ($i = 0; $i < $segmentCount; $i++) {
            $startTime    = $i * $this->reelDuration;
            $segDuration   = min($this->reelDuration, $duration - $startTime);

            // Skip very short tail segments (< 5s)
            if ($segDuration < 5) {
                continue;
            }

            $outputFile = $outputDir . '/' . $baseName . '_' . sprintf('%02d', $i + 1) . '.mp4';

            try {
                $clip = $video->clip(
                    TimeCode::fromSeconds($startTime),
                    TimeCode::fromSeconds($segDuration)
                );
                $clip->save($format, $outputFile);

                if (file_exists($outputFile) && filesize($outputFile) > 0) {
                    $segments[] = [
                        'path'     => $outputFile,
                        'start'    => $startTime,
                        'duration' => $segDuration,
                        'index'    => $i + 1,
                    ];
                }
            } catch (\Exception $e) {
                error_log("VideoProcessor::splitIntoReels segment {$i} failed: " . $e->getMessage());
                continue;
            }
        }

        return $segments;
    }

    /**
     * Create a portrait/vertical crop version for reels (9:16 aspect).
     *
     * Center-crops the source and scales to 720×1280 — ready for
     * TikTok / Reels / Shorts.
     */
    public function cropToReel(string $videoPath, string $outputPath): bool
    {
        if (!$this->ffmpeg || !file_exists($videoPath)) {
            return false;
        }

        try {
            $video = $this->ffmpeg->open($videoPath);

            // Calculate center-crop for 9:16 from source dimensions
            $streams = $video->getStreams();
            $videoStream = $streams->videos()->first();
            $srcW = $videoStream->get('width', 1920);
            $srcH = $videoStream->get('height', 1080);

            $cropW = (int) ($srcH * 9 / 16);
            $cropH = $srcH;
            $cropX = (int) (($srcW - $cropW) / 2);

            $video
                ->filters()
                ->crop(
                    new \FFMpeg\Coordinate\Point($cropX, 0),
                    new Dimension($cropW, $cropH)
                )
                ->resize(new Dimension(720, 1280))
                ->synchronize();

            $format = new X264();
            $format->setKiloBitrate(2000)
                   ->setAudioCodec('aac')
                   ->setAudioKiloBitrate(128);

            $video->save($format, $outputPath);

            return file_exists($outputPath) && filesize($outputPath) > 0;
        } catch (\Exception $e) {
            error_log('VideoProcessor::cropToReel failed: ' . $e->getMessage());
            return false;
        }
    }

    // -----------------------------------------------------------------------
    // Internal helpers
    // -----------------------------------------------------------------------

    /**
     * Build the config array passed to FFMpeg::create().
     */
    private function buildConfig(): array
    {
        $config = [
            'timeout'        => 3600,   // 1 hour max per operation
            'ffmpeg.threads' => 4,
        ];

        $ffmpegPath  = $this->findBinary('ffmpeg');
        $ffprobePath = $this->findBinary('ffprobe');

        if ($ffmpegPath) {
            $config['ffmpeg.binaries'] = $ffmpegPath;
        }
        if ($ffprobePath) {
            $config['ffprobe.binaries'] = $ffprobePath;
        }

        // Temporary directory for intermediate files
        $tmp = sys_get_temp_dir() . '/ffmpeg-tmp';
        if (!is_dir($tmp)) {
            @mkdir($tmp, 0755, true);
        }
        $config['temporary_directory'] = $tmp;

        return $config;
    }

    /**
     * Locate FFmpeg/FFprobe binary on the system.
     */
    private function findBinary(string $name): string
    {
        // Common installation paths (checked first for speed)
        $paths = [
            // cPanel / Linux server
            '/usr/bin/' . $name,
            '/usr/local/bin/' . $name,
            '/opt/ffmpeg/bin/' . $name,
            // Windows dev
            'C:/ffmpeg/bin/' . $name . '.exe',
            'C:/Program Files/ffmpeg/bin/' . $name . '.exe',
        ];

        foreach ($paths as $p) {
            if (file_exists($p)) {
                return $p;
            }
        }

        // Fallback: search PATH
        $which = trim(shell_exec("where $name 2>nul") ?? '');
        if ($which) {
            $lines = explode("\n", $which);
            if (file_exists(trim($lines[0]))) {
                return trim($lines[0]);
            }
        }

        return '';
    }

    /**
     * Expose underlying FFMpeg instance for advanced/custom operations
     * that go beyond this service's convenience methods.
     */
    public function getFFMpeg(): ?FFMpeg
    {
        return $this->ffmpeg;
    }

    public function getFFProbe(): ?FFProbe
    {
        return $this->ffprobe;
    }
}
