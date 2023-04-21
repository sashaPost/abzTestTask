<?php

namespace App\Http\Controllers;

use App\Models\User;

use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    //
    public function __construct (Request $request) {

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
            $user['position'] = $position->name;
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

    // private function prevLink () {
    //     return null;
    // }

    private function links ($page, $count, $totalPages) {
        // - break url to components;
        // - get as 'env' variables;
        if ($page <= 1) {
            return [
                'next_url' => 'http://127.0.0.1:8000/api/v1/test?page=' . ($page + 1) . '&count=' . $count,
                'prev_url' => null,
            ];
        } elseif ($page == $totalPages) {
            return [
                'next_url' => null,
                'prev_url' => 'http://127.0.0.1:8000/api/v1/test?page=' . ($page - 1) . '&count=' . $count,            
            ];
        } else {
            return [
                'next_url' => 'http://127.0.0.1:8000/api/v1/test?page=' . ($page + 1) . '&count=' . $count,
                'prev_url' => 'http://127.0.0.1:8000/api/v1/test?page=' . ($page - 1) . '&count=' . $count,
            ];
        }
    }

    public function usersGet (Request $request) {

        $page = $request->query('page');
        $count = $request->query('count');
        $offset = ($page - 1) * $count;

        $users = User::skip($offset)->take($count)->select(
            'id',
            'name',
            'email',
            // 'position',
            'position_id',
            // eto k Dronu:
            'created_at',  // should be displayed as 'registration_timestamp'
            'photo',
        )->get(); 
        $totalUsers = User::count();

        $totalPages = ceil($totalUsers / $count);

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
