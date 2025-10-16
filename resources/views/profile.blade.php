<x-layout>
  <x-navbar />

  <main>
    <header>
      <h1 class="font-bold text-3xl">{{ $user->name }}</h1>
      <p class="text-lg">{{ $user->email }}</p>
    </header>

    <form class="mt-10 flex" hx-post="/profile-update">
      <div class="flex flex-col gap-4">
        <div>
          <label class="block font-semibold" id="name">Name:</label>
          <input class="border bg-white p-1" name="name" value="{{ $user->name }}" />
        </div>

        <div>
          <label class="block font-semibold" id="email">Email:</label>
          <input class="border bg-white p-1" name="email" value="{{ $user->email }}" />
        </div>

        <div>
          <label class="block font-semibold" id="father">Father's name:</label>
          <input class="border bg-white p-1" name="father" value="{{ $user->father_name }}" />
        </div>

        @if ($user->mobile_no)
        <div>
          <label class="block font-semibold" id="mobile_no">Mobile No:</label>
          <input class="border bg-white p-1" name="mobile_no" value="{{ $user->mobile_no }}" />
        </div>
        @endif

        <div>
          <label class="block font-semibold" id="address">Address:</label>
          <input class="border bg-white p-1" name="address" value="{{ $user->address }}" />
        </div>
      </div>

      <div>
        <label for="profile">Profile Picture</label>
        <img src="/storage/{{ $user->picture }}" />
      </div>
    </form>
  </main>

</x-layout>
