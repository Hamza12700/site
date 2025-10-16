<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;

// Sucks that can't simply use 'redirect' because of HTMX.
// Htmx doesn't reload/redirect if status code isn't 2xx.
function hx_redirect(string $path = "/") {
  return response('redirect')->header("HX-Redirect", $path);
}

Route::get("/", function () {
  return view("home");
});

Route::get("/profile", function() {
  return view("profile", ["user" => Auth::user()]);
})->middleware("auth");

Route::get("/logout", function(Request $request) {
  Auth::logout();
  $request->session()->invalidate();
  $request->session()->regenerateToken();
  return redirect('/');
})->middleware("auth");

Route::get("/login", function () {
  if (Auth::check()) { return redirect("/profile"); }
  return view("login");
});

Route::post("/login", function (Request $request) {
  $creds = $request->validate([
    "email" => "required|email",
    "password" => "required|string",
  ]);

  if (Auth::attempt($creds)) {
    $request->session()->regenerate();
    return hx_redirect("/profile");
  }

  return "The provided credentials do not match our records";
});

Route::get("/register", function () {
  $courses = DB::select("select * from courses");
  return view("register", ["courses" => $courses]);
});

Route::post("/register", function (Request $request) {
  if ($request->input("course") === null) {
    return response("Missing courses", 400);
  }

  $info = $request->validate([
    "name" => "required|string",
    "picture" => "required|image",
    "email" => "required|email",
    "password" => "required|string",
    "father_name" => "required|string",
    "address" => "required|string",
    "mobile_no" => "string",
    "gender" => "required|string",
  ]);

  $info['picture'] = $request->file("picture")->storePublicly("uploads", "public") or function() {
    return response("File operation failed!", 500);
  };

  $user = User::create($info);
  Auth::login($user);
  return hx_redirect("/profile");
});
