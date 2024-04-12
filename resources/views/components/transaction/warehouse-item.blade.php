<div>
    <!-- Happiness is not something readymade. It comes from your own actions. - Dalai Lama -->

    <div class="grid grid-cols-8 divide-x mt-6">

       
        @foreach ($arrayNameWh as $key => $item)
        <div>
            <div class="text-center">
                <div>
                    <p class="font-bold">{{$item}}</p>
                </div>
                <div class="">
                    @isset($arrayStokWh[$key])
                        {{$arrayStokWh[$key]}}
                    @endisset

                    @empty($arrayStokWh[$key])
                       0
                    @endempty
                </div>
            </div>
        </div>
        @endforeach
        
    </div>
  
        
   
</div>