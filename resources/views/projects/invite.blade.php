<div class="card flex flex-col mt-3">
    <h3 class="font-normal text-xl mb-3 py-4 -ml-5 border-l-4 border-blue-light pl-4">
        Invite a User
    </h3>

    <form></form>
    <form method="POST" action="{{ $project->path() . '/invitations' }}">
        @csrf

        <div class="mb-3">
            <input type="email" name="email" class="border border-grey rounded w-full py-2 px-3" placeholder="Email address">
        </div>

        <button type="submit" class="button">Invite</button>
    </form>
    @include('errors', ['bag' => 'invitations'])
</div>
