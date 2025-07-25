<x-layouts.layout>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4 mb-6">

        <p class="text-2xl font-bold">Report {{ $label }}</p>

       
    </div>

    <div class="mb-8">


        <section class="bg-gray-50 dark:bg-gray-900 ">
            <div class="mx-auto  ">
                <!-- Start coding here -->
                <div class="bg-white font-semibold  relative shadow-md sm:rounded-lg overflow-hidden">
                    
                    
                    <div class="overflow-auto max-h-[600px] relative" id="scrollWrapper">
                        <table class="min-w-max w-full text-left text-xs">
                            <thead id="stickyHeader" class="sticky top-0 z-20 bg-white font-semibold  transition-shadow">
                                <tr class="bg-white font-semibold ">
                                    <th id="customerColumnHeader" class="sticky top-0 left-0 z-30 bg-white font-semibold   w-48 text-green-600 text-xs  border-b">
                                        <div class="border-e px-4 py-2">
                                             Nama Customer
                                        </div>
                                       
                                    </th>
                                    @foreach ($allMonths as $month)
                                        <th class="sticky top-0 z-20 bg-white font-semibold  px-4 py-2 w-32 text-xs text-green-600 text-right border-b">
                                            {{ $month }}
                                        </th>
                                    @endforeach
                                    <th class="sticky top-0 z-20 bg-white font-semibold  px-4 py-2 w-32 text-xs text-green-600 text-right border-b">
                                        Total
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($customerTotals as $customer => $totals)
                                    <tr class="border-b ">
                                        <td id="customerColumn" class=" shadow-lg sticky left-0 bg-white font-semibold  z-10  w-48 font-semibold ">
                                            <div class="border-e px-4 py-2">
                                                {{ $customer }}
                                            </div>
                                            
                                          
                                        </td>
                        
                                        @foreach($allMonths as $bulan)
                                            <td class="px-4 py-2 w-32 text-right">
                                                {{ number_format(abs($totals[$bulan]), 2) }}
                                            </td>
                                        @endforeach
                                        <td class="px-4 py-2 w-32 text-right text-blue-500 font-bold">
                                            {{ number_format(abs($totals['Total']), 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                                 {{-- Total per bulan --}}
                                    <tr class="border-b">
                                        <td id="customerColumnHeader" class="sticky top-0 left-0 z-30 bg-white font-bold   w-48 text-xs  border-b">
                                        <div class="border-e px-4 py-2">
                                            Total
                                        </div>
                                       
                                        </td>
                                        @foreach($allMonths as $bulan)
                                            <td class="px-4 py-2 w-32 text-right font-bold  ">
                                                {{ number_format(abs($grandTotalPerMonth[$bulan]), 2) }}
                                            </td>
                                        @endforeach
                                        <td class="px-4 py-2 w-32 text-right font-bold  text-blue-500">
                                            {{ number_format(abs($grandTotalOverall), 2) }}
                                        </td>
                                    </tr>
                            </tbody>
                        </table>
                    </div>



                  
                   
                </div>
            </div>
        </section>
       

    </div>

    @push('jsBody')

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const scrollContainer = document.getElementById('scrollWrapper');
            const header = document.getElementById('stickyHeader');
            const customerHeader = document.getElementById('customerColumnHeader');
            const customerColumnCells = document.querySelectorAll('#customerColumn');

            scrollContainer.addEventListener('scroll', function () {
                // Shadow untuk header atas
                if (scrollContainer.scrollTop > 0) {
                    header.classList.add('shadow-md');
                } else {
                    header.classList.remove('shadow-md');
                }

                // Shadow untuk scroll ke kanan (header)
                if (scrollContainer.scrollLeft > 0) {
                    header.classList.add('shadow-[inset_-8px_0_8px_-6px_rgba(0,0,0,0.2)]');
                    customerHeader.classList.add('shadow-lg');
                    customerColumnCells.forEach(el => el.classList.add('shadow-lg'));
                } else {
                    header.classList.remove('shadow-[inset_-8px_0_8px_-6px_rgba(0,0,0,0.2)]');
                    customerHeader.classList.remove('shadow-lg');
                    customerColumnCells.forEach(el => el.classList.remove('shadow-lg'));
                }
            });
        });
    </script>

    <script>
      document.addEventListener('DOMContentLoaded', function() {
          var toggleNamaImage = document.getElementById('image-checkbox');
          var namaColumnImage = document.querySelectorAll('.image-col');
    

          toggleNamaImage.addEventListener('change', function() {
              if (toggleNamaImage.checked) {
                  namaColumnImage.forEach(function(barcode) {
                      barcode.classList.remove('hidden');
                  });
              } else {
                  namaColumnImage.forEach(function(image) {
                      image.classList.add('hidden');
                  });
              }
          });

      });

     
    </script>
        
    @endpush

</x-layouts.layout>