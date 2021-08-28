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
    private $keyIndex =1;

    public function search(Request $request)
    {
        HttpClient::init([
            'applicationId' => \Config::get('ApiKeys.'. $this->keyIndex),
            'utmSource' => 'SPACESQUAD',
        ]);
        $search = $request['search-term'];
        $page = 1;
        $per_page = 30;

        try {
            $users = Search::users($search, $page, $per_page);
            $this->saveUsers($users->getResults());
        }catch (Exception $e){
            $this->setKeyIndex();
            $this->search($request);
        }

        try {
            $photos = Search::photos($search, $page, $per_page);
            $this->savePhotos($photos->getResults());
        }catch (Exception $e){
            $this->setKeyIndex();
            $this->search($request);
        }

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
                $user['recognition'] = 'manual';
                UnspalshUsers::create($user);
            }
        }
    }

    private function savePhotos($photos)
    {
        foreach ($photos as $photo) {
            if (!UnspalshPhotos::find($photo['id'])) {
                $photo['recognition'] = 'manual';
                UnspalshPhotos::create($photo);
            }
        }
    }

    public function setNextKeyIndex(){
        $this->keyIndex++ > count(\Config::get('ApiKeys')) ? $this->keyIndex = 1 : $this->keyIndex++;
    }
}
