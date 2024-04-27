<!-- LATEST POSTS START -->
<div class="bg-white py-6 sm:py-8 lg:py-12 my-10 mt-10">
    <br><br>
    <div class="mx-auto max-w-screen-2xl px-4 md:px-8">
        <!-- text - start -->
        <div class="mb-10 md:mb-16 ">
            <h2 class="mb-4 text-center text-2xl font-bold text-gray-800 md:mb-6 lg:text-3xl">Blog</h2>

            <p class="mx-auto max-w-screen-md text-center text-gray-500 md:text-lg">
                {{ __('messages.latest_news_paragraph') }}</p>
        </div>
        <!-- text - end -->

        <div class="grid gap-4 sm:grid-cols-2 md:gap-6 lg:grid-cols-3 xl:grid-cols-4 xl:gap-8">
            <!-- article - start -->
            @foreach ($latestPosts as $post)
                <a href="{{ route('posts.show', ['postId' => $post->post_title_slug]) }}"
                    class="group relative flex h-48 flex-col overflow-hidden rounded-lg bg-gray-100 shadow-lg md:h-64 xl:h-96">
                    <img src="{{ Storage::url($post->post_image) }}" loading="lazy" alt="Photo Post"
                        class="absolute inset-0 h-full w-full object-cover object-center transition duration-200 group-hover:scale-110" />

                    <div
                        class="pointer-events-none absolute inset-0 bg-gradient-to-t from-gray-800 to-transparent md:via-transparent">
                    </div>

                    <div class="relative mt-auto p-4">
                        <span class="block text-sm text-gray-200 capitalize">
                            {{ \Carbon\Carbon::createFromFormat('l, F jS Y, H:i A', $post->post_date)->locale('es')->translatedFormat('F j, Y') }}


                        </span>
                        <h2 class="mb-2 text-xl font-semibold text-white transition duration-100">
                            {{ ucfirst(Str::words($post->post_title, 6, '...')) }}</h2>


                        <span class="font-semibold text-indigo-300">Leer Más</span>

                    </div>
                </a>
                <!-- article - end -->
            @endforeach

        </div>
        <div class="my-10">{{ $latestPosts->links() }}</div>
    </div>

</div>
<br><br><br>
<!-- LATEST POSTS END -->
