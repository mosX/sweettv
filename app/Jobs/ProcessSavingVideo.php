<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Events\PusherBroadcast;

use App\Models\Video;

class ProcessSavingVideo implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $filename;
    protected $fullpath;
    /**
     * Create a new job instance.
     */
    public function __construct($filename, $fullpath)   
    {
        $this->filename = $filename;
        $this->fullpath = $fullpath;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        exec('ffmpeg -y -i '.$this->fullpath.$this->filename.' -vcodec copy -acodec copy -f mp4 '.$this->fullpath.$this->filename);
        exec('ffmpeg -y -i '.$this->fullpath.$this->filename.' -vf "scale=1920:1080" '.$this->fullpath.'1080p_'.$this->filename);   
        exec('ffmpeg -y -i '.$this->fullpath.$this->filename.' -vf "scale=1280:720" '.$this->fullpath.'720p_'.$this->filename);   

        $video = new Video();
        $video->filename =$this->filename;
        $video->save();

        broadcast(new PusherBroadcast('Video Was Successfully Processed'));
    }
}