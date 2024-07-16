<div class="">
    <div >
        <div class="">
            <div class="relative ">
                <select class="select-item{{$ids}}" name="addMoreInputFields[{{$ids}}][name]">
                    <option ></option>
                </select>

                @error('')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                @enderror
                
            </div>
            
        </div>

        <div class="">
            <div class="relative ">
                <select class="select-item1" name="addMoreInputFields[1][name]">
                    <option ></option>
                </select>

                @error('')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                @enderror
                
            </div>
            
        </div>
          
    </div>

    @push('jsBody')

    <script>
        $(document).ready(function() {

            

          

         

        })
    </script>

    @endpush
</div>