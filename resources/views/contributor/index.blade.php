<x-layouts.layout>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4 mb-6">

        @php
            if(Request('from')){
                $df = \carbon\Carbon::parse(Request('from'))->format('d/m/Y');
            }else{
                $df = \carbon\Carbon::parse($from)->format('d/m/Y');
            }

            if(Request('to')){
                $dt = \carbon\Carbon::parse(Request('to'))->format('d/m/Y');
            }else{
                $dt = \carbon\Carbon::parse($to)->format('d/m/Y');
            }
        @endphp

        <p class="text-2xl font-bold">Contributors from {{$df}} - {{$dt}}</p>

       
    </div>

    <div class="mb-8">


        <section class="bg-gray-50 dark:bg-gray-900 ">
            <div class="mx-auto  ">
                <!-- Start coding here -->
                <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
                    <form action="{{route('contributor.filter')}}" method="get">
                        <div class="flex flex-col md:flex-row items-end justify-between p-4">
                        
                            
                            <div class="w-full md:w-4/6">
                            
                                <div class="grid gap-4 md:grid-cols-5">
                                    <div>
                                        <label for="from" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">From</label>
                                        <input type="date" id="from" name="from" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"  value="{{Request('from') ? Request('from') : $from}}"/>
                                    </div>
                                    <div>
                                        <label for="to" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">To</label>
                                        <input type="date" id="to" name="to" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"  value="{{Request('to') ? Request('to') : $to}}"/>
                                    </div>
                                    <div>
                                        <x-partial.data-list :dataProp='$dataListPropCustomer' />
                                    </div>
                                    <div>
                                      
                                        <label for="filterBrand" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Type</label>
                                        <select name="filterBrand" id="filterBrand" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                            <option value="" {{Request('filterBrand') ? '' : 'selected' }}>Choose</option>
                                         
                                            @foreach ($typeList as  $item)

                                                @php
                                                     $itemId = (string)$item['id']; 
                                                @endphp


                                                <option {{Request('filterBrand')== $itemId ? 'selected' : '' }} value="{{$item['id']}}">{{$item['name']}}</option>
                                            @endforeach
                                            
                                            
                                        </select>
                                         
                                       
                                    </div>

                                   

                                    

                                   

                                </div>

                                    
                                
                            </div>
                            <div class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">
                                <button type="submit" class="flex items-center justify-center text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800">
                                    <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" class="h-4 w-4 mr-2 " viewbox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd" />
                                    </svg>
                                    Filter
                                </button>

                                <a href="{{route('contributor.index')}}" class="flex items-center justify-center py-2 px-5 me-2 mb-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">

                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" >
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                      </svg>

                                      
                                    {{-- <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" c viewbox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd" />
                                    </svg> --}}

                                    Clear
                                </a>

                            
                            </div>

                            
                        </div>
                    </form>
                   
                    <div class="overflow-x-auto">
                       
                    </div>

                 

                   
                </div>
            </div>
        </section>

        <div class="bg-white shadow-md sm:rounded-t-lg mt-8">
            
            <div id="accordion-collapse" data-accordion="collapse">
                <h2 id="accordion-collapse-heading-1">
                <button type="button" class="flex items-center justify-between w-full p-5 font-medium rtl:text-right text-gray-500 border border-b-0 border-gray-200 rounded-t-xl focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-800 dark:border-gray-700 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 gap-3" data-accordion-target="#accordion-collapse-body-1" aria-expanded="true" aria-controls="accordion-collapse-body-1">
                    <span>Top 50 Item</span>
                    <svg data-accordion-icon class="w-3 h-3 rotate-180 shrink-0" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5 5 1 1 5"/>
                    </svg>
                </button>
                </h2>
                <div id="accordion-collapse-body-1" class="hidden" aria-labelledby="accordion-collapse-heading-1">
                <div class="p-5 border border-b-0 border-gray-200 dark:border-gray-700 ">
                    <x-contributor.top />
                </div>
                </div>
                <h2 id="accordion-collapse-heading-2">
                <button type="button" class="flex items-center justify-between w-full p-5 font-medium rtl:text-right text-gray-500 border border-b-0 border-gray-200 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-800 dark:border-gray-700 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 gap-3" data-accordion-target="#accordion-collapse-body-2" aria-expanded="false" aria-controls="accordion-collapse-body-2">
                    <span>Group by Brand</span>
                    <svg data-accordion-icon class="w-3 h-3 rotate-180 shrink-0" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5 5 1 1 5"/>
                    </svg>
                </button>
                </h2>
                <div id="accordion-collapse-body-2" class="hidden" aria-labelledby="accordion-collapse-heading-2">
                <div class="p-5 border border-b-0 border-gray-200 dark:border-gray-700">
                    <x-contributor.brand />
                </div>
                </div>
                <h2 id="accordion-collapse-heading-3">
                <button type="button" class="flex items-center justify-between w-full p-5 font-medium rtl:text-right text-gray-500 border border-gray-200 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-800 dark:border-gray-700 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 gap-3" data-accordion-target="#accordion-collapse-body-3" aria-expanded="false" aria-controls="accordion-collapse-body-3">
                    <span>Group by Type</span>
                    <svg data-accordion-icon class="w-3 h-3 rotate-180 shrink-0" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5 5 1 1 5"/>
                    </svg>
                </button>
                </h2>
                <div id="accordion-collapse-body-3" class="hidden" aria-labelledby="accordion-collapse-heading-3">
                <div class="p-5 border border-t-0 border-gray-200 dark:border-gray-700">
                    <x-contributor.type />
                </div>
                </div>

                <h2 id="accordion-collapse-heading-4">
                <button type="button" class="flex items-center justify-between w-full p-5 font-medium rtl:text-right text-gray-500 border border-gray-200 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-800 dark:border-gray-700 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 gap-3" data-accordion-target="#accordion-collapse-body-4" aria-expanded="false" aria-controls="accordion-collapse-body-4">
                    <span>Group by Size</span>
                    <svg data-accordion-icon class="w-3 h-3 rotate-180 shrink-0" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5 5 1 1 5"/>
                    </svg>
                </button>
                </h2>
                <div id="accordion-collapse-body-4" class="hidden" aria-labelledby="accordion-collapse-heading-4">
                <div class="p-5 border border-t-0 border-gray-200 dark:border-gray-700">
                    <x-contributor.size />
                </div>
                </div>

                {{-- <h2 id="accordion-collapse-heading-5">
                <button type="button" class="flex items-center justify-between w-full p-5 font-medium rtl:text-right text-gray-500 border border-gray-200 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-800 dark:border-gray-700 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 gap-3" data-accordion-target="#accordion-collapse-body-5" aria-expanded="false" aria-controls="accordion-collapse-body-5">
                    <span>Summary</span>
                    <svg data-accordion-icon class="w-3 h-3 rotate-180 shrink-0" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5 5 1 1 5"/>
                    </svg>
                </button>
                </h2>
                <div id="accordion-collapse-body-5" class="hidden" aria-labelledby="accordion-collapse-heading-5">
                <div class="p-5 border border-t-0 border-gray-200 dark:border-gray-700">
                    <x-contributor.all />
                </div>
                </div> --}}
        </div>
  
        </div>
       

    </div>

    @push('jsBody')

    <script>


     
    </script>
        
    @endpush

</x-layouts.layout>