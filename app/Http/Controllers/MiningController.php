<?php

namespace App\Http\Controllers;

use App\Models\UnspalshPhotos;
use App\Models\UnspalshUsers;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Unsplash;

class MiningController extends Controller
{
    public function search(Request $request){

        \Unsplash\HttpClient::init([
            'applicationId'	=> env("UNSPLASH_API_KEY"),
            'secret'		=> env("UNSPLASH_SECRET_KEY"),
            'callbackUrl'	=> 'http://unsplash-api.com/',
            'utmSource' => 'SPACESQUAD'
        ]);


        $search = $request['search-term'];
        $page = 2;
        $per_page = 1000;

        $photos = Unsplash\Search::photos($search, $page, $per_page);
        $users = Unsplash\Search::users($search, $page, $per_page);
            $this->saveUsers($users->getResults());
            $this->savePhotos($photos->getResults());
            return view('spacesquad.search')->with('users', $users)->with('photos',$photos);

    }

    public function ranking(){

        $mostLikedPhotos = UnspalshPhotos::all()->sortByDesc('likes')->take(10);
        $mostLikedUsers = UnspalshUsers::all()->sortByDesc('total_likes')->take(10);

        return view('spacesquad.ranking')->with('mostLikedUsers',$mostLikedUsers)->with('mostLikedPhotos',$mostLikedPhotos);
    }

    private function saveUsers($users){
        foreach($users as $user){
            if(!UnspalshUsers::find($user['id'])){
            UnspalshUsers::create($user);
                var_dump('user saved');
            }
        }
    }

    private function savePhotos($photos){
        foreach($photos as $photo){
            //dd($photo);
            if (!UnspalshPhotos::find($photo['id'])){
                UnspalshPhotos::create($photo);
                var_dump('photo saved');
            }
        }
    }
}
