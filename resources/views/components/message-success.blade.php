@if (session()->has('message'))
    <div x-data="{ showMessage: true }" x-show="showMessage" x-init="setTimeout(() => showMessage = false, 6000)">



        <!-- info message -->
        <div class="bg-green-600 shadow-lg mx-auto w-96 max-w-full text-sm pointer-events-auto bg-clip-padding rounded-lg block mb-5"
            id="static-example" role="alert" aria-live="assertive" aria-atomic="true" data-mdb-autohide="false">
            <div
                class="bg-green-600 flex justify-between items-center py-2 px-3 bg-clip-padding border-b border-green-500 rounded-t-lg">
                <p class="font-bold text-white flex items-center">
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="info-circle"
                        class="w-4 h-4 mr-2 fill-current" role="img" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 512 512">
                        <path fill="currentColor"
                            d="M256 8C119.043 8 8 119.083 8 256c0 136.997 111.043 248 248 248s248-111.003 248-248C504 119.083 392.957 8 256 8zm0 110c23.196 0 42 18.804 42 42s-18.804 42-42 42-42-18.804-42-42 18.804-42 42-42zm56 254c0 6.627-5.373 12-12 12h-88c-6.627 0-12-5.373-12-12v-24c0-6.627 5.373-12 12-12h12v-64h-12c-6.627 0-12-5.373-12-12v-24c0-6.627 5.373-12 12-12h64c6.627 0 12 5.373 12 12v100h12c6.627 0 12 5.373 12 12v24z">
                        </path>
                    </svg>
                    Notification Alert
                </p>
                <div class="flex items-center">
                    <p class="text-white opacity-90 text-xs">

                        <!-- DATETIME NOW -->
                        @php
                            $date = new \DateTime();
                            $date->setTimezone(new \DateTimeZone('America/Argentina/Buenos_Aires')); //GMT
                            $datetime = $date->format('H:i A');
                            
                        @endphp
                        {{ $datetime }}

                        <!-- END DATETIME NOW -->
                    </p>
                    <button type="button"
                        class="btn-close btn-close-white box-content w-4 h-4 ml-2 text-white border-none rounded-none opacity-50 focus:shadow-none focus:outline-none focus:opacity-100 hover:text-white hover:opacity-75 hover:no-underline"
                        data-mdb-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
            <div class="p-3 bg-green-600 rounded-b-lg break-words text-white">
                {{ session('message') }}
            </div>
        </div>

    </div>
@endif
<!-- END INFO MESSAGE -->

@if (session()->has('success'))
    <script>
        Swal.fire({
            title: 'Genial!!',
            text: '{{ session('success') }}',
            icon: 'success',
            confirmButtonText: 'OK'
        })
    </script>
@endif

@if ($errors->any())
    <script>
        let errors = @json($errors->all());

        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            html: '<div class="text-red-500">' + errors.join('<br>') + '</div>',
            footer: '<a href="">Web Application</a>'
        });
    </script>
@endif

@if (session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            html: '<div class="text-red-500">{{ session('error') }}</div>',
            footer: '<a href="">Web Application</a>'
        });
    </script>
@endif
