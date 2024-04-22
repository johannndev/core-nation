@foreach ($arrayNameWh as $key => $item)

<td class="wh-col hidden px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
    @isset($arrayStokWh[$key])
        {{$arrayStokWh[$key]}}
    @endisset

    @empty($arrayStokWh[$key])
        0
    @endempty
</td>

@endforeach
    
    
  
        
