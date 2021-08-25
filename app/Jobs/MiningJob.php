<?php

namespace App\Jobs;

use App\Models\UnspalshPhotos;
use App\Models\UnspalshUsers;
use App\Models\User;
use GuzzleHttp\Client;
use Http;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class MiningJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //$this->MineUsers();
        $this->MinePhotos();
    }

    public function MineUsers()
    {
        $existingPhotos = UnspalshPhotos::all();
        $existingPhotos->each(function ($photo) {
            if (!UnspalshUsers::find($photo->user['id']))
                UnspalshUsers::create($photo->user);
        });
    }

    public function MinePhotos()
    {
        $existingUsers = UnspalshUsers::all();
        $existingUsers->each(function ($user) {
            $photos = Http::acceptJson()
                ->get('https://api.unsplash.com/photos/JMHVHptLC4g',
                    ['client_id' => env("UNSPLASH_API_KEY")])->body();
            $photos = json_decode($photos);
            foreach ($photos as $photo) {
                if (!UnspalshPhotos::find($photo['id']))
                    UnspalshPhotos::create($photo);
            };
        });
    }

}
