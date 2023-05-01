<?php

namespace App\Http\Controllers;

use Carbon\Carbon;

use App\Models\User;
use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    private $protocol;
    private $domain;
    private $path;
    private $baseUrl;
    private $queryPage;
    private $queryCount;
    
    public function __construct (
        
    ) {
        $this->protocol = env('LINK_PROTOCOL');
        $this->domain = env('LINK_DOMAIN');
        $this->path = env('LINK_PATH');
        $this->baseUrl = $this->protocol . $this->domain . $this->path;
        $this->queryPage = env('LINK_QUERY_PAGE');
        $this->queryCount = env('LINK_QUERY_COUNT');
    }

    public function test2 (Request $request) {
        // check:
        // $user = User::latest()->first();
        // $position = $user->position;
        // return $user->position->name;

        $users = User::select(
            'id',
            'name',
            'email',
            'phone',
            'position_id',
            'photo',
        )->get();

        foreach ($users as $user) {
            $updUser = User::where('id', $user->id)->first();
            $updUserPosition = $updUser->position->name;
            $user['position'] = $updUserPosition;

            $registered = $user->created_at;
            $dateTime = Carbon::parse($registered);
            $user['registration_timestamp'] = $dateTime->timestamp;
        }

        return $users;
    }
    public function test (Request $request): JsonResponse {

        // $users = User::all();

        $users = User::skip(0)->take(6)->select(
            'id',
            'name',
            'email',
            // 'position',
            'position_id',
            // eto k Dronu:
            'created_at',  // should be displayed as 'registration_timestamp'
            'photo',
        )->get(); 

        foreach ($users as $user) {
            // $key = $user['position'];
            // $value
            $updUser = User::where('id', $user->id)->first();
            $updUserPosition = $updUser->position->name;
            // $position = Position::where('id', $user->position_id)->first();
            $position = $updUser->position;
            // $user['position'] = $position->name;
            $user['position'] = $updUserPosition;
            // $user->position();
            // User::find($user->id)->position;
            // $key = $value;
            //  = $user->position->name;
        }

        return new JsonResponse([
            'success' => true,
            'total_users' => User::count(),
            'users' => $users,
            // "page" => 1,
            // "total_pages" => 10,
            // "count" => 5,
            // "links" => {
            //     "next_url": "https://frontend-test-assignment-api.abz.agency/api/v1/users?page=2&count=5",
            //     "prev_url": null
            // },
        ]);
    }



    // methods below works fine; 
    // should be refactored later;
    private function links ($page, $count, $totalPages) {
        if ($page <= 1) {
            $links = [
                'next_url' => $this->baseUrl . $this->queryPage . ($page + 1) . $this->queryCount . $count,
                'prev_url' => null,
            ];
            return $links;
        } elseif ($page == $totalPages) {
            return [
                'next_url' => null,
                'prev_url' => $this->baseUrl . $this->queryPage . ($page - 1) . $this->queryCount . $count,            
            ];
        } else {
            return [
                'next_url' => $this->baseUrl . $this->queryPage . ($page + 1) . $this->queryCount . $count,
                'prev_url' => $this->baseUrl . $this->queryPage . ($page - 1) . $this->queryCount . $count,
            ];
        }
    }

    public function usersGet (Request $request) {

        // I have to add 'request validation', I guess

        $page = $request->query('page');
        $count = $request->query('count');
        $offset = ($page - 1) * $count;

        $totalUsers = User::count();
        $totalPages = ceil($totalUsers / $count);

        if ($page > $totalPages) {
            return new JsonResponse([
                "success" => false,
                "message" => "Page not found",
            ], 404);
        }

        $users = User::skip($offset)->take($count)->select(
            'id',
            'name',
            'email',
            'phone',
            'position_id',
            'photo',
        )->get(); 
        foreach ($users as $user) {
            $updUser = User::where('id', $user->id)->first();
            $updUserPosition = $updUser->position->name;
            $user['position'] = $updUserPosition;

            $registered = $user->created_at;
            $dateTime = Carbon::parse($registered);
            $user['registration_timestamp'] = $dateTime->timestamp;
        }


        $navLinks = $this->links($page, $count, $totalPages);

        return new JsonResponse([
            'success' => true,
            'page' => $page,
            'total_pages' => $totalPages,
            'total_users' => $totalUsers,
            'count' => $count,
            'links' => $navLinks,
            'users' => $users,
        ]);
    }

    public function positions (Request $request): JsonResponse {
        $positions = Position::select('id', 'name')->orderBy('id')->get();
        return new JsonResponse([
            'success' => true,
            'positions' => $positions,
        ]);
    }
}
