<?php

namespace App\Jobs;

use App\Models\Video;
use Carbon\Carbon;
use FFMpeg\Coordinate\Dimension;
use FFMpeg\Format\Video\X264;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;

class ConvertVideoForStreaming implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $video;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Video $video)
    {
        $this->video = $video;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // create a video format...
        $low = (new X264('aac'))->setKiloBitrate(500);
        $high = (new X264('aac'))->setKiloBitrate(1000);

        $converted_name = $this->getCleanFileName($this->video->path);

        // open the uploaded video from the right disk...
        FFMpeg::fromDisk($this->video->disk)
            ->open($this->video->path)
            ->exportForHLS()
            ->addFormat($low, function ($filters){
                $filters->resize(640,480);
            })
            ->addFormat($high, function ($filters){
                $filters->resize(1280,720);
            })
            ->toDisk('public')
            ->save($converted_name);




        // update the database so we know the convertion is done!
        $this->video->update([
            'converted_for_streaming_at' => Carbon::now(),
            'processed' => true,
            'stream_path' => $converted_name
        ]);
    }
    private function getCleanFileName($filename){
        return preg_replace('/\\.[^.\\s]{3,4}$/', '', $filename) . '.mp4';
    }
}
