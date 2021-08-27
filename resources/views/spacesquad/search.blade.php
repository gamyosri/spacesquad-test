<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">

    <!-- Scripts -->
    <script src="{{ mix('js/app.js') }}" defer></script>
</head>
<body>
@include('navigation-menu')
<div class=" p-6 sm:px-20 bg-white border-b border-gray-200 ">
    <form name="search-form" action="{{url('search')}} ">

    <label>Search</label>
<input class="inline-flex items-center px-4 py-2 bg-gray-400 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-300 active:bg-gray-500 focus:outline-none focus:border-gray-500 focus:ring focus:ring-gray-100 disabled:opacity-25 transition" type="text" name="search-term" id="srearch-field"  required>
        <button class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition" type="submit" >Search</button>

        @if(isset($users))
        <label> Noch {{ $users->getHeaders()['X-Ratelimit-Remaining'][0] }} von {{ $users->getHeaders()['X-Ratelimit-Limit'][0] }} API-calls möglich</label>
        @endif
    </form>
</div>



    @if(isset($users))
        <h2>Users search results</h2>
            <div class="flex flex-col flex-wrap justify-center md:flex-row mt-10">
                @foreach($users->getResults() as $user)
                <div class=" rounded overflow-hidden shadow-lg m-4 bg-gray-100 md:w-1/6 lg:w-1/6" >
                    <img class="w-full" src="{{ $user['profile_image']['large'] }}" alt="Profile-pic">
                    <div class="px-6 py-4  border-b-2">
                        <a class="font-bold text-xl mb-2" href="{{ $user['links']['html'] }}">{{ $user['username'] }}</a>
                        <p class="text-grey-darker text-base">
                            test
                        </p>
                    </div>
                    <div class="px-6 py-4">
                        <span class="inline-block bg-grey-lighter rounded-full px-3 py-1 text-sm font-semibold text-grey-darker mr-2">{{ $user['total_photos'] }} photos</span>
                        <span class="inline-block bg-grey-lighter rounded-full px-3 py-1 text-sm font-semibold text-grey-darker mr-2">{{ $user['total_likes'] }} likes</span>
                    </div>
                </div>
                @endforeach
            </div>

    @endif

<br>

    @if(isset($photos))
        <h2>Users search results</h2>
        <div class="flex flex-col flex-wrap justify-center md:flex-row mt-10">
            @foreach($photos->getResults() as $photo)
                <div class=" rounded overflow-hidden shadow-lg m-4 bg-gray-100 md:w-1/6 lg:w-1/6" >
                    <img class="w-full" src="{{ $photo['urls']['regular'] }}" alt="Profile-pic">
                    <div class="px-6 py-4  border-b-2">
                        <a class="font-bold text-xl mb-2" href="{{ $photo['links']['html'] }}">{{ $photo['alt_description']}}</a>
                        <br>
                        <a class="text-grey-darker text-base" href="{{ $photo['user']['links']['html'] }}">
                            {{ $photo['user']['username'] }}
                        </a>
                    </div>
                    <div class="px-6 py-4">
                        <span class="inline-block bg-grey-lighter rounded-full px-3 py-1 text-sm font-semibold text-grey-darker mr-2">{{ $photo['likes'] }} likes</span>
                        @if(isset($photo['views']))
                        <span class="inline-block bg-grey-lighter rounded-full px-3 py-1 text-sm font-semibold text-grey-darker mr-2">{{ $photo['views'] }} views</span>
                        @endif
                        @if(isset($photo['downloads']))
                        <span class="inline-block bg-grey-lighter rounded-full px-3 py-1 text-sm font-semibold text-grey-darker mr-2">{{ $photo['downloads'] }} downloads</span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif

</body>
</html>


