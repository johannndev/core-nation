<x-layouts.layout>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4 mb-6">

        <p class="text-2xl font-bold">Borongan Detail</p>

       
    </div>

    <div class="mb-8">

      @if (session('error'))

        <div id="alert-2" class="flex items-center p-4 mb-4 text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
          <svg class="flex-shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
          </svg>
          <span class="sr-only">Info</span>
          <div class="ms-3 text-sm font-medium">
            {{session('error')}}
          </div>
          <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-red-50 text-red-500 rounded-lg focus:ring-2 focus:ring-red-400 p-1.5 hover:bg-red-200 inline-flex items-center justify-center h-8 w-8 dark:bg-gray-800 dark:text-red-400 dark:hover:bg-gray-700" data-dismiss-target="#alert-2" aria-label="Close">
            <span class="sr-only">Close</span>
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
            </svg>
          </button>
        </div>

      @endif

       

        <section class="bg-gray-50 dark:bg-gray-900 mb-6">
            <div class="mx-auto  ">
                <!-- Start coding here -->
                <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden  p-4">
                  
                    <div class="">
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div class="border-t border-gray-100">
                                <dl class="divide-y divide-gray-100">
                                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                    <dt class="text-sm font-medium leading-6 text-gray-900">Date</dt>
                                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                                    {{\carbon\Carbon::parse($borongan->date)->format('d/m/Y')}}
                                    </dd>
                                </div>
                                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                    <dt class="text-sm font-medium leading-6 text-gray-900">Period</dt>
                                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                                        {{\carbon\Carbon::parse($borongan->from)->format('d/m/Y')}} -  {{\carbon\Carbon::parse($borongan->to)->format('d/m/Y')}}
                                    </dd>
                                </div>
                                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                    <dt class="text-sm font-medium leading-6 text-gray-900">Group</dt>
                                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{$borongan->jahit->name}}</dd>
                                </div>
                                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                    <dt class="text-sm font-medium leading-6 text-gray-900">Items</dt>
                                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                                        {{$borongan->total_items}}
                                    </dd>
                                </div>
                                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                    <dt class="text-sm font-medium leading-6 text-gray-900">User</dt>
                                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{$borongan->user->username}}</dd>
                                </div>
                                
    
                                
                                
                                </dl>
                            </div>

                            <div class="border-t border-gray-100">
                                <dl class="divide-y divide-gray-100">
                                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                    <dt class="text-sm font-medium leading-6 text-gray-900">Tres</dt>
                                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                                        {{ number_format($borongan->tres) }}
                                    </dd>
                                </div>
                                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                    <dt class="text-sm font-medium leading-6 text-gray-900">Permak</dt>
                                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                                        {{ number_format($borongan->permak) }}
                                    </dd>
                                </div>
                                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                    <dt class="text-sm font-medium leading-6 text-gray-900">Lain-lain</dt>
                                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ number_format($borongan->lain2) }}</dd>
                                </div>
                                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                    <dt class="text-sm font-medium leading-6 text-gray-900">Total</dt>
                                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                                        {{ number_format($borongan->total) }}
                                    </dd>
                                </div>
                            
              
                                </dl>
                            </div>
                        </div>
                    </div>
                 
                   
                </div>
            </div>
        </section>

        <section class="bg-gray-50 dark:bg-gray-900 ">
            <div class="mx-auto  ">
                <!-- Start coding here -->
                <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
                   
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-4 py-3">Kitir</th>
                                    <th scope="col" class="px-4 py-3">Items</th>
                                    <th scope="col" class="px-4 py-3">Price</th>
                                    <th scope="col" class="px-4 py-3">Quantity</th>
                                    <th scope="col" class="px-4 py-3">Total</th>

                                </tr>
                            </thead>
                            <tbody>
                                @forelse ( $detaiList as $item)
                                    
                              

                                <tr class="border-b dark:border-gray-700 hover:bg-gray-100">
                                   
                                    <th class="px-4 py-3">{{$item->serial()}}</th>
                                    <td scope="row" class="px-4 py-3  whitespace-nowrap ">
                                        
                                        <a href="{{route('item.detail',$item->item_id)}}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">{{$item->item->code}}</a>

                                    </td>
                           
                                    <td class="px-4 py-3"> {{ number_format($item->ongkos) }}</td>
                                    <td class="px-4 py-3">{{ number_format($item->quantity) }}</td>
                                    <td class="px-4 py-3">{{ number_format($item->total) }}</td>
                                   
                                    
                                </tr>
                                    
                                @empty

                                <tr class="border-b dark:border-gray-700 hover:bg-gray-100">
                                   
                                    <td class="px-4 py-3 text-center" colspan="9">Data Empty</td>
                                   
                                    
                                    
                                </tr>
                                    
                                @endforelse ()
                                
                              
                            
                            </tbody>
                        </table>
                    </div>

                    {{-- {{$detaiList->onEachSide(1)->links()}} --}}

                   
                </div>
            </div>
        </section>

       
       

    </div>

 

    

  

</x-layouts.layout>