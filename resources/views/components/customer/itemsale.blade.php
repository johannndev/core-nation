<div class=" ">
    <!-- Start coding here -->
    <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
       
        
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-4 py-3">Periode</th>
                        <th scope="col" class="px-4 py-3">Group item</th>
                        <th scope="col" class="px-4 py-3">Type</th>
                        <th scope="col" class="px-4 py-3">Total Qty</th>
                        <th scope="col" class="px-4 py-3">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ( $dataList as $item)
                        
                   

                    <tr class="border-b dark:border-gray-700 hover:bg-gray-100">
                        
                        <th scope="row" class="px-4 py-3  whitespace-nowrap ">
                    
                          {{$item->bulan}}/{{$item->tahun}}

                        </th>
               
                        <td class="px-4 py-3">

                            @isset($item->group)

                            <p><a href="{{route('item.detailGroup',$item->group->id)}}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">{{ $item->group->name }}</a></p>
                                
                            @endisset

                            @empty($item->group)
                            -
                            @endempty

                        </td>
                        <td class="px-4 py-3">   
                            <span class="text-nowrap bg-primary-100 text-primary-800 text-xs font-medium px-2 py-0.5 rounded dark:bg-primary-900 dark:text-primary-300"> {{$item->type_name}}</span> 
                        </td>
                        <td class="px-4 py-3">{{$item->sum_qty}}</td>
                        <td class="px-4 py-3">{{number_format(abs($item->sum_total),2,',','.')}}</td>
                       
                        
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
</div>