<div>
    <div class="p-4 flex items-center">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
        </svg>
        <p class="ml-2 font-medium text-lg">
            @if($data->memberId)
                {{ $data->memberId }}</a>
            @else
                Not a Member
            @endif
        </p>
    </div>

    <hr class="h-px  bg-gray-200 border-0 dark:bg-gray-700">

    <div class="p-4">

        <div class="grid grid-cols-3 gap-4">
            <div>
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z" />
                      </svg>
                      
    
                    <p class="font-medium ml-2">Balance</p>
                </div>

                <div class="mt-2 flex">
                    <p class="font-bold mr-1">Rp</p>
                    <p class="font-bold text-2xl">{{number_format($data->stat->balance,2)}}</p>
                </div>
            </div>

            <div>
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                      </svg>
    
                    <p class="font-medium ml-2">ADDRESS</p>
                </div>

                <div class="mt-2">
                    @if ($data->address)

                        <p class="mb-6">{{$data->address}}</p>

                        <a href="https://maps.google.com/maps?q={{ Str::slug($data->address) }}" class="text-green-700 hover:text-white border border-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-3 py-2 text-center  dark:border-green-500 dark:text-green-500 dark:hover:text-white dark:hover:bg-green-600 dark:focus:ring-green-800">Open Gmaps</a>
                        
                    @else
                        No Addres
                    @endif
                    
                </div>
            </div>

            <div>
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z" />
                      </svg>
                      
    
                    <p class="font-medium ml-2">Contact</p>
                </div>

                <div class="mt-2">
                    <div class="grid grid-cols-6 gap-4">
                        <div class="col-span-2 text-gray-500">
                            Phone
                        </div>
                        <div class="col-span-4">
                            @if($data->phone)
                                <a class="hover:text-blue-500" href="tel:{{ $data->phone }}">{{ $data->phone }}</a>
                            @else
                                No Phone
                            @endif
                        </div>
                    </div>
                    <div class="grid grid-cols-6 gap-4">
                        <div class="col-span-2 text-gray-500">
                            2nd Phone
                        </div>
                        <div class="col-span-4">
                            @if($data->phone2)
                                <a class="hover:text-blue-500" href="tel:{{ $data->phone2 }}">{{ $data->phone2 }}</a>
                            @else
                                No 2nd Phone
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-6 gap-4">
                        <div class="col-span-2 text-gray-500">
                            Fax
                        </div>
                        <div class="col-span-4">
                            @if($data->fax)
                               {{ $data->fax }}
                            @else
                                No Fax
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-6 gap-4">
                        <div class="col-span-2 text-gray-500">
                            Email
                        </div>
                        <div class="col-span-4">
                            @if($data->email)
                                <a class="hover:text-blue-500" href="mailto:{{ $data->email }}">{{ $data->email }}</a>
                            @else
                                No Email
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>


    <hr class="h-px  bg-gray-200 border-0 dark:bg-gray-700">

    <div class="p-4">

        @if ($data->trashed()) 
            <form class="inline-flex" action="{{route('addrbook.restore',[$data->id, 'action' => 'customer', 'type' => $data->type])}}" method="post">
                @csrf
        
            <button type="submit" class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2  dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">Restore</button>

            </form>
        @else
            <form class="inline-flex" action="{{route('addrbook.delete',[$data->id, 'action' => 'customer', 'type' => $data->type])}}" method="post">
                @csrf
           
            <button type="submit" class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2  dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">Delete</button>
    
            </form>
        @endif

       
        <a href="{{route($nameType.'.edit',$data->id)}}" class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2  dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">Edit</a>
    </div>
      
</div>