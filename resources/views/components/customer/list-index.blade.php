<div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    
                    <th scope="col" class="px-4 py-3">Name</th>
                    @if ($hidePropBalance == 'show')
                    <th scope="col" class="px-4 py-3">Balance</th>
                    @endif
                    @if ($type != App\Models\Customer::TYPE_CUSTOMER)

                    <th scope="col" class="px-4 py-3">Location</th>

                    @endif
                    <th scope="col" class="px-4 py-3">Actions</th>
                    
                </tr>
            </thead>
            <tbody>
                @forelse ( $dataList as $item)
                    
              

                <tr class="border-b dark:border-gray-700 hover:bg-gray-100">
                   
                    <th scope="row" class="px-4 py-3  whitespace-nowrap ">
                        
                        <a href="{{route($nameType.'.transaction',$item->id)}}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">{{$item->name}}</a>

                    </th>
                    @if ($hidePropBalance == 'show')
                    <td class="px-4 py-3">{{Number::format($item->stat->balance,2)}}</td>
                    @endif

                    @if ($type != App\Models\Customer::TYPE_CUSTOMER)

                    <td class="px-4 py-3">
                        

                        @isset($item->locations)

                           

                            {{$item->getLocation($item->locations)}}
                                
                        @endisset
                       

                        
                    </td>

                    @endif
                 
                    <td class="px-4 py-3">
                        <a href="{{route($nameType.'.detail',$item->id)}}" class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-4 py-2 me-2  dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">Detail</button>


                        <a href="{{route($nameType.'.edit',$item->id)}}" class=" items-center justify-center text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800">
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

    {{$dataList->onEachSide(1)->links()}}
</div>