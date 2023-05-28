<x-layout>
    <x-menu></x-menu>
    
{{$json_data['player1_name']}}VS{{$json_data['player2_name']}}<br>
<div class="flex justify-center">
    <div class="flex flex-col">
        <div id="turn_player" class="rounded border bg-yellow-500 pb-10 tex-center">
            @if (isset($win_player))
            {{$win_player}}の勝利
            @endif
        </div>
        <table class="border-collapse border border-slate-900">
            <form action="/battle" method="post">
            @csrf
                @for($i=1; $i<=14; $i++)
                    <tr class="border border-slate-900">
                        @for($j=1; $j<=14; $j++)
                        <?php $squ_num = $j + 14 * ($i - 1); ?>
                            <td class="border border-slate-900 w-8 h-8 text-center bg-amber-100 " >
                                {{-- @if (($json_data[$squ_num] == 0)) --}}
                                @if (($json_data["squ_{$squ_num}"] == 0))
                                    @if (!isset($win_player))
                                    <div class="justify-center">
                                    <button type="submit" name="btn_num" value="{{$squ_num}}">
                                        .
                                    </button>
                                    @else
                                    .
                                    </div>
                                    @endif
                                {{-- @elseif($json_data[$squ_num] == 1) --}}
                                @elseif($json_data["squ_{$squ_num}"] == 1)
                                    ◯
                                {{-- @elseif($json_data[$squ_num] == 2) --}}
                                @elseif($json_data["squ_{$squ_num}"] == 2)
                                    ●
                                @endif
                                <input type="hidden" name="num" value="{{$squ_num}}">
                                <input type="hidden" name="room_no" value="{{$json_data['room_no']}}">
                            </td>
                        @endfor  
                    </tr>
                @endfor
            </form>
        </table>
    </div>
</div>

<script>
    'use strict';

    let room_no = 72872;
    console.log(`ルームナンバーは${room_no}`);
    //手番の制御
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
    url: '/return_json',
    type: 'POST',
    dataType: 'json',
    data:{room_no: room_no},
    })
    .done( function(response) {
        // 取得したJSONデータを表示
        // $('#result').text(JSON.stringify(response));
        
        console.log('取得できました');
        console.log(response[0].comment);

    })
    .fail( function(jqXHR, textStatus, errorThrown) {
        // console.error(error);
        console.log('失敗');
        console.log("ajax通信に失敗しました");
        console.log("jqXHR          : " + jqXHR.status); // HTTPステータスが取得
        console.log("textStatus     : " + textStatus);    // タイムアウト、パースエラー
        console.log("errorThrown    : " + errorThrown); // 例外情報
        console.log("URL            : " + '/return_json');
    });

    function turn_player(data){

    }
    
</script>

</x-layout>
