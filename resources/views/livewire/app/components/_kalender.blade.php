
{{-- <div class="section full no-border" style="margin-top:-20px;">
    <div class="wide-block no-border mt-2 pt-5 pb-5 bg-danger text-center">
        <div wire:loading wire:target="showPage">
            <div class="text-center spinner-grow text-white" role="status"></div>
        </div>
    </div>
</div> --}}
<div class="section mb-2" style="margin-top: -30px">
    <div class="card">
        <div class="card-body d-flex justify-content-between align-items-end">
            <div>
                <h6 class="card-subtitle" style="line-height: 10px">Kalender Kerja Bulan</h6>
                <h5 style="line-height: 15px" class="card-title mb-0 d-flex align-items-center justify-content-between">
                    {{ now()->translatedFormat('F Y') }}
                </h5>
            </div>
        </div>
    </div>
</div>

<div class="section mt-5 mb-2">
    <div class="card">
            <div class="table-responsive">
            @php
                $today = \Carbon\Carbon::today();
                $tempDate = \Carbon\Carbon::createFromDate($today->year, $today->month, 1);

                echo '<table class="table table-bordered table-sm">
                    <thead>
                        <tr class="table-active text-center">
                            <th width="20px">Mg</th>
                            <th width="20px">Sn</th>
                            <th width="20px">Sl</th>
                            <th width="20px">Rb</th>
                            <th width="20px">Km</th>
                            <th width="20px">Jm</th>
                            <th width="20px">Sb</th>
                        </tr>
                    </thead>';

                    $skip = $tempDate->dayOfWeek;
                    // echo $skip;
                    for($i = 0; $i < $skip; $i++) {
                        $tempDate->subDay();
                    }
                    do //loops through month
                    {
                        echo '<tr class="text-center">';
                            $libur = $jadwal->hari_libur->flatten();
                            //loops through each week
                            for($i=0; $i < 7; $i++) {
                                if ($libur->contains($tempDate->day)) {
                                    echo '<td height="40px" class="align-middle"><span class="text-center text-danger">' ; echo  $tempDate->day;
                                } else {
                                    echo '<td height="40px" class="align-middle '.($tempDate->day == $today->day ? "bg-danger":"").'"><span class="text-center">' ; echo  $tempDate->day;
                                }

                                echo '</span></td>';
                                $tempDate->addDay();
                            }
                            echo '</tr>';

                    }while($tempDate->month == $today->month);
                    echo '</table>';
            @endphp
            </div>
    </div>
</div>

