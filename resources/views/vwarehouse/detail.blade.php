<x-layouts.layout>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4 mb-6">

        <p class="text-2xl font-bold">{{$nameCustomer}}  </p>

       
    </div>

    
    <div class="mb-8">
        <div class="text-sm font-medium text-center text-gray-500 border-b border-gray-200 dark:text-gray-400 dark:border-gray-700">
            <ul class="flex flex-wrap -mb-px">
            
              
                <li class="me-2">
                    <a href="{{route('vwarehouse.detail',$cid)}}" class="inline-block p-4 text-blue-600 border-b-2 border-blue-600 rounded-t-lg active dark:text-blue-500 dark:border-blue-500">Detail</a>
                </li>
                <li class="me-2">
                    <a href="{{route('vwarehouse.transaction',$cid)}}" class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300" aria-current="page">Transaction</a>
                </li>

                <li class="me-2">
                    <a href="{{route('vwarehouse.items',$cid)}}" class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300">Items</a>
                </li>

                <li class="me-2">
                    <a href="{{route('vwarehouse.stat',$cid)}}" class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300">Stats</a>
                </li>

                <li class="me-2">
                    <a href="{{route('vwarehouse.itemsale',$cid)}}" class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300">Item Sale</a>
                </li>
                
            </ul>
        </div>
    </div>

    <div class="mb-8">


        <section class="bg-gray-50 dark:bg-gray-900 ">
            <div class="mx-auto  ">
                <!-- Start coding here -->
                <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
                    
                    {{-- {{$customerType}} --}}
                   
                    <x-customer.detail :nameType='$nameType'  :cid='$cid' />

                   
                </div>
            </div>
        </section>
       

    </div>

    @push('jsBody')

    <script>
     

     
    </script>
        
    @endpush

</x-layouts.layout>