<x-layouts.layout>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4 mb-6">

        <p class="text-2xl font-bold">Create gaji {{$karyawan->nama}}</p>

       
    </div>

    @if ($errors->any())
      <div class="flex p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
        <svg class="flex-shrink-0 inline w-4 h-4 me-3 mt-[2px]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
          <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
        </svg>
        <span class="sr-only">Danger</span>
        <div>
          <span class="font-medium">Ensure that these requirements are met:</span>
            <ul class="mt-1.5 list-disc list-inside">
              @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
              @endforeach
              
          </ul>
        </div>
      </div>
    @endif

    <div id="alert-border-2">
      
    @if ((session('errorMessage')))

    <div  class="flex items-center p-4 mb-4 text-red-800 border-t-4 border-red-300 bg-red-50 dark:text-red-400 dark:bg-gray-800 dark:border-red-800" role="alert">
        <svg class="flex-shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
          <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
        </svg>
        <div class="ms-3 text-sm font-medium">
            {{session('errorMessage')}}
        </div>
        <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-red-50 text-red-500 rounded-lg focus:ring-2 focus:ring-red-400 p-1.5 hover:bg-red-200 inline-flex items-center justify-center h-8 w-8 dark:bg-gray-800 dark:text-red-400 dark:hover:bg-gray-700"  data-dismiss-target="#alert-border-2" aria-label="Close">
          <span class="sr-only">Dismiss</span>
          <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
          </svg>
        </button>
    </div>
        
    @endif

