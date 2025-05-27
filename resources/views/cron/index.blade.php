<x-layouts.layout>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4 mb-6">

        <p class="text-2xl font-bold">Cron Runner</p>

       
    </div>

    <div class="mb-8">


        <section class="bg-gray-50 dark:bg-gray-900 ">
            <div class="mx-auto  ">
                <!-- Start coding here -->
                <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">

                  
                    <div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="px-4 py-3">Name</th>
                                        <th scope="col" class="px-4 py-3">Command</th>
                                        <th scope="col" class="px-4 py-3">Schedule</th>
                                        <th scope="col" class="px-4 py-3">Status</th>
                                        <th scope="col" class="px-4 py-3"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ( $dataList as $item)
                                        
                                  
                    
                                    <tr class="border-b dark:border-gray-700 hover:bg-gray-100">
                                        
                                        
                                        <th class="px-4 py-3">{{$item->name}}</th>
                                        <td class="px-4 py-3">{{$item->command}}</td>
                                        <td class="px-4 py-3">{{$item->schedule}}</td>
                                        <td class="px-4 py-3">

                                             
                                            @if ($item->status == 1)
                                                <span class="bg-green-100 text-green-800 text-xs font-medium inline-flex items-center px-2.5 py-0.5 rounded-sm me-2 dark:bg-green-700 dark:text-green-400 border border-green-500 ">
                                                    Running...
                                                </span>

                                        
                                            @else

                                                <span class="bg-blue-100 text-blue-800 text-xs font-medium inline-flex items-center px-2.5 py-0.5 rounded-sm me-2 dark:bg-blue-700 dark:text-blue-400 border border-blue-500 ">
                                                    Stopped
                                                </span>

                                                
                                            @endif

                                        </td>
                                        <td class="px-4 py-3 flex">
                                            <a href="{{route('cronrunner.edit',$item->id)}}" class=" items-center justify-center text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-4 py-2 me-2 dark:bg-green-600 dark:hover:bg-green-700 focus:outline-none dark:focus:ring-primary-800">
                                                Edit
                                            </a>


                                        </td>
                                        
                                        
                                    </tr>
                                        
                                    @empty
                    
                                    <tr class="border-b dark:border-gray-700 hover:bg-gray-100">
                                       
                                        <td class="px-4 py-3 text-center" colspan="9">Data Empty</td>
                                       
                                        
                                        
                                    </tr>
                                        
                                    @endforelse ()
                                    
                                  
                                
                                </tbody>
                            </table>
                        </div>
                    
                    </div>


                   
                </div>
            </div>
        </section>
       

    </div>

    @push('jsBody')


        
    @endpush

</x-layouts.layout>