<div>
    <div class="flex flex-col px-4 py-3 space-y-3 lg:flex-row items-center  lg:space-y-0 lg:space-x-4">
        <div>
            <p class="me-2">Show:</p>
        </div>

        <div>
            <div class="flex items-center ">
                <input id="image-checkbox" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                <label for="image-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Image</label>
            </div>

        </div>

        <div>
            <div class="flex items-center ">
                <input id="online-checkbox" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                <label for="online-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Online</label>
            </div>
        </div>

    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class=" image-col hidden px-4 py-3">Image</th>
                    <th scope="col" class="px-4 py-3">ID</th>
                    <th scope="col" class=" online-col hidden px-4 py-3">SKU</th>
                    <th scope="col" class="normal-col px-4 py-3">Code</th>
                    <th scope="col" class="px-4 py-3">Name</th>
                    <th scope="col" class="px-4 py-3">Description</th>
                    <th scope="col" class="online-col hidden px-4 py-3">Size</th>
                    <th scope="col" class="px-4 py-3">Price</th>
                    <th scope="col" class="px-4 py-3">Quantity</th>
                    <th scope="col" class="online-col hidden px-4 py-3">Disc</th>
                    <th scope="col" class="px-4 py-3">Action</th>
                    
                </tr>
            </thead>
            <tbody>
                @forelse ( $dataList as $item)
                    
                @php
                    $url = $item->item->getImageUrl();
                    
                @endphp

                <tr class="border-b dark:border-gray-700 hover:bg-gray-100">
                   
                    <th scope="row" id="" class="image-col hidden  px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        <div class="h-20 w-20 mr-3">
                            {{-- {{$item->getImageUrl()}} --}}
                            <x-partial.image type="h-20 w-20" :url="$url" />
                        </div>

                    </th>
                    <th scope="row" class="px-4 py-3  whitespace-nowrap ">
                        
                       

                        <a href="{{$item->item->getDetailUrl($item->item_id,$item->item->type)}}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">{{$item->item_id}}</a>

                    </th>
           
                    <td class="px-4 py-3 online-col hidden ">{{$item->item->code}}</td>
                    <td class="px-4 py-3 normal-col">{{$item->item->getItemCode()}}</td>
                    <td class="px-4 py-3 normal-col ">{{$item->item->getItemName()}}</td>
                    <td class="px-4 py-3  online-col hidden">{{$item->item->getOnlineName()}}</td>
                    <td class="px-4 py-3">{{$item->item->group ? $item->item->group->description : $item->item->description}}</td>
                    <td class="px-4 py-3 online-col hidden">
                        @if ($item->item->getSize())

                        <span class="bg-blue-100 text-blue-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300"> {{$item->item->getSize()}}</span>
                            
                        @endif
                       
                    </td>
                    <td class="px-4 py-3">{{Number::format($item->item->price,2)}}</td>
                    <td class="px-4 py-3">{{$item->quantity}}</td>
                    <td class="px-4 py-3  online-col hidden">{{$item->item->getDisc()}}</td>
                 
                    <td class="px-4 py-3">
                        <a href="{{route('item.edit',$item->id)}}" class=" items-center justify-center text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800">
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