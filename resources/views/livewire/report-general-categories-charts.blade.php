 <div x-show="activeTab === '2'">
     <!-- REPORT GENERAL CATEGORIES CHART  -->
     <div id="between-dates-chart-table">
         <div class="flex flex-col space-y-2 md:space-y-0 md:flex-row md:items-center my-10">
             <div class="w-full px-3 md:w-1/3 mb-3 sm:mb-0 ">
                 <div wire:ignore>
                     <select wire:model="selectedUser2" wire:change="updateCategoriesData" id="selectUserChart2"
                         style="width: 100%"
                         class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                         <option value="">{{ __('messages.table_columns_categories.select_a_user') }}</option>
                         @if (auth()->user()->hasRole('Admin'))
                             @foreach ($users as $user)
                                 <option value="{{ $user->id }}">{{ $user->name }}
                                 </option>
                             @endforeach
                         @else
                             <option value="{{ auth()->user()->id }}">
                                 {{ auth()->user()->name }}
                             </option>
                         @endif

                     </select>
                 </div>
             </div>

             <div class="w-full px-3 md:w-1/3 mb-3 sm:mb-0 ">
                 <div wire:ignore>
                     <select wire:model="selectedCategoryId" id="selectCategoryChart" wire:change="updateCategoriesData"
                         style="width: 100%"
                         class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                         <option value="">{{ __('messages.select_a_category') }}</option>
                         @foreach ($categoriesRender as $formattedCategory)
                             <optgroup label="{{ $formattedCategory['mainCategoryTitle'] }}">
                                 @foreach ($formattedCategory['categories'] as $category)
                                     <option value="{{ $category['id'] }}">
                                         {{ $category['category_name'] }}
                                     </option>
                                 @endforeach
                             </optgroup>
                         @endforeach
                     </select>
                 </div>
             </div>

             <div class="w-full px-3 md:w-1/3 mb-3 sm:mb-0 ">
                 <div wire:ignore>
                     <select wire:model="selectedYear2" id="selectYearChart2" wire:change="updateCategoriesData"
                         style="width: 100%"
                         class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                         <option value="">{{ __('messages.select_a_year') }}</option>
                         @foreach ($years as $year)
                             <option value="{{ $year }}">{{ $year }}</option>
                         @endforeach
                     </select>
                 </div>
             </div>
             <!-- VARIABLES for JSPDF -->
             <span id="userInfo2" class="text-xs font-bold text-center text-blue-500 capitalize dark:text-gray-400"
                 data-username="{{ $userNameSelected2 ? $userNameSelected2->name : '' }}"
                 data-year="{{ $selectedYear2 ? $selectedYear2 : '' }}" data-report="{{ $report_date }}"
                 data-category-name="{{ isset($categoryNameSelected->category_name) ? $categoryNameSelected->category_name : '' }}">

             </span>
             <!-- END VARIABLES for JSPDF -->
         </div>

         @if ($showChart2)
             <div class="my-10 flex justify-end space-x-2">

                 <x-button class="bg-green-600 hover:bg-green-700 shadow-lg hover:shadow-green-500/50"
                     onclick="downloadImage2()">
                     <span class="font-semibold"><i class="fa-regular fa-image px-1"></i></span>
                     {{ __('messages.download') }}
                 </x-button>
                 <x-button class="bg-purple-600 hover:bg-purple-700 shadow-lg hover:shadow-purple-500/50"
                     onclick="generatePDF2()">
                     <span class="font-semibold"><i class="fa-regular fa-file-pdf px-1"></i></span>
                     {{ __('messages.convert_to_pdf') }}
                 </x-button>
                 <x-button class="bg-red-600 hover:bg-red-700 shadow-lg hover:shadow-red-500/50"
                     wire:click="resetFields2" wire:loading.attr="disabled">
                     <span class="font-semibold"><i class="fa-solid fa-power-off px-1"></i></span>
                     {{ __('messages.reset_fields') }}
                 </x-button>
             </div>
             <div class="flex flex-col space-y-2 md:space-y-0 md:flex-row md:items-center my-10">


                 <div class="w-full px-3 md:w-1/3 mb-3 sm:mb-0 ">
                     <select wire:model="SelectMainCurrencyTypeRender" wire:change="updateCategoriesData"
                         class="w-full text-sm dark:text-gray-800 dark:border-gray-600 dark:bg-white form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">

                         <option value="USD">USD</option>
                         @foreach ($mainCurrencyTypeRender as $currencyType)
                             @php
                                 // Si es 'Blue-ARS', cambiarlo a 'ARS'
                                 $displayCurrency = $currencyType == 'Blue-ARS' ? 'ARS' : $currencyType;
                             @endphp
                             <option value="{{ $currencyType }}">{{ $displayCurrency }}</option>
                         @endforeach

                     </select>

                 </div>
             </div>

             <div id="chart-container2" class="my-5" wire:key="{{ $chartId }}">
                 <div>
                     <div class="grid gap-6 mb-8 md:grid-cols-1" id="content2">
                         <div
                             class="min-w-0 p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800 flex flex-col md:flex-row items-center">
                             <!-- Contenedor del canvas, ajustado para ser responsive y con margen -->
                             <div class="p-3 md:w-2/5 lg:w-4/6 xl:w-4/6 -mt-4 mr-4">
                                 <!-- Agregado "mr-4" para añadir margen a la derecha -->
                                 <canvas id="{{ $chartId }}" height="200"></canvas>
                             </div>

                             <!-- Contenedor de la información, ajustado para ser responsive -->
                             <div
                                 class="rounded-lg w-full md:w-3/5 px-6 py-6 text-xs font-bold tracking-wide text-center capitalize border-b bg-gray-100 dark:border-gray-700 dark:text-gray-400 dark:bg-gray-700">
                                 @php
                                     $currencyType =
                                         $SelectMainCurrencyTypeRender === 'Blue-ARS'
                                             ? 'ARS'
                                             : $SelectMainCurrencyTypeRender;
                                 @endphp
                                 <h4 class="text-base mb-4 font-semibold text-gray-600 dark:text-gray-300">
                                     @if ($userNameSelected2)
                                         {{ $userNameSelected2->name }}
                                     @else
                                         User Not Selected
                                     @endif
                                     @if ($selectedYear2)
                                         - {{ $selectedYear2 }}
                                     @endif
                                 </h4>
                                 <p class="text-lg text-[#0694a2] dark:text-gray-400 font-semibold">
                                     @if (isset($categoryNameSelected->category_name))
                                         {{ $categoryNameSelected->category_name }}
                                     @endif
                                 </p>
                                 <p class="text-gray-800 dark:text-gray-300 text-lg font-bold mb-7 my-3">
                                     @if (isset($categoryNameSelected))
                                         {{ number_format($totalGeneral, 0, '.', ',') }}
                                         {{ $currencyType }}
                                     @endif
                                 </p>
                             </div>
                         </div>
                     </div>

                 </div>

                 <script>
                     var userName = "{{ $categoryNameSelected ? $categoryNameSelected->category_name : '' }}";
                     var ctx = document.getElementById('{{ $chartId }}').getContext('2d');

                     var dataBar = {
                         labels: [
                             @for ($i = 1; $i <= 12; $i++)
                                 "{{ ucfirst(\Carbon\Carbon::create()->month($i)->translatedFormat('F')) }}",
                             @endfor
                         ],

                         datasets: [{
                             label: userName,
                             backgroundColor: "#0694a2",
                             borderColor: "#0694a2",
                             data: [
                                 @foreach ($ArrayCategories as $data)
                                     {{ $data['total'] }},
                                 @endforeach
                             ],
                         }]
                     };

                     var options = {
                         title: {
                             display: true,
                             text: ' ',
                             responsive: true,
                             legend: {
                                 display: false,
                             },
                         },
                         scales: {
                             xAxes: [{
                                 display: true,
                                 title: 'Mes',
                             }],
                             yAxes: [{
                                 display: true,
                                 title: 'Valor'
                             }]
                         },
                         tooltips: {
                             callbacks: {
                                 title: function(tooltipItem, data) {
                                     return data.labels[tooltipItem[0].index];
                                 },
                                 label: function(tooltipItem, data) {
                                     var datasetLabel = data.datasets[tooltipItem.datasetIndex].label;
                                     var value = tooltipItem.value;
                                     var currencyType = '{{ $SelectMainCurrencyTypeRender }}';

                                     // Utiliza un ternario para cambiar 'Blue-ARS' a 'ARS'
                                     currencyType = (currencyType === 'Blue-ARS') ? ' ARS' : currencyType;

                                     // Aplicar formato con toLocaleString
                                     if (!isNaN(value)) {
                                         value = Number(value).toLocaleString('en-US') + ' ' + currencyType;
                                     }

                                     return datasetLabel + ': ' + value;
                                 }
                             }
                         }

                     };

                     var myChart = new Chart(ctx, {
                         type: 'bar',
                         data: dataBar,
                         options: options
                     });
                 </script>

             </div>
         @endif
     </div>
     <!-- END REPORT GENERAL CATEGORIES TABLE  -->
 </div>

 <!--  JSPDF HTML2CANVAS -->

 <script type="text/javascript">
     function generatePDF2() {
         // Obtén el valor del atributo de datos para la categoría seleccionada
         const userInfoElement = document.getElementById('userInfo2');
         const categoryNameSelected = userInfoElement.getAttribute('data-category-name');
         const selectedYear2 = userInfoElement.getAttribute('data-year');
         const reportDate = userInfoElement.getAttribute('data-report');
         const userNameSelected = userInfoElement.getAttribute('data-username');

         // Verifica si la categoría está seleccionada
         if (categoryNameSelected) {
             const {
                 jsPDF
             } = window.jspdf;
             const doc = new jsPDF();

             // Usa html2canvas para convertir el contenido del div en una imagen
             html2canvas(document.getElementById('content2')).then((canvas) => {
                 const imgData = canvas.toDataURL('image/png');

                 // Calcula las coordenadas para centrar el logo en el PDF
                 const pdfWidth = doc.internal.pageSize.width;
                 const logoWidth = 31;
                 const logoHeight = 22;
                 const logoX = (pdfWidth - logoWidth) / 2;
                 const logoY = 10;
                 doc.addImage('{{ asset('img/logo.png') }}', 'PNG', logoX, logoY, logoWidth, logoHeight);

                 // Calcula las coordenadas para centrar la imagen en el PDF
                 const imgWidth = 190;
                 const imgHeight = 75;
                 const x = (pdfWidth - imgWidth) / 2;
                 const y = 60;

                 // Agrega la imagen al PDF
                 doc.addImage(imgData, 'PNG', x, y, imgWidth, imgHeight);

                 // Ajusta el tamaño y color del texto
                 const fontSize = 12;
                 const generalReportColor = '#000000'; // Negro
                 const textColor = '#0000FF'; // Azul

                 // Agrega texto alineado a la izquierda debajo del logo
                 const textBelowLogoY = logoY + logoHeight + 10;
                 const textLeftMargin = 20;

                 // Convierte la cadena 'reportDate' a formato capitalize
                 function capitalize(str) {
                     return str.replace(/\b\w/g, function(char) {
                         return char.toUpperCase();
                     });
                 }

                 const capitalizedReportDate = capitalize(reportDate);


                 // Agrega el texto al PDF con colores diferentes
                 doc.setFontSize(fontSize);
                 doc.setTextColor(generalReportColor);
                 doc.text('Report Category ', textLeftMargin, textBelowLogoY);

                 doc.setTextColor(textColor);
                 doc.text(' ' + userNameSelected + ' ' + selectedYear2, textLeftMargin + doc.getTextWidth(
                     'Report Category '), textBelowLogoY);


                 // Agrega otro texto debajo del primer texto
                 const reportDateTextY = textBelowLogoY + 10;
                 // Cambia el color del texto para 'Report Date'
                 doc.setTextColor(generalReportColor);
                 doc.text('Report Date: ', textLeftMargin, reportDateTextY);
                 // Cambia el color del texto para el valor de 'capitalizedReportDate'
                 doc.setTextColor(textColor);
                 doc.text(capitalizedReportDate, textLeftMargin + doc.getTextWidth('Report Date: '),
                     reportDateTextY);


                 // Formatea la fecha como DD-MM-YYYY
                 const formattedDate = new Date().toLocaleDateString('es-ES', {
                     day: '2-digit',
                     month: '2-digit',
                     year: 'numeric'
                 });


                 // Genera el nombre del archivo con el nombre de usuario y la fecha
                 const fileName =
                     `Report-Categories-Chart-${userNameSelected}-${categoryNameSelected}-${formattedDate}.pdf`;

                 // Guarda el PDF con el nombre generado
                 doc.save(fileName);

             });
         } else {
             // Si la categoría no está seleccionada, muestra un mensaje o realiza alguna otra acción
             alert("Por favor, selecciona una categoría antes de generar el PDF.");
         }
     }
 </script>

 <script type="text/javascript">
     function downloadImage2() {
         // Obtén el valor del atributo de datos para la categoría seleccionada
         const userInfoElement = document.getElementById('userInfo2');
         const categoryNameSelected = userInfoElement.getAttribute('data-category-name');
         const userNameSelected = userInfoElement.getAttribute('data-username');

         // Verifica si la categoría está seleccionada
         if (categoryNameSelected) {
             // Usa html2canvas para convertir el contenido del div en una imagen
             html2canvas(document.getElementById('content2')).then((canvas) => {

                 // Formatea la fecha como DD-MM-YYYY
                 const formattedDate = new Date().toLocaleDateString('es-ES', {
                     day: '2-digit',
                     month: '2-digit',
                     year: 'numeric'
                 });

                 const imgData = canvas.toDataURL('image/jpeg'); // Puedes cambiar a 'image/png' si prefieres PNG

                 // Crea un enlace temporal y simula un clic para descargar la imagen
                 const link = document.createElement('a');
                 const fileName =
                     `Report-Categories-Image-${userNameSelected}-${categoryNameSelected}-${formattedDate}.jpg`; // Cambia a '.png' si prefieres PNG
                 link.href = imgData;
                 link.download = fileName;
                 document.body.appendChild(link);
                 link.click();
                 document.body.removeChild(link);
             });
         } else {
             // Si la categoría no está seleccionada, muestra un mensaje o realiza alguna otra acción
             alert("Por favor, selecciona una categoría antes de descargar la imagen.");
         }
     }
 </script>


 <!-- END JSPDF HTML2CANVAS -->
