<?php

namespace App\Jobs;

use App\Models\UnspalshPhotos;
use App\Models\UnspalshUsers;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

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
        $this->MineUsers();
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
            $response = Http::acceptJson()
                ->get($user->links['photos'], ['client_id' => \Config::get('ApiKeys.' . rand(1, 5))]);

            $photos = $response->collect();

            $photos->each(function ($photo) {
                if (!UnspalshPhotos::find($photo['id']))
                    UnspalshPhotos::create($photo);
            });
        });
    }
}
