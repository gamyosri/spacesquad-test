<?php

namespace App\Http\Controllers;

use App\Models\UnspalshPhotos;
use App\Models\UnspalshUsers;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Unsplash\HttpClient;
use Unsplash\Search;
use Unsplash\Exception;

class MiningController extends Controller
{

    public function search(Request $request)
    {
           $this->initClient();

        $search = $request['search-term'];
        $page = 1;
        $per_page = 30;

        try {
            $photos = Search::photos($search, $page, $per_page);
            $users = Search::users($search, $page, $per_page);
        }catch (Exception $e){
            ApiKeyRotator::initClient();
            $this->search($request);
        }

            $this->saveUsers($users->getResults());
            $this->savePhotos($photos->getResults());

        return view('spacesquad.search')->with('users', $users)->with('photos', $photos);

    }

    public function ranking()
    {
        $mostLikedPhotos = UnspalshPhotos::all()->sortByDesc('likes')->take(10);
        $mostLikedUsers = UnspalshUsers::all()->sortByDesc('total_likes')->take(10);

        return view('spacesquad.ranking')->with('mostLikedUsers', $mostLikedUsers)->with('mostLikedPhotos', $mostLikedPhotos);
    }

    private function saveUsers($users)
    {
        foreach ($users as $user) {
            if (!UnspalshUsers::find($user['id'])) {
                UnspalshUsers::create($user);
            }
        }
    }

    private function savePhotos($photos)
    {
        foreach ($photos as $photo) {
            if (!UnspalshPhotos::find($photo['id'])) {
                UnspalshPhotos::create($photo);
            }
        }
    }

    public function initClient() {
        HttpClient::init([
            'applicationId' => \Config::get('ApiKeys.'.rand(1,5)),
            'utmSource' => 'SPACESQUAD'
        ]);
    }

}
