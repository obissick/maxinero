<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMaxScaleUserRequest;
use App\Services\MaxScaleClient;
use GuzzleHttp\Exception\ConnectException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

class UserController extends Controller
{
    public function __construct(private MaxScaleClient $maxscale)
    {
        $this->middleware('auth');
    }

    public function index()
    {
        try {
            $users = json_decode($this->maxscale->get('users'), true);

            return view('users.users', compact('users'));
        } catch (ConnectException) {
            return redirect('settings')->with('error', 'Issue connecting to MaxScale backend.');
        } catch (\Exception $e) {
            return redirect('settings')->with('error', $e->getMessage());
        }
    }

    public function store(StoreMaxScaleUserRequest $request)
    {
        $data = [
            'data' => [
                'id'   => $request->input('user_id'),
                'type' => 'inet',
                'attributes' => [
                    'password' => $request->input('password'),
                    'account'  => $request->input('account'),
                ],
            ],
        ];

        $this->maxscale->post('users/inet', $data);

        return $this->maxscale->get('users/inet/' . $request->input('user_id'));
    }

    public function destroy(Request $request, string $id)
    {
        if ($request->input('type') === 'delete-user') {
            $this->maxscale->delete('users/inet/' . $id);
            Session::flash('success', 'User deleted.');
        } elseif ($request->input('type') === 'disable-user') {
            $this->maxscale->delete('users/unix/' . $id);
            Session::flash('success', 'User disabled.');
        }

        return View::make('flash-message');
    }
}