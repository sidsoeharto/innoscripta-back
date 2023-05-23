<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{

    public function getOne (Request $request) {
        $user = $request->user();

        return $user->load('preferences');
    }

    public function updateName (Request $request) {
        $user = auth('sanctum')->user();
        $user->name = $request->name;
        $user->save();
        return response("", 200);
    }

    public function getPreferences (Request $request) {
        $user = auth('sanctum')->user();
        $preferences = $user->preferences;
        if ($preferences) {
            foreach($preferences as $preference) {
                $preferencesObject[$preference->name] = $preference->value;
            }
            return response()->json($preferencesObject);
        }
        return response()->json(['category' => 'all', 'source' => 'all']);
    }

    public function updatePreferences (Request $request) {
        $user = auth('sanctum')->user();
        $user->setPreferences(['category' => $request->category, 'source' => $request->source]);
        return response()->json($user->preferences);
    }
}