</div>



 
<div>
  <form action="{{route('gajih.store',$karyawan->id)}}" method="post" enctype="multipart/form-data">

      @csrf

      <section class="bg-gray-50 dark:bg-gray-900 mb-8">
          <div class="mx-auto  ">
              <!-- Start coding here -->
              <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden p-4">

                  <div class="">

                    <div class="grid grid-cols-3 gap-4 mb-8">

                        <div>
                          <label for="bulan" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Bulan</label>
                          <input type="text" name="bulan" id="disabled-input" aria-label="disabled input" class=" bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 cursor-not-allowed dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500" readonly value="{{$now->month}}" >

                        </div>

                        <div>
                          <label for="tahun" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tahun</label>
                          <input type="text" name="tahun" id="disabled-input" aria-label="disabled input" class=" bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 cursor-not-allowed dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500" readonly value="{{$now->year}}" >

                        </div>

                    </div>


                      <div class="grid md:grid-cols-3 gap-4 mb-8">

                          <div class="">
                            <p class="font-medium mb-4">Rincian Gajih</p>
                            
                            <div>
                                <div class="mb-4">
                                  <label for="bulanan" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Bulanan</label>
                                  <input type="text" name="bulanan" id="bulanan" aria-describedby="helper-text-explanation" class=" bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 cursor-not-allowed dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500" readonly value="{{old('bulanan',$karyawan->bulanan)}}">
    
                                </div>

                                <div class="mb-4">
                                  <label for="harian" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Harian</label>
                                  <input type="text" name="harian" id="harian" aria-describedby="helper-text-explanation" class=" bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 cursor-not-allowed dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500" readonly value="{{old('harian',$karyawan->harian)}}">
    
                                </div>

                                <div class="mb-4">
                                  <label for="premi" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Premi</label>
                                  <input type="text" name="premi" id="premi" aria-describedby="helper-text-explanation" class=" bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 cursor-not-allowed dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500" readonly value="{{old('premi',$karyawan->premi)}}">
    
                                </div>
                            </div>

                            
                           
                              

                          </div>

                          <div>

                            <p class="font-medium mb-4">Rincian Cuti</p>

                            <div class="mb-4">
                              <label for="total_cuti_tahunan" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tahunan</label>
                              <input type="text" name="total_cuti_tahunan" id="total_cuti_tahunan" aria-describedby="helper-text-explanation" class=" bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 cursor-not-allowed dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500" readonly value="{{old('total_cuti_tahunan',(int)$totalCuti[0]['total_cuti_tahunan'])}}">

                            </div>

                            <div class="mb-4">
                              <label for="total_cuti_sakit" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Sakit</label>
                              <input type="text" name="total_cuti_sakit" id="total_cuti_sakit" aria-describedby="helper-text-explanation" class=" bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 cursor-not-allowed dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500" readonly value="{{old('total_cuti_sakit',(int)$totalCuti[0]['total_cuti_sakit'])}}">

                            </div>

                            <div class="mb-4">
                              <label for="total_cuti_mendadak" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Mendadak</label>
                              <input type="text" name="total_cuti_mendadak" id="total_cuti_mendadak" aria-describedby="helper-text-explanation" class=" bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 cursor-not-allowed dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500" readonly value="{{old('total_cuti_mendadak',(int)$totalCuti[0]['total_cuti_mendadak'])}}">

                            </div>

                          </div>

                          <div>

                            <p class="font-medium mb-4">Cuti melewati batas tahunan</p>

                            <div class="mb-4">
                              <label for="batas_cuti_bulanan" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tahunan</label>
                              <input type="text" name="batas_cuti_bulanan" id="batas_cuti_bulanan" aria-describedby="helper-text-explanation" class=" bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 cursor-not-allowed dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500" readonly value="{{old('batas_cuti_bulanan',$dendaCutiTahunan)}}">

                            </div>

                            <div class="mb-4">
                              <label for="batas_cuti_sakit" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Sakit</label>
                              <input type="text" name="batas_cuti_sakit" id="batas_cuti_sakit" aria-describedby="helper-text-explanation" class=" bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 cursor-not-allowed dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500" readonly value="{{old('batas_cuti_sakit',$dendaCutiSakit)}}">

                            </div>

                            <div class="mb-4">
                              <label for="batas_cuti_mendadak" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Mendadak</label>
                              <input type="text" name="batas_cuti_mendadak" id="batas_cuti_mendadak" aria-describedby="helper-text-explanation" class=" bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 cursor-not-allowed dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500" readonly value="{{old('batas_cuti_mendadak',(int)$totalCuti[0]['total_cuti_mendadak'])}}">

                            </div>

                          </div>

                         
                      </div>

                      <div class="grid md:grid-cols-2 gap-4 mb-8">

                          <div class="">
                            <label for="potong_premi" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Potongan Premi({{$grandTotalCuti}})</label>
                            <input type="text" name="potong_premi" id="potong_premi" aria-describedby="helper-text-explanation" class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 cursor-not-allowed dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500" readonly value="{{old('potong_premi',$potongPremi)}}">

                          </div>

                          <div class="">
                            <label for="potong_bulanan" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Potongan Bulanan({{$grandTotalDendaCuti}})</label>
                            <input type="text" name="potong_bulanan" id="potong_bulanan" aria-describedby="helper-text-explanation" class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 cursor-not-allowed dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500" readonly value="{{old('potong_bulanan',$grandTotalDendaCutiRupiah)}}">

                          </div>

                          <div class="">
                            <label for="bonus" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Bonus</label>
                            <input type="number" name="bonus" id="bonus" aria-describedby="helper-text-explanation" class=" border text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 @error('bonus') bg-red-50  border-red-500 text-red-900 @else bg-gray-50  border-gray-300 text-gray-900 @enderror" value="{{old('bonus',0)}}">

                          </div>
                          <div class="">
                            <label for="sanksi" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Sanksi</label>
                            <input type="text" name="sanksi" id="sanksi" aria-describedby="helper-text-explanation" class=" border text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 @error('sanksi') bg-red-50  border-red-500 text-red-900 @else bg-gray-50  border-gray-300 text-gray-900 @enderror" value="{{old('sanksi',0)}}">

                          </div>

                          <div>
                            <label for="sanksi" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Privasi</label>
                            <div class="flex space-x-6">
                              <div class="flex items-center ">
                                  <input checked id="default-radio-1" type="radio" value="1" name="privasi" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                  <label for="default-radio-1" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Public</label>
                              </div>
                              <div class="flex items-center">
                                  <input  id="default-radio-2" type="radio" value="2" name="privasi" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                  <label for="default-radio-2" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Private</label>
                              </div>
                            </div>
                          </div>
                      </div>

                      <div class="mb-8">
                        <p class="text-sm font-medium text-gray-500 mb-1">Total Gajih</p>
                        <div class="flex">
                          <p class="font-bold mr-1">Rp</p>
                            <p class="font-bold text-2xl" id="result"></p>
                        </div>

                      </div>
                      
                        
                      <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Submit</button>

                  </div>
              </div>
          </div>
      </section>

  </form>
</div>

  @push('jsBody')

  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const sanksi = document.getElementById("sanksi");
      const bonus = document.getElementById("bonus");
      const potongBulanan = document.getElementById("potong_bulanan");
      const potongPremi = document.getElementById("potong_premi");
      const bulanan = document.getElementById("bulanan");
      const harian = document.getElementById("harian");
      const premi = document.getElementById("premi");
      const result = document.getElementById("result");

      function calculateSum() {
          const rupiahHarian = parseInt(harian.value)*26;
          const totalGajih = rupiahHarian + parseInt(bulanan.value) + parseInt(premi.value) + parseInt(bonus.value);
          const totalSanksi = parseInt(potongBulanan.value) + parseInt(potongPremi.value) + parseInt(sanksi.value);
          const gajih = totalGajih-totalSanksi;

          result.textContent = gajih.toLocaleString('id-ID');

          console.log(gajih);
          
      }

      calculateSum();

      sanksi.addEventListener("input", calculateSum);
      bonus.addEventListener("input", calculateSum);
  });
  </script>
      
  @endpush
   


</x-layouts.layout>