<nav class="border-b border-border px-6">
    <div class="max-w-7xl mx-auto h-16 flex items-center justify-between">
        <div>
            <a href="/">Idea</a>
        </div>

        <div class="flex gap-x-5 items-center">
            @auth
                <a href="/profile">Edit Profile</a>

                <form action="/logout" method="POST">
                    @csrf
                    <button>Log Out</button>
                </form>
            @endauth

            @guest
                <a href="/register" class="btn">Register</a>
                <a href="/login">Sign in</a>
            @endguest
        </div>
    </div>
</nav>
