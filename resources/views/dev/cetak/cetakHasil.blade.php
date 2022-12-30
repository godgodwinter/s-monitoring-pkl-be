<x-cetak.cetak-style3></x-cetak.cetak-style3>
{{-- <link rel="stylesheet" href="{{ asset('/') }}assets/css/babeng.css" /> --}}
<style>
    .meter {
        height: 14px;
        /* Can be anything */
        position: relative;
        background: #555;
        -moz-border-radius: 25px;
        -webkit-border-radius: 25px;
        border-radius: 25px;
        padding: 2px;
        -webkit-box-shadow: inset 0 -1px 1px rgba(255, 255, 255, 0.3);
        -moz-box-shadow: inset 0 -1px 1px rgba(255, 255, 255, 0.3);
        box-shadow: inset 0 -1px 1px rgba(255, 255, 255, 0.3);
    }

    .meter>span {
        display: block;
        height: 100%;
        -webkit-border-top-right-radius: 8px;
        -webkit-border-bottom-right-radius: 8px;
        -moz-border-radius-topright: 8px;
        -moz-border-radius-bottomright: 8px;
        border-top-right-radius: 8px;
        border-bottom-right-radius: 8px;
        -webkit-border-top-left-radius: 20px;
        -webkit-border-bottom-left-radius: 20px;
        -moz-border-radius-topleft: 20px;
        -moz-border-radius-bottomleft: 20px;
        border-top-left-radius: 20px;
        border-bottom-left-radius: 20px;
        background-color: #f1a165;
        background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0, #f1a165), color-stop(1, #f36d0a));
        background-image: -webkit-linear-gradient(top, #f1a165, #f36d0a);
        background-image: -moz-linear-gradient(top, #f1a165, #f36d0a);
        background-image: -ms-linear-gradient(top, #f1a165, #f36d0a);
        background-image: -o-linear-gradient(top, #f1a165, #f36d0a);
        -webkit-box-shadow: inset 0 2px 9px rgba(255, 255, 255, 0.3), inset 0 -2px 6px rgba(0, 0, 0, 0.4);
        -moz-box-shadow: inset 0 2px 9px rgba(255, 255, 255, 0.3), inset 0 -2px 6px rgba(0, 0, 0, 0.4);
        position: relative;
        overflow: hidden;
    }
 .page-break {
  page-break-after: always;
}
</style>

<body>

    @foreach ($dataCetak as $cetak)
    <table width="99%" id="tableBiasa" border="0">
        <tr>
            @php
            $datalogo='assets/img/kop.png';
            @endphp
            <td width="99%" align="right" style="padding-bottom:15px"><img src="{{$datalogo}}" width="100%"></td>

        </tr>
    </table>


    <div class="babeng-default">
        <center>
            {{-- <h4 class="text-center">DETEKSI KECERDASAN EMOSI, SOSIAL DAN SPIRITUAL </h4> --}}
            {{-- <h4 class="text-center"> (EQ; Sc.Q; SQ)</h4>
            <h4 class="text-center">ASPEK - ASPEK KECERDASAN EQ; Sc.Q ; SQ</h4> --}}
        </center>
        <br>
        {{-- <table width="50%" class="table table-sm table-light" id="tableBiasa"> --}}
        <table width="50%" id="tableBiasa">
            <tr>
                <th width="100px">Nama</th>
                <th width="4%">:</th>
                <th>{{ $cetak->dataSiswa->nama }}</th>
            </tr>
            <tr>
                <th width="100px">Kelas</th>
                <th width="4%">:</th>
                <th>{{ $cetak->dataSiswa->kelasdetail?$cetak->dataSiswa->kelasdetail->kelas->tingkatan." ".$cetak->dataSiswa->kelasdetail->kelas->jurusan_table->nama." ".$cetak->dataSiswa->kelasdetail->kelas->suffix:null }} </th>
            </tr>
        </table>


    <h4  class="pt-2"> Penilaian Guru : {{ $cetak->hasil->penilaian_guru_rekap }}</h4>
    <table width="50%" id="tableBiasa">
        @foreach ($cetak->hasil->penilaian_guru as $pg)
        <tr>
            <th width="10px">{{ $loop->index+1 }}.</th>
            <th width="300px">{{ $pg->nama }}</th>
            <th width="60px">:</th>
            <th width="400px">{{ $pg->nilai }} </th>
        </tr>
        @endforeach
        <tr>
            <th width="10px"></th>
            <th width="300px">Rata-rata</th>
            <th width="60px">:</th>
            <th width="400px">{{ $cetak->hasil->penilaian_guru_avg }} </th>
        </tr>
        <tr>
            <th width="10px"></th>
            <th width="300px">Persentase</th>
            <th width="60px">:</th>
            <th width="400px">{{ $cetak->hasil->penilaian_guru_setting_persentase }} %</th>
        </tr>
        <tr>
            <th width="10px"></th>
            <th width="300px">Nilai Akhir</th>
            <th width="60px">:</th>
            <th width="400px">{{ $cetak->hasil->penilaian_guru_rekap }} </th>
        </tr>
    </table>
    <h4  class="pt-2">Penilaian Pembimbing lapangan : {{ $cetak->hasil->penilaian_pembimbinglapangan_rekap }}</h4>
    <table width="50%" id="tableBiasa">
        @foreach ($cetak->hasil->penilaian_pembimbinglapangan as $pg)
        <tr>
            <th width="10px">{{ $loop->index+1 }}.</th>
            <th width="300px">{{ $pg->nama }}</th>
            <th width="60px">:</th>
            <th width="400px">{{ $pg->nilai }} </th>
        </tr>
        @endforeach
        <tr>
            <th width="10px"></th>
            <th width="300px">Rata-rata</th>
            <th width="60px">:</th>
            <th width="400px">{{ $cetak->hasil->penilaian_pembimbinglapangan_avg }} </th>
        </tr>
        <tr>
            <th width="10px"></th>
            <th width="300px">Persentase</th>
            <th width="60px">:</th>
            <th width="400px">{{ $cetak->hasil->penilaian_pembimbinglapangan_setting_persentase }} %</th>
        </tr>
        <tr>
            <th width="10px"></th>
            <th width="300px">Nilai Akhir</th>
            <th width="60px">:</th>
            <th width="400px">{{ $cetak->hasil->penilaian_pembimbinglapangan_rekap }} </th>
        </tr>
    </table>
    <h4  class="pt-2"> Penilaian Absensi : {{ $cetak->hasil->penilaian_absensi_rekap }}</h4>
    <table width="50%" id="tableBiasa">
        <tr>
            <th width="10px"></th>
            <th width="300px">Nilai Absensi</th>
            <th width="60px">:</th>
            <th width="400px">{{ $cetak->hasil->penilaian_absensi }} </th>
        </tr>
        <tr>
            <th width="10px"></th>
            <th width="300px">Persentase</th>
            <th width="60px">:</th>
            <th width="400px">{{ $cetak->hasil->penilaian_absensi_setting_persentase }} %</th>
        </tr>
        <tr>
            <th width="10px"></th>
            <th width="300px">Nilai Akhir</th>
            <th width="60px">:</th>
            <th width="400px">{{ $cetak->hasil->penilaian_absensi_rekap }} </th>
        </tr>
    </table>
    <h4  class="pt-2"> Penilaian Jurnal {{ $cetak->hasil->penilaian_jurnal_rekap }}</h4>
    <table width="50%" id="tableBiasa">
        <tr>
            <th width="10px"></th>
            <th width="300px">Nilai Jurnal</th>
            <th width="60px">:</th>
            <th width="400px">{{ $cetak->hasil->penilaian_jurnal }} </th>
        </tr>
        <tr>
            <th width="10px"></th>
            <th width="300px">Persentase</th>
            <th width="60px">:</th>
            <th width="400px">{{ $cetak->hasil->penilaian_jurnal_setting_persentase }} %</th>
        </tr>
        <tr>
            <th width="10px"></th>
            <th width="300px">Nilai Akhir</th>
            <th width="60px">:</th>
            <th width="400px">{{ $cetak->hasil->penilaian_jurnal_rekap }} </th>
        </tr>
    </table>
    <h4  class="pt-2"> Nilai Akhir : {{ $cetak->hasil->nilaiakhir }}</h4>
    <table width="50%" id="tableBiasa">
        <tr>
            <th width="10px"></th>
            <th width="300px">Penilaian Guru</th>
            <th width="60px">:</th>
            <th width="400px">{{ $cetak->hasil->penilaian_guru_rekap }} </th>
        </tr>
        <tr>
            <th width="10px"></th>
            <th width="300px">Penilaian Pembimbing lapangan</th>
            <th width="60px">:</th>
            <th width="400px">{{ $cetak->hasil->penilaian_pembimbinglapangan_rekap }} </th>
        </tr>
        <tr>
            <th width="10px"></th>
            <th width="300px">Penilaian Absensi</th>
            <th width="60px">:</th>
            <th width="400px">{{ $cetak->hasil->penilaian_absensi_rekap }} </th>
        </tr>
        <tr>
            <th width="10px"></th>
            <th width="300px">Penilaian Jurnal</th>
            <th width="60px">:</th>
            <th width="400px">{{ $cetak->hasil->penilaian_jurnal_rekap }} </th>
        </tr>
    </table>


        <table width="50%" class="table table-light" id="tableBiasa">
            <tr>
                <th width="3%"></th>
                <th width="40%" align="center">
                    {{-- <br>
                    <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <br><br><br><br><br><br><br><br> --}}
                    {{-- <hr style="width:70%; border-top:2px dotted; border-style: none none dotted;  "> --}}

                </th>

                <th width="24%"></th>

                <th width="50%" align="center">
                    <center>
                        Mengetahui <br>
                        Pimpinan YPMT
                        <br><br><br><br>

                        Drs. Kepsek
                        <center>
                </th>
                <th width="3%"></th>

            </tr>

        </table>
    </div>
    <div class="page-break"></div>
    @endforeach
</body>
