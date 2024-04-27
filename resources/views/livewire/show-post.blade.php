<!-- Published POST -->
<div class="container my-24 px-6 mx-auto">

    <!-- Section: Design Block -->
    <section class="mb-32 text-gray-800">
        @if (strpos($post->post_image, 'videos') !== false)
            {{-- Si es un video, mostrar el reproductor de video --}}
            <video width="240" height="160" controls>
                <source src="{{ Storage::url($post->post_image) }}" type="video/mp4">
                Tu navegador no soporta el elemento de video.
            </video>
        @else
            {{-- Si es una imagen, mostrar la imagen --}}
            <br><br>
            <img src="{{ Storage::url($post->post_image) }}"
                class="shadow-lg rounded-lg mx-auto max-w-full h-auto max-h-64 object-cover mb-5"
                alt="Imagen del artículo">
        @endif

        <div class="flex items-center mb-6">
            <img src="{{ asset('img/favicon/favicon-32x32.png') }}" class="rounded-full mr-2 h-6" alt=""
                loading="lazy" />
            <div>
                {{ __('messages.published') }} <span class="font-medium capitalize">
                    {{ \Carbon\Carbon::createFromFormat('l, F jS Y, H:i A', $post->post_date)->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}


                </span>

            </div>
        </div>

        <h1 class="font-bold text-3xl mb-6 "> {{ $post->post_title }}
        </h1>

        <p>
            {!! $post->post_content !!}

        </p>
    </section>
    <!-- Section: Design Block -->
    <script type="text/javascript"
        src="https://platform-api.sharethis.com/js/sharethis.js#property=646e837f413e9c001905a213&product=inline-share-buttons&source=platform"
        async="async"></script>
    <div class="flex flex-col items-center">
        <h1 class="text-green-700 text-3xl font-semibold my-10 text-center">{{ __('messages.share_article') }}</h1>

        <div class="sharethis-inline-share-buttons my-5 pb-10"></div>

    </div>

</div>


<!-- END Published POST -->
